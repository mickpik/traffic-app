<?php

namespace App\Document;

use \DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="traffic_jam")
 */
class TrafficJam
{
    /**
     * @MongoDB\Id(strategy="auto", name="_id")
     * @var string|null
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     * @var string
     */
    private $slug;

    /**
     * @MongoDB\Field(type="string")
     * @var string
     */
    private $from;

    /**
     * @MongoDB\EmbedOne(targetDocument="Geolocation")
     * @var Geolocation
     */
    private $fromLoc;

    /**
     * @MongoDB\Field(type="string")
     * @var string
     */
    private $to;

    /**
     * @MongoDB\EmbedOne(targetDocument="Geolocation")
     * @var Geolocation
     */
    private $toLoc;

    /**
     * @MongoDB\Field(type="integer")
     * @var integer|null
     */
    private $delay;

    /**
     * @MongoDB\Field(type="integer")
     * @var integer|null
     */
    private $distance;

    /**
     * @MongoDB\Field(type="date")
     * @var DateTime|null
     */
    private $start;

    /**
     * @MongoDB\Field(type="date")
     * @var DateTime|null
     */
    private $end;

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     * @return TrafficJam
     */
    public function setId(?string $id): TrafficJam
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return TrafficJam
     */
    public function setSlug(string $slug): TrafficJam
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return TrafficJam
     */
    public function setFrom(string $from): TrafficJam
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return Geolocation
     */
    public function getFromLoc(): Geolocation
    {
        return $this->fromLoc;
    }

    /**
     * @param Geolocation $fromLoc
     * @return TrafficJam
     */
    public function setFromLoc(Geolocation $fromLoc): TrafficJam
    {
        $this->fromLoc = $fromLoc;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return TrafficJam
     */
    public function setTo(string $to): TrafficJam
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return Geolocation
     */
    public function getToLoc(): Geolocation
    {
        return $this->toLoc;
    }

    /**
     * @param Geolocation $toLoc
     * @return TrafficJam
     */
    public function setToLoc(Geolocation $toLoc): TrafficJam
    {
        $this->toLoc = $toLoc;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDelay(): ?int
    {
        return $this->delay;
    }

    /**
     * @param int|null $delay
     * @return TrafficJam
     */
    public function setDelay(?int $delay): TrafficJam
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDistance(): ?int
    {
        return $this->distance;
    }

    /**
     * @param int|null $distance
     * @return TrafficJam
     */
    public function setDistance(?int $distance): TrafficJam
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getStart(): ?DateTime
    {
        return $this->start;
    }

    /**
     * @param DateTime|null $start
     * @return TrafficJam
     */
    public function setStart(?DateTime $start): TrafficJam
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    /**
     * @param DateTime|null $end
     * @return TrafficJam
     */
    public function setEnd(?DateTime $end): TrafficJam
    {
        $this->end = $end;
        return $this;
    }
}
