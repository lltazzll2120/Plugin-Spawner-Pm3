<?php

namespace Tazz\Spawners\Entities;

use Tazz\Spawners\Pocketmine\AddActorPacket;

use pocketmine\entity\Living;
use pocketmine\Player;

class Bee extends Living
{

    public const NETWORK_ID = 122;

    public $width = 0.6;
    public $height = 0.6;

    public function getName(): string
    {
        return "Bee";
    }

    protected function sendSpawnPacket(Player $player): void
    {
        $pk = new AddActorPacket();
        $pk->entityRuntimeId = $this->getId();
        $pk->type = static::NETWORK_ID;
        $pk->position = $this->asVector3();
        $pk->motion = $this->getMotion();
        $pk->yaw = $this->yaw;
        $pk->headYaw = $this->yaw; //TODO
        $pk->pitch = $this->pitch;
        $pk->attributes = $this->attributeMap->getAll();
        $pk->metadata = $this->propertyManager->getAll();

        $player->dataPacket($pk);
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 3);
    }
}