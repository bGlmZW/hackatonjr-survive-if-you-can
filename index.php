<?php
require_once __DIR__ . '/Location.php';  
require_once __DIR__ . '/Road.php';  
require_once __DIR__ . '/Transport.php';  

use Location\Location;
use Transport\Foot; 
use Transport\Transport;

$a = new Location("Cy Tech", 0, 0);

$b = new Location("RER A", 1, 1);

$a->addRoad(5, $b, Transport::Foot);
$a->addRoad(1, $b, Transport::Train);

echo $a->name."->".$a->roads[0]->destination->name.$a->roads[0]->time.$a->roads[0]->transport;
echo "</br>";

echo $b->name."Cergy préfecture";

?>