<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\nbt\tag\ListTag;

class IronGolem extends Animal
{

    public const NETWORK_ID = self::IRON_GOLEM;

    public $width = 1.4;
    public $height = 2.7;

    public function getName(): string
    {
        return "Iron Golem";
    }

    public function initEntity(): void{
        $this->setMaxHealth(6);
        parent::initEntity();
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
		$o = mt_rand(0, 2);
        $iron = Item::get(351, 19, mt_rand(0, 3));
        $rose = Item::get(Item::RED_FLOWER, 0, $o);
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
                $xp = Item::get(384, 0, 1);
                $xp->setCustomName("§r§l§aIron Golem Experience Bottle");
                $xp->setLore([
                '§r',
                '§r§7Contains Experience when used',    
				'§r§7Click anywhere to open',
				' ',
                '§r§7Receive:§6 25 - 150 Experience'
				]);    				
                $mobcoin = Item::get(175, 0, 1);
                $mobcoin->setCustomName("§r§6Mob Coin");
                $mobcoin->setLore([
                '§r§7Contains Mob Coin when used',    
				'§r§eClick anywhere to open'
				]);  				
				$mobcoin->setNamedTagEntry(new ListTag(Item::TAG_ENCH));  						
		if(mt_rand(1, 400) === mt_rand(1, 400)) {	 
             return [$item, $item1, $iron];        
		}				
        if(mt_rand(0, 5) === 0) {
            return [$iron, $rose];
        }
		if(mt_rand(1, 60) === mt_rand(1, 60)) {	 
             return [$rose, $xp, $iron];        
		}	
		if(mt_rand(1, 50) === mt_rand(1, 50)) {	 
             return [$mobcoin];        
		}	
        return [$iron];
    }
}