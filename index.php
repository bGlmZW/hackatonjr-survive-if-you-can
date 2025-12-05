<?php
require_once __DIR__ . '/Location.php';  
require_once __DIR__ . '/Road.php';  
require_once __DIR__ . '/Transport.php';  
require_once __DIR__ . '/Path.php';

use Location\Location;
use Transport\Transport;

/**
 * Recherche un lieu par son nom (pratique si tu récupères les noms via un formulaire).
 */
function findLocationByName(array $locations, string $name): ?Location {
    foreach ($locations as $loc) {
        if (strcasecmp($loc->name, $name) === 0) {
            return $loc;
        }
    }
    return null;
}

// ============= 1. CRÉATION DES LIEUX =============
$cyTech        = new Location("Cy Tech", 0, 0);
$rerA          = new Location("RER A", 1, 1);
$downtown      = new Location("Centre-ville", 3, 1);
$hospital      = new Location("Hopital", 4, 2);
$police        = new Location("Commissariat", 4, 0);
$mall          = new Location("Centre commercial", 5, 1);
$stadium       = new Location("Stade", 6, 0);
$cemetery      = new Location("Cimetiere", 7, 0);

$safeHouse     = new Location("Safe House", -1, 2);
$forestCamp    = new Location("Camp de la foret", -2, 3);
$riverBridge   = new Location("Pont de la riviere", -3, 3);
$farm          = new Location("Ferme abandonnee", -4, 2);
$gasStation    = new Location("Station essence", -2, 1);

$oldFactory    = new Location("Ancienne usine", 6, 3);
$powerPlant    = new Location("Centrale electrique", 8, 3);
$tunnel        = new Location("Entree du tunnel", 7, 2);

$abandonedSt   = new Location("Gare abandonnee", 2, 3);
$trainDepot    = new Location("Depot de trains", 4, 4);

$radioTower    = new Location("Tour radio", -1, 5);
$bunker        = new Location("Bunker souterrain", 0, 4);

// ============= 2. CRÉATION DES ROUTES =============
// On ajoute des routes DANS LES DEUX SENS.

// ----- Zone campus / ville -----
// Cy Tech <-> RER A
$cyTech->addRoad(5,  $rerA,     Transport::Foot);
$cyTech->addRoad(2,  $rerA,     Transport::Train);
$rerA->addRoad(5,    $cyTech,   Transport::Foot);
$rerA->addRoad(2,    $cyTech,   Transport::Train);

// RER A <-> Centre-ville
$rerA->addRoad(10,   $downtown, Transport::Car);
$rerA->addRoad(5,    $downtown, Transport::Train);
$downtown->addRoad(10, $rerA,   Transport::Car);
$downtown->addRoad(5,  $rerA,   Transport::Train);

// Centre-ville <-> Hopital
$downtown->addRoad(6,  $hospital, Transport::Car);
$downtown->addRoad(15, $hospital, Transport::Foot);
$hospital->addRoad(6,  $downtown, Transport::Car);
$hospital->addRoad(15, $downtown, Transport::Foot);

// Centre-ville <-> Centre commercial
$downtown->addRoad(4,  $mall, Transport::Car);
$downtown->addRoad(12, $mall, Transport::Foot);
$mall->addRoad(4,      $downtown, Transport::Car);
$mall->addRoad(12,     $downtown, Transport::Foot);

// Hopital <-> Commissariat
$hospital->addRoad(3,  $police, Transport::Car);
$hospital->addRoad(8,  $police, Transport::Foot);
$police->addRoad(3,    $hospital, Transport::Car);
$police->addRoad(8,    $hospital, Transport::Foot);

// Centre-ville <-> Commissariat
$downtown->addRoad(3,  $police, Transport::Car);
$police->addRoad(3,    $downtown, Transport::Car);

// Commissariat <-> Stade
$police->addRoad(4,    $stadium, Transport::Car);
$stadium->addRoad(4,   $police,  Transport::Car);

// Stade <-> Cimetiere
$stadium->addRoad(7,   $cemetery, Transport::Foot);
$cemetery->addRoad(7,  $stadium,  Transport::Foot);

// Safe House <-> Camp de la forêt
$safeHouse->addRoad(6,    $forestCamp, Transport::Foot);
$forestCamp->addRoad(6,   $safeHouse,  Transport::Foot);

// Camp de la forêt <-> Pont de la rivière
$forestCamp->addRoad(4,   $riverBridge, Transport::Foot);
$riverBridge->addRoad(4,  $forestCamp,  Transport::Foot);

