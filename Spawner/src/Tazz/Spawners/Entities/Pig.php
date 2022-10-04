<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\nbt\tag\ListTag;

class Pig extends Animal {

    public const NETWORK_ID = self::PIG;

    public $width = 0.9;
    public $height = 0.9;
	
    public function getName(): string{
        return "Pig";
    }
	
    public function initEntity(): void{
        $this->setMaxHealth(4);
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
		$drops = [Item::get(Item::RAW_PORKCHOP, 0, 1)];
                $mobcoin = Item::get(175, 0, 1);
                $mobcoin->setCustomName("§r§6Mob Coin");
                $mobcoin->setLore([
                '§r§7Contains Mob Coin when used',    
				'§r§eClick anywhere to open'
				]);  				
				$mobcoin->setNamedTagEntry(new ListTag(Item::TAG_ENCH));  
		if(mt_rand(1, 250) === mt_rand(1, 250)) {	 
             return [$mobcoin];        
		}				
        return $drops;
    }
}