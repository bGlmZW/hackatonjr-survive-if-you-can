<?php
require_once __DIR__ . '/Location.php';  
require_once __DIR__ . '/Road.php';  
require_once __DIR__ . '/Transport.php';  
require_once __DIR__ . '/Path.php';

use Location\Location;
use Transport\Foot; 
use Transport\Transport;

$a = new Location("Cy Tech", 0, 0);

$b = new Location("RER A", 1, 1);

$c = new Location("Shop", 2, 2);

$a->addRoad(5, $b, Transport::Foot);
$a->addRoad(1, $b, Transport::Train);
$a->addRoad(3, $c, Transport::Car);
$b->addRoad(2, $c, Transport::Train);

$locations = [$a, $b, $c];
$result = shortestpath($locations, $a, $c, Transport::Train);

if ($result === null) {
    echo "Aucun chemin trouvé.<br>";
} else {
    echo "<strong>Chemin trouvé :</strong> " . implode(" → ", $result["names"]) . "<br>";
    echo "<strong>Distance totale :</strong> " . $result["distance"] . " minutes<br><br>";
}


echo "</br>";
echo $a->name."->".$a->roads[0]->destination->name.$a->roads[0]->time.$a->roads[0]->transport->name;
echo "</br>";

echo $b->name."Cergy préfecture";

?>