<?php
require_once __DIR__ . '/Location.php';  
require_once __DIR__ . '/Road.php';  
require_once __DIR__ . '/Transport.php';

use Location\Location;
use Road\Road;
use Transport\Transport;

function shortestpath(array $locations, Location $start, Location $end, Transport $mode): ?array 
{
    $locByname = [];
    foreach ($locations as $loc) {
        $locByname[$loc->name] = $loc;
    }

    $startName = $start->name;
    $endName   = $end->name;

    $dist      = [];
    $previous  = [];
    $unvisited = [];

    foreach ($locByname as $name => $loc) {
        $dist[$name]      = INF;
        $previous[$name]  = null;
        $unvisited[$name] = true;
    }

    $dist[$startName] = 0.0;

    while (!empty($unvisited)) {
        $currentName = null;
        $currentDist = INF;

        foreach ($unvisited as $name => $_) {
            if ($dist[$name] < $currentDist) {
                $currentDist = $dist[$name];
                $currentName = $name;
            }
        }

        if ($currentName === null || $currentDist === INF) {
            break;
        }

        if ($currentName === $endName) {
            break;
        }

        unset($unvisited[$currentName]);

        $currentLoc = $locByname[$currentName];

        foreach ($currentLoc->roads as $road) {
            if ($road->transport !== $mode) {
                continue;
            }

            $neighbor     = $road->destination;
            $neighborName = $neighbor->name;

            if (!array_key_exists($neighborName, $dist)) {
                continue;
            }

            $alt = $dist[$currentName] + $road->time;

            if ($alt < $dist[$neighborName]) {
                $dist[$neighborName]  = $alt;
                $previous[$neighborName] = $currentName;
            }
        }
    }

    if ($dist[$endName] === INF) {
        return null;
    }


    $pathNames = [];
    $current = $endName;

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