// Pont de la rivière <-> Ferme
$riverBridge->addRoad(7,  $farm,       Transport::Car);
$farm->addRoad(7,         $riverBridge, Transport::Car);

// Ferme <-> Station essence
$farm->addRoad(5,         $gasStation, Transport::Car);
$gasStation->addRoad(5,   $farm,       Transport::Car);

// Station essence <-> Centre-ville
$gasStation->addRoad(9,   $downtown, Transport::Car);
$downtown->addRoad(9,     $gasStation, Transport::Car);

// Centre commercial <-> Ancienne usine
$mall->addRoad(5,         $oldFactory, Transport::Car);
$oldFactory->addRoad(5,   $mall,       Transport::Car);

// Ancienne usine <-> Centrale électrique
$oldFactory->addRoad(6,   $powerPlant, Transport::Car);
$powerPlant->addRoad(6,   $oldFactory, Transport::Car);

// Centrale électrique <-> Tunnel
$powerPlant->addRoad(5,   $tunnel,     Transport::Car);
$tunnel->addRoad(5,       $powerPlant, Transport::Car);

// Tunnel <-> Centre-ville
$tunnel->addRoad(8,       $downtown,   Transport::Car);
$downtown->addRoad(8,     $tunnel,     Transport::Car);

// Gare abandonnée <-> Dépôt de trains
$abandonedSt->addRoad(3,  $trainDepot, Transport::Train);
$trainDepot->addRoad(3,   $abandonedSt, Transport::Train);

// Dépôt de trains <-> Centre-ville
$trainDepot->addRoad(4,   $downtown,   Transport::Train);
$downtown->addRoad(4,     $trainDepot, Transport::Train);

// Gare abandonnée <-> Cimetiere (à pied)
$abandonedSt->addRoad(10, $cemetery,   Transport::Foot);
$cemetery->addRoad(10,    $abandonedSt, Transport::Foot);

// Tour radio <-> Bunker
$radioTower->addRoad(8,   $bunker,     Transport::Foot);
$bunker->addRoad(8,       $radioTower, Transport::Foot);

// Bunker <-> Safe House
$bunker->addRoad(5,       $safeHouse,  Transport::Foot);
$safeHouse->addRoad(5,    $bunker,     Transport::Foot);

// Bunker <-> Centre-ville
$bunker->addRoad(20,      $downtown,   Transport::Foot);
$downtown->addRoad(20,    $bunker,     Transport::Foot);

// ============= 3. LISTE DES LIEUX =============
$locations = [
    $cyTech,
    $rerA,
    $downtown,
    $hospital,
    $police,
    $mall,
    $stadium,
    $cemetery,
    $safeHouse,
    $forestCamp,
    $riverBridge,
    $farm,
    $gasStation,
    $oldFactory,
    $powerPlant,
    $tunnel,
    $abandonedSt,
    $trainDepot,
    $radioTower,
    $bunker,
];

// ============= 4. CALCUL DES ITINÉRAIRES =============

// 1) Exemple à pied : Tour radio -> Bunker
$resultFoot = shortestpath($locations, $radioTower, $bunker, Transport::Foot);

// 2) Exemple en train : Gare abandonnée -> Centre-ville
$resultTrain = shortestpath($locations, $abandonedSt, $downtown, Transport::Train);

// 3) Exemple en voiture AVEC LIEU INTERMÉDIAIRE OBLIGATOIRE :
//    Centrale électrique -> (via Tunnel) -> Centre-ville
$resultCar = shortestpath_with_intermediate(
    $locations,
    $powerPlant,
    $tunnel,      // lieu intermédiaire obligatoire
    $downtown,
    Transport::Car
);

// ============= 5. AFFICHAGE DES RÉSULTATS =============
function displayResult(?array $result, string $label): void {
    echo "<h3>$label</h3>";
    if ($result === null) {
        echo "Aucun chemin trouvé.<br>";
    } else {
        echo "Chemin : " . implode(" → ", $result['names']) . "<br>";
        echo "Temps total : " . $result['distance'] . " minutes<br>";
    }
    echo "<hr>";
}

// Les labels correspondent maintenant bien aux trajets calculés
displayResult($resultFoot,  "Tour radio → Bunker (FOOT)");
displayResult($resultTrain, "Gare abandonnée → Centre-ville (TRAIN)");
displayResult($resultCar,   "Centrale électrique → Tunnel → Centre-ville (CAR)");
?>
