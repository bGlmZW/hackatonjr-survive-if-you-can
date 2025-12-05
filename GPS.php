<?php 

namespace App; // Ou ton namespace

use Location\Location;
use SplPriorityQueue;

class GPS {
    
    // Algorithme de Dijkstra
    public static function findPath(Location $start, Location $end, array $allLocations) {
        $distances = [];
        $previous = [];
        $queue = new SplPriorityQueue();

        foreach ($allLocations as $loc) {
            $distances[$loc->name] = INF; // Infini par défaut
            $previous[$loc->name] = null;
        }

        $distances[$start->name] = 0;
        $queue->insert($start, 0);

        while (!$queue->isEmpty()) {
            $current = $queue->extract();

            if ($current === $end) {
                break; // On est arrivé
            }

            foreach ($current->roads as $road) {
                $neighbor = $road->destination;
                $alt = $distances[$current->name] + $road->time;

                if ($alt < $distances[$neighbor->name]) {
                    $distances[$neighbor->name] = $alt;
                    $previous[$neighbor->name] = [$current, $road]; // On garde le point précédent et la route utilisée
                    $queue->insert($neighbor, -$alt); // Priorité négative car SplPriorityQueue est MaxHeap
                }
            }
        }

        return self::reconstructPath($previous, $start, $end);
    }

    private static function reconstructPath($previous, $start, $end) {
        $path = [];
        $currentName = $end->name;
        
        if (!isset($previous[$currentName]) && $start !== $end) {
            return null; // Pas de chemin trouvé
        }

        while (isset($previous[$currentName])) {
            $step = $previous[$currentName]; // [Location, Road]
            array_unshift($path, $step); // Ajoute au début
            $currentName = $step[0]->name;
        }
        return $path;
    }
}