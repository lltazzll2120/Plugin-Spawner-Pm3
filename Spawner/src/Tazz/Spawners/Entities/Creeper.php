<?php


namespace Tazz\Spawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Creeper extends Monster
{

    public const NETWORK_ID = self::CREEPER;

    public $height = 1.7;
    public $width = 0.6;


    public function getName(): string
    {
        return "Creeper";
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
        if (mt_rand(1, 10) < 3) {
            return [Item::get(Item::GUNPOWDER, 0, 1 * $lootingL)];
        }

        return [];
    }

}