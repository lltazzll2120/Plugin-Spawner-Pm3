<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Mule extends Animal {

    public const NETWORK_ID = self::MULE;

    public $width = 1.3965;
    public $height = 1.6;


    public function getName(): string{
        return "Mule";
    }

    public function initEntity(): void{
        $this->setMaxHealth(20);
        parent::initEntity();
    }

    public function getDrops(): array{
        $lootingL = 1;
        $cause = $this->lastDamageCause;
        if($cause instanceof EntityDamageByEntityEvent){
            $dmg = $cause->getDamager();
            if($dmg instanceof Player){
                
                $looting = $dmg->getInventory()->getItemInHand()->getEnchantment(Enchantment::LOOTING);
                if($looting !== null){
                    $lootingL = $looting->getLevel();
                }else{
                    $lootingL = 1;
            }
            }
        }
        return [
            Item::get(Item::LEATHER, 0, mt_rand(1, 2 * $lootingL)),
        ];
    }
}