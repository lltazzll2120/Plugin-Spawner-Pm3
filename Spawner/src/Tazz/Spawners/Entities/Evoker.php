<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Evoker extends Monster {

    public const NETWORK_ID = self::EVOCATION_ILLAGER;

    public $width = 0.6;
    public $height = 1.95;

    public function getName(): string{
        return "Evoker";
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
            Item::get(Item::EMERALD, 0, mt_rand(0, 1 * $lootingL))
        ];
    }
}