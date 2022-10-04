<?php


namespace Tazz\Spawners\Entities;


use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\nbt\tag\ListTag;

class ElderGuardian extends Monster {

    public const NETWORK_ID = self::ELDER_GUARDIAN;

    public $width = 1.9975;
    public $height = 1.9975;

    public function getName(): string{
        return "Elder Guardian";
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
                $mobcoin = Item::get(175, 0, 1);
                $mobcoin->setCustomName("§r§6Mob Coin");
                $mobcoin->setLore([
                '§r§7Contains Mob Coin when used',    
				'§r§eClick anywhere to open'
				]);  				
				$mobcoin->setNamedTagEntry(new ListTag(Item::TAG_ENCH));  		
		if(mt_rand(1, 50) === mt_rand(1, 50)) {	 
             return [$mobcoin];        
		}			
        return [
            Item::get(Item::PRISMARINE_CRYSTALS, 0, mt_rand(0, 1)),
            Item::get(Item::PRISMARINE_SHARD, 0, mt_rand(0, 1)),
        ];
    }
}