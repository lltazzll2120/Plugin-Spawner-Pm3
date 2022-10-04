<?php


namespace Tazz\Spawners\Entities;


use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\nbt\tag\ListTag;

class Panda extends Animal
{

    public const NETWORK_ID = self::PANDA;

    public $width = 1.2;
    public $height = 1.2;
  

    public function getName(): string
    {
        return "Panda";
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
        $item = Item::get(351, 2, 1);
                $mobcoin = Item::get(175, 0, 1);
                $mobcoin->setCustomName("§r§6Mob Coin");
                $mobcoin->setLore([
                '§r§7Contains Mob Coin when used',    
				'§r§eClick anywhere to open'
				]);  				
				$mobcoin->setNamedTagEntry(new ListTag(Item::TAG_ENCH)); 	
		if(mt_rand(1, 500) === mt_rand(1, 500)) {	 
             return [$mobcoin];        
		}		
        return [$item];
    }
}