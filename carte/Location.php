<?php 
namespace Location;

use Road\Road;
use Transport\Transport;

class Location 
{
    public $name;
    public $roads = [];
    public $position; // [x, y]
    public $category; // Ajout pour respecter la consigne "catégories" 

    public function __construct(string $name, $x, $y, string $category = "Neutre")
    {
        $this->name = $name;
        $this->position = [$x, $y];
        $this->category = $category;
    }

    // Calcul de la distance euclidienne vers un autre point
    public function getDistanceFrom(Location $other): float
    {
        $deltaX = $this->position[0] - $other->position[0];
        $deltaY = $this->position[1] - $other->position[1];
        return sqrt(($deltaX * $deltaX) + ($deltaY * $deltaY));
    }

    public function addRoad(Location $destination, Transport $mode)
    {
        // On ne passe plus le temps, le constructeur de Road va le calculer
        $this->roads[] = new Road($this, $destination, $mode);
    }
}