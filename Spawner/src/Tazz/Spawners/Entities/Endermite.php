<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Monster;

class Endermite extends Monster {

    public const NETWORK_ID = self::ENDERMITE;

    public $height = 0.3;
    public $width = 0.4;

    public function getName(): string{
        return "Endermite";
    }
}