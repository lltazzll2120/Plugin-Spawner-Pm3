<?php


namespace Tazz\Spawners\Entities;


use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\nbt\tag\ListTag;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Cow extends Animal {

    public const NETWORK_ID = self::COW;

    public $width = 0.9;
    public $height = 1.3;

    public function getName(): string{
        return "Cow";
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
		$drops = [Item::get(Item::RAW_BEEF, 0, 1)];
		$drops2 = [Item::get(Item::LEATHER, 0, 1)];
                $mobcoin = Item::get(175, 0, 1);
                $mobcoin->setCustomName("§r§6Mob Coin");
                $mobcoin->setLore([
                '§r§7Contains Mob Coin when used',    
				'§r§eClick anywhere to open'
				]);  				
				$mobcoin->setNamedTagEntry(new ListTag(Item::TAG_ENCH));  
		if(mt_rand(1, 200) === mt_rand(1, 200)) {	 
             return [$mobcoin];        
		}		
		if(mt_rand(1, 5) === mt_rand(1, 5)) {	 
             return $drops2;        
		}		
        return $drops;
    }
}