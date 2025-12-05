<?php
namespace Transport;

enum Transport
{
    case Car;
    case Train;
    case Foot;

    // Fonction pour l'icône
    public function getIcon(): string
    {
        return match($this) {
            self::Car => '🚗',
            self::Train => '🚆',
            self::Foot => '🚶',
        };
    }

    // Fonction pour le nom
    public function getName(): string
    {
        return match($this) {
            self::Car => 'Voiture',
            self::Train => 'Train',
            self::Foot => 'À Pied',
        };
    }

    // Fonction pour la vitesse
    public function getSpeed(): int
    {
        return match($this) {
            self::Car => 60,
            self::Train => 120,
            self::Foot => 5,
        };
    }
}