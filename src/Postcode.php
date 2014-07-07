<?php


namespace Jflight\PostcodeFinder;

use League\Geotools\Geotools;
use League\Geotools\Coordinate\Coordinate;

abstract class Postcode {

    public $postcode;

    public $latitude;

    public $longitude;

    protected $geotools;

    protected $file = null;

    public function __construct($postcode = null)
    {
        $this->postcode = $postcode;

        if ($this->file !== null)
        {
            $this->file = __DIR__ . $this->file;
        }
    }


    public function create($postcode)
    {
        return new static($postcode);
    }

    public function all()
    {
        if (! isset($this->all))
        {
            $this->all = array();
            $array = $this->getArrayFromCsv($this->file);

            foreach ($array as $itemArray)
            {
                $this->all[] = $this->createFromArray($itemArray);
            }
        }

        return $this->all;
    }

    public function findNearestPostcodes($radius)
    {
        if (! isset($this->postcode))
        {
            throw new \Exception('Postcode must be set before nearest postcodes can be found.');
        }
        $this->setCoordinates();
        return $this->getNearest($radius);
    }

    protected function setCoordinates()
    {
        foreach ($this->all() as $postcode)
        {
            if ($postcode->postcode === $this->postcode)
            {
                $this->latitude = $postcode->latitude;
                $this->longitude = $postcode->longitude;
                return;
            }
        }

        throw new \Exception('Current set postcode is not in the csv file.');
    }

    protected function getGeotoolsCoordinate($array)
    {
        return new Coordinate($array);
    }

    protected function getDistance($coordA, $coordB)
    {
        if (! isset($this->geotools))
        {
            $this->geotools = new Geotools();
        }

        return $this->geotools->distance()->setFrom($coordA)->setTo($coordB)->in('km')->vincenty();
    }

    protected function getArrayFromCsv($file)
    {
        $csvData = file_get_contents($file);
        $lines = explode(PHP_EOL, $csvData);
        $array = array();
        foreach ($lines as $line) {
            $array[] = str_getcsv($line);
        }
        return $array;
    }

    protected function createFromArray($array)
    {
        $postcode = new static;
        $postcode->postcode = $array[0];
        $postcode->latitude = $array[1];
        $postcode->longitude = $array[2];
        return $postcode;
    }

    protected function getNearest($radius)
    {
        $coordA = $this->getGeotoolsCoordinate(array($this->latitude, $this->longitude));
        $results = $this->getOnlyPostcodesInRadiusOfCoord($radius, $coordA);
        return $results;
    }

    protected function ifInRadiusAddToArray($radius, $distance, $postcode, $results)
    {
        if ($distance <= $radius) {
            $results[] = $postcode;
            return $results;
        }
        return $results;
    }

    protected function getOnlyPostcodesInRadiusOfCoord($radius, $coordA)
    {
        $results = array();
        foreach ($this->all() as $postcode) {
            $results = $this->addPostcodeToArrayIfInRadius($radius, $coordA, $postcode, $results);
        }
        return $results;
    }

    protected function addPostcodeToArrayIfInRadius($radius, $coordA, $postcode, $results)
    {
        $coordB = $this->getGeotoolsCoordinate(array($postcode->latitude, $postcode->longitude));
        $distance = $this->getDistance($coordA, $coordB);
        $results = $this->ifInRadiusAddToArray($radius, $distance, $postcode, $results);
        return $results;
    }
} 