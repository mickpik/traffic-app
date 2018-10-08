<?php

namespace App\Controller;

use App\Manager\TrafficJamManager;
use App\Provider\AnwbProvider;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends FOSRestController
{
    /** @var TrafficJamManager */
    private $trafficJamManager;

    /** @var AnwbProvider */
    private $trafficJamProvider;

    /**
     * ApiController constructor.
     * @param TrafficJamManager $trafficJamManager
     * @param AnwbProvider $trafficJamProvider
     */
    public function __construct(TrafficJamManager $trafficJamManager, AnwbProvider $trafficJamProvider)
    {
        $this->trafficJamManager = $trafficJamManager;
        $this->trafficJamProvider = $trafficJamProvider;
    }

    /**
     * @Rest\Get("/api")
     * @Rest\QueryParam(name="start", nullable=true)
     * @Rest\QueryParam(name="end", nullable=true)
     * @Rest\View()
     *
     * @param string|null $start
     * @param string|null $end
     * @return Response
     */
    public function index(string $start = null, string $end = null)
    {
        $start = $this->getDateTimeFromQueryParam($start);
        $end = $this->getDateTimeFromQueryParam($end);

        if ($start === null && $end === null) {
            try {
                $trafficJams = $this->trafficJamProvider->getCurrentTrafficJams();
            } catch (GuzzleException $exception) {
                // Use database as fallback when provider is offline
                $trafficJams = $this->trafficJamManager->all();
            }
        } else {
            $trafficJams = $this->trafficJamManager->findByDate($start, $end);
        }

        $view = $this->view($trafficJams, 200);
        return $this->handleView($view);
    }

    private function getDateTimeFromQueryParam(string $param = null)
    {
        $date = null;
        if ($param !== null) {
            $date = \DateTime::createFromFormat('d-m-Y', $param);
            if ($date === false) {
                return null;
            }
        }
        return $date;
    }
}
