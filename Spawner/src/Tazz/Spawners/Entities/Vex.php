<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\nbt\tag\ListTag;

class Vex extends Monster {

    public const NETWORK_ID = self::VEX;

    public $width = 0.4;
    public $height = 0.8;

    public function getName(): string{
        return "Vex";
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
        $drops = [Item::get(Item::AIR, 0, 0)];
                $mobcoin = Item::get(175, 0, 1);
                $mobcoin->setCustomName("§r§6Mob Coin");
                $mobcoin->setLore([
                '§r§7Contains Mob Coin when used',    
				'§r§eClick anywhere to open'
				]);  				
				$mobcoin->setNamedTagEntry(new ListTag(Item::TAG_ENCH)); 	
		if(mt_rand(1, 25) === mt_rand(1, 25)) {	 
             return [$mobcoin];        
		}		
        return $drops;
    }
}	