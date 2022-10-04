<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Enderman extends Monster {

    public const NETWORK_ID = self::ENDERMAN;

    public $width = 0.3;
    /** @var float */
    public $length = 0.9;
    public $height = 1.8;

    public function getName(): string{
        return "Enderman";
    }
	
    public function initEntity(): void{
        $this->setMaxHealth(6);
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
            Item::get(Item::ENDER_PEARL, 0, mt_rand(0, 1 * $lootingL)),
        ];
    }
}