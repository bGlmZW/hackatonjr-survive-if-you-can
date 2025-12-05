<?php
require_once __DIR__ . '/Location.php';  
require_once __DIR__ . '/Road.php';  
require_once __DIR__ . '/Transport.php';

use Location\Location;
// use Road\Road; // pas obligatoire ici

/**
 * Calcule le plus court chemin entre $start et $end pour un mode de transport donné
 * en utilisant l'algorithme de Dijkstra.
 *
 * @param Location[]        $locations
 * @param Location          $start
 * @param Location          $end
 * @param int|string        $mode     Mode de transport (ex : Transport::Car)
 *
 * @return array{
 *     distance: float,
 *     path: Location[],
 *     names: string[]
 * }|null
 */
function shortestpath(array $locations, Location $start, Location $end, $mode): ?array 
{
    // Indexation des lieux par leur nom
    $locByname = [];
    foreach ($locations as $loc) {
        $locByname[$loc->name] = $loc;
    }

    $startName = $start->name;
    $endName   = $end->name;

    // Vérification que les lieux existent dans la liste
    if (!isset($locByname[$startName]) || !isset($locByname[$endName])) {
        return null;
    }

    $dist      = [];
    $previous  = [];
    $unvisited = [];

    // Initialisation
    foreach ($locByname as $name => $loc) {
        $dist[$name]      = INF;
        $previous[$name]  = null;
        $unvisited[$name] = true;
    }

    $dist[$startName] = 0.0;

    // ==== Algorithme de Dijkstra ====
    while (!empty($unvisited)) {
        $currentName = null;
        $currentDist = INF;

        // On récupère le noeud non visité le plus proche
        foreach ($unvisited as $name => $_) {
            if ($dist[$name] < $currentDist) {
                $currentDist = $dist[$name];
                $currentName = $name;
            }
        }

        if ($currentName === null || $currentDist === INF) {
            // Plus de noeud atteignable
            break;
        }

        if ($currentName === $endName) {
            // On a atteint la destination
            break;
        }

        unset($unvisited[$currentName]);

        $currentLoc = $locByname[$currentName];

        // Parcours des routes sortantes compatibles avec le mode de transport
        foreach ($currentLoc->roads as $road) {
            // Ici, $road->transport contient normalement la constante Transport::XXX
            if ($road->transport !== $mode) {
                continue; // mauvaise catégorie de transport
            }

            $neighbor     = $road->destination;
            $neighborName = $neighbor->name;

            if (!array_key_exists($neighborName, $dist)) {
                continue;
            }

            $alt = $dist[$currentName] + $road->time;

            if ($alt < $dist[$neighborName]) {
                $dist[$neighborName]     = $alt;
                $previous[$neighborName] = $currentName;
            }
        }
    }

    // Si la distance reste infinie, pas de chemin
    if ($dist[$endName] === INF) {
        return null;
    }

    // ==== Reconstruction du chemin ====
    $pathNames = [];
    $current   = $endName;

    while ($current !== null) {
        array_unshift($pathNames, $current);
        $current = $previous[$current] ?? null;
    }

    $pathLocations = [];
    foreach ($pathNames as $name) {
        $pathLocations[] = $locByname[$name];
    }

    return [
        'distance' => $dist[$endName],
        'path'     => $pathLocations,
        'names'    => $pathNames,
    ];
}

/**
 * Calcule un chemin en imposant des arrêts intermédiaires.
 *
 * Exemple : start -> via1 -> via2 -> end
 *
 * @param Location[] $locations
 * @param Location   $start
 * @param Location[] $stops   Lieux intermédiaires dans l'ordre souhaité
 * @param Location   $end
 * @param int|string $mode
 *
 * @return array{
 *     distance: float,
 *     path: Location[],
 *     names: string[]
 * }|null
 */
function shortestpath_with_stops(
    array $locations,
    Location $start,
    array $stops,
    Location $end,
    $mode
): ?array {
    $allLocations = [];
    $allNames     = [];
    $totalDist    = 0.0;

    // On va faire : start -> stop1 -> stop2 -> ... -> end
    $currentStart = $start;
    $targets      = $stops;
    $targets[]    = $end;

    foreach ($targets as $index => $target) {
        $segment = shortestpath($locations, $currentStart, $target, $mode);
        if ($segment === null) {
            // Impossible de rejoindre un des segments
            return null;
        }

        $segmentLocations = $segment['path'];
        $segmentNames     = $segment['names'];

        // Pour tous les segments sauf le premier, on enlève le premier noeud
        // (qui est déjà présent comme fin du segment précédent)
        if ($index > 0) {
            array_shift($segmentLocations);
            array_shift($segmentNames);
        }

        $allLocations = array_merge($allLocations, $segmentLocations);
        $allNames     = array_merge($allNames, $segmentNames);
        $totalDist   += $segment['distance'];

        $currentStart = $target;
    }

    return [
        'distance' => $totalDist,
        'path'     => $allLocations,
        'names'    => $allNames,
    ];
}

/**
 * Version pratique pour UN seul lieu intermédiaire.
 *
 * @param Location[] $locations
 */
function shortestpath_with_intermediate(
    array $locations,
    Location $start,
    Location $via,
    Location $end,
    $mode
): ?array {
    return shortestpath_with_stops($locations, $start, [$via], $end, $mode);
}
