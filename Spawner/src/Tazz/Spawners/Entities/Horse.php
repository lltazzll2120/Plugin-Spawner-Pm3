<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Horse extends Animal
{

    public $width = 2;
    public $height = 3;

    public const NETWORK_ID = self::HORSE;

    public function getName(): string
    {
        return "Horse";
    }

    public function getDrops(): array
    {
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
        return $drops = [
            Item::get(Item::LEATHER, 0, mt_rand(0, 2 * $lootingL)),
        ];
    }
}