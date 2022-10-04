<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Chicken extends Animal {

    public const NETWORK_ID = self::CHICKEN;

    public $width = 0.6;
    /** @var float */
    public $length = 0.6;
    public $height = 0;


    public function getName(): string{
        return "Chicken";
    }
	
    public function initEntity(): void{
        $this->setMaxHealth(5);
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
        $drops = [
            Item::get(Item::FEATHER, 0, 1),
            Item::get(Item::RAW_CHICKEN, 0, 1),
        ];

        return $drops;
    }
}