<?php

namespace App\Provider;

use App\Document\Geolocation;
use App\Document\TrafficJam;
use App\Manager\TrafficJamManager;
use Cocur\Slugify\Slugify;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Request;

class AnwbProvider
{
    private const URI = 'https://www.anwb.nl/feeds/gethf';

    /** @var TrafficJamManager */
    private $trafficJamManager;

    /**
     * AnwbProvider constructor.
     * @param TrafficJamManager $trafficJamManager
     */
    public function __construct(TrafficJamManager $trafficJamManager)
    {
        $this->trafficJamManager = $trafficJamManager;
    }

    /**
     * @return TrafficJam[]
     * @throws GuzzleException
     */
    public function getCurrentTrafficJams(): array
    {
        $trafficJams = [];
        $anwbTrafficJams = [];

        $client = new Client();
        $res = $client->request(Request::METHOD_GET, self::URI);
        $content = json_decode($res->getBody()->getContents());
        if (!empty($content->roadEntries)) {
            foreach ($content->roadEntries as $roadEntry) {
                if (!empty($roadEntry->events->trafficJams)) {
                    $anwbTrafficJams = array_merge($anwbTrafficJams, $roadEntry->events->trafficJams);
                }
            }
        }

        foreach ($anwbTrafficJams as $anwbTrafficJam) {
            $trafficJams[] = $this->translateResponse($anwbTrafficJam);
        }

        return $trafficJams;
    }

    /**
     * @return TrafficJam[]
     * @throws GuzzleException
     */
    public function saveCurrentTrafficJams(): array
    {
        $slugs = [];
        $savedTrafficJams = [];
        foreach ($this->getCurrentTrafficJams() as $trafficJam) {
            // Some responses dont have a start defined
            // We dont want these in our database because this make them unable to slugily
            if ($trafficJam->getStart() !== null) {
                $slugs[] = $trafficJam->getSlug();
                $savedTrafficJams[] = $trafficJam;
                $this->trafficJamManager->persist($trafficJam);
            }
        }

        $this->updateEndDates($slugs);
        $this->trafficJamManager->flush();

        return $savedTrafficJams;
    }

    private function translateResponse(\stdClass $anwbTrafficJam): TrafficJam
    {
        $from = $anwbTrafficJam->from;
        $to = $anwbTrafficJam->to;
        $start = null;
        $formattedStart = null;
        if (!empty($anwbTrafficJam->start)) {
            $start = \DateTime::createFromFormat('Y-m-d\TH:i:s', $anwbTrafficJam->start);
            $formattedStart = $start->format('d-m-y H:i:s');
        }
        // createFromFormat will return false if it cant convert string to DateTime
        if ($start === false) {
            $start = null;
        }

        $slugify = new Slugify();
        // AWNB Service has no identifier but we can assume the combination from, to & start is unique
        $slug = $slugify->slugify($from . $to . $formattedStart);

        $trafficJam = $this->trafficJamManager->findBySlug($slug);
        if ($trafficJam === null) {
            $trafficJam = new TrafficJam();
        }

        $trafficJam->setSlug($slug);
        $trafficJam->setFrom($from);
        $trafficJam->setFromLoc($this->translateResponseToGeo($anwbTrafficJam->fromLoc));
        $trafficJam->setTo($to);
        $trafficJam->setToLoc($this->translateResponseToGeo($anwbTrafficJam->toLoc));
        $trafficJam->setDelay($anwbTrafficJam->delay ?? null);
        $trafficJam->setDistance($anwbTrafficJam->distance ?? null);
        $trafficJam->setStart($start);

        return $trafficJam;
    }

    private function translateResponseToGeo(\stdClass $loc): Geolocation
    {
        $geo = new Geolocation();
        $geo->setLatitude($loc->lat);
        $geo->setLongitude($loc->lon);
        return $geo;
    }

    /**
     * @param string[] $slugs
     */
    private function updateEndDates(array $slugs): void
    {
        $trafficJams = $this->trafficJamManager->findByCriteria([
            'end' => null,
            'slug' => ['$nin' => $slugs]
        ]);
        // Retrieve traffic jams which are not in current list and with an empty end

        // Now is as close as we can get to the actual end
        $now = new \DateTime();
        foreach ($trafficJams as $trafficJam) {
            $trafficJam->setEnd($now);
            $this->trafficJamManager->persist($trafficJam);
        }
    }
}
