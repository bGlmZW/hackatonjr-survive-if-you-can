<?php
namespace Road;

use Location\Location;
use Transport\Transport;

class Road
{
    public $time;
    public $depart;
    public $destination;
    public $transport;

    public function __construct(Location $depart, Location $destination, Transport $transport, int $time)
    {
        $this->depart = $depart;
        $this->destination = $destination;
        $this->transport = $transport;
        $this->time = $time;
    }
}

