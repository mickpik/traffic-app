<?php

namespace App\Manager;

use App\Document\TrafficJam;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class TrafficJamManager
{
    /** @var DocumentManager */
    private $documentManager;

    /**
     * TrafficJamManager constructor.
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    private function getRepository(): ObjectRepository
    {
        return $this->documentManager->getRepository(TrafficJam::class);
    }

    public function flush(): void
    {
        $this->documentManager->flush();
    }

    public function persist(TrafficJam $trafficJam): void
    {
        $this->documentManager->persist($trafficJam);
    }

    /**
     * @return TrafficJam[]
     */
    public function all(): array
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param array $criteria
     * @return TrafficJam[]
     */
    public function findByCriteria(array $criteria): array
    {
        return $this->getRepository()->findBy($criteria);
    }

    public function findOneByCriteria(array $criteria): ?TrafficJam
    {
        /** @var TrafficJam|null $trafficJam */
        $trafficJam = $this->getRepository()->findOneBy($criteria);
        return $trafficJam;
    }

    public function findBySlug(string $slug): ?TrafficJam
    {
        return $this->findOneByCriteria(['slug' => $slug]);
    }

    /**
     * @param \DateTime|null $start
     * @param \DateTime|null $end
     * @return TrafficJam[]
     */
    public function findByDate(\DateTime $start = null, \DateTime $end = null): array
    {
        $criteria = [];
        if ($start !== null) {
            $criteria['start'] = ['$gte' => $start];
        }
        if ($end !== null) {
            $criteria['end'] = ['$lte' => $end];
        }
        return $this->findByCriteria($criteria);
    }
}
