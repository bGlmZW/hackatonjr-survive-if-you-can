<?php
namespace Location;

use Road\Road;
use Transport\Transport;

class Location 
{
    public $name;
    public $roads;
    public $position;

    public function __construct(string $name, $x, $y)
    {
        $this->name = $name;

        $this->position = [$x,$y];
        $this->roads = [];
    
    }

    public function addRoad(int $time, Location $destination, Transport $mode)
    {
        $this->roads[] = new Road($this, $destination, $mode, $time);
        
    }
}

