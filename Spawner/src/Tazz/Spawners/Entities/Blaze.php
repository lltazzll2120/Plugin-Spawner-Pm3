<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\nbt\tag\ListTag;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Blaze extends Monster
{

    public const NETWORK_ID = self::BLAZE;

    public $width = 0.6;
    public $height = 1.8;

    public function getName(): string{
        return "Blaze";
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
        $drops = [Item::get(Item::BLAZE_ROD, 0, 1)];
                $item = Item::get(378, 130, 1);
                $item->setCustomName("§r§l§3Elixir Orb");
                $item->setLore([
                '§r',
                '§r§7Contains Elixir when used',    
				'§r§7Click anywhere to open',
				' ',
                '§r§7Receive: §326 - 85 Elixir'
				]); 		
                $item1 = Item::get(378, 120, 1);
                $item1->setCustomName("§r§l§bCE-EXP Orb");
                $item1->setLore([
                '§r',
                '§r§7Contains CE-EXP when used',    
				'§r§7Click anywhere to open',
				' ',
                '§r§7Receive: §330 - 103 CE-EXP'
				]);   
                $mobcoin = Item::get(175, 0, 1);
                $mobcoin->setCustomName("§r§6Mob Coin");
                $mobcoin->setLore([
                '§r§7Contains Mob Coin when used',    
				'§r§eClick anywhere to open'
				]);  				
				$mobcoin->setNamedTagEntry(new ListTag(Item::TAG_ENCH));  
		if(mt_rand(1, 450) === mt_rand(1, 450)) {	 
             return [$item, $item1];        
		}
		if(mt_rand(1, 100) === mt_rand(1, 100)) {	 
             return [$mobcoin];        
		}		
        return $drops;
    }
}