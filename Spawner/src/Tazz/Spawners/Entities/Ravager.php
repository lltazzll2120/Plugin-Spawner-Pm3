<?php

namespace Tazz\Spawners\Entities;

use Tazz\Spawners\Pocketmine\AddActorPacket;
use pocketmine\entity\Living;
use pocketmine\item\Item;
use pocketmine\Player;

class Ravager extends Living
{
    public const NETWORK_ID = 59;

    public $width = 1.975;
    public $height = 2.2;

    public function getName(): string
    {
        return "Ravager";
    }

    /**
     * @param Player $player
     */
    protected function sendSpawnPacket(Player $player): void
    {
        $pk = new AddActorPacket();
        $pk->entityRuntimeId = $this->getId();
        $pk->type = static::NETWORK_ID;
        $pk->position = $this->asVector3();
        $pk->motion = $this->getMotion();
        $pk->yaw = $this->yaw;
        $pk->headYaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->attributes = $this->attributeMap->getAll();
        $pk->metadata = $this->propertyManager->getAll();

        $player->dataPacket($pk);
    }

    public function getDrops(): array
    {
        //Ravager drops aren't affected by Looting
        return [
            $uncommon = Item::get(339, 2, 1),
            $uncommon->setCustomName("§r§a§lRavager XP Note"),
            $uncommon->setLore([
                '§r§7Congrats! You have killed an',
                '§r§7Ravager that this XP NOTE',
                '§r§a§lXP:',
                '§r§a§l',
                '§r§a50 - 1,000 XP',
                '§r§a§l',
                '§r§eRight click to redeem this XP paper'	
                ]), 				
        ];
    }
}