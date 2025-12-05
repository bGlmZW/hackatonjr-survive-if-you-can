<?php
// Road.php
namespace Road;

use Location\Location;
use Transport\Transport;

class Road
{
    public $time;
    public $depart;
    public $destination;
    public $transport;

    // Vitesses arbitraires (unités par heure ou minute)
    const SPEED_FOOT = 4;
    const SPEED_CAR = 50;  // Constante selon le sujet 
    const SPEED_TRAIN = 100; // Plus vite que la voiture 

    public function __construct(Location $depart, Location $destination, Transport $transport)
    {
        $this->depart = $depart;
        $this->destination = $destination;
        $this->transport = $transport;

        // 1. Calculer la distance
        $distance = $depart->getDistanceFrom($destination);

        // 2. Définir la vitesse
        $speed = match($transport) {
            Transport::Foot => self::SPEED_FOOT,
            Transport::Car => self::SPEED_CAR,
            Transport::Train => self::SPEED_TRAIN,
        };

        // 3. Calculer le temps (Temps = Distance / Vitesse)
        $this->time = $distance / $speed;
    }
}

