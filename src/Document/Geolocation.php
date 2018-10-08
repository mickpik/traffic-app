<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class Geolocation
{
    /**
     * @MongoDB\Field(type="float")
     * @var float
     */
    private $longitude;

    /**
     * @MongoDB\Field(type="float")
     * @var float
     */
    private $latitude;

    /**
     * @param float $longitude
     * @return self
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $latitude
     * @return self
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
}
