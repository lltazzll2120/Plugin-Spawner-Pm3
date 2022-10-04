<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use function mt_rand;

class Slime extends Living {

    public const NETWORK_ID = self::SLIME;

    public $width = 0.6;
    public $height = 1.8;

    public function getName(): string{
        return "Slime";
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
        return [
            $asd = Item::get(339, 150, mt_rand(0, 1 * $lootingL)),
	        $asd->setCustomName("§r§a§lSlime Money Note"),
			$asd->setLore([
            '§r§7Congrats! You have killed an',
            '§r§7Slime that give money when clicked',
            '§r§a§lXP:',
            '§r§a§l',
            '§r§a1,000 - 10,000 $',
            '§r§a§l',
            '§r§eRight click to redeem this Money note'	
            ]),
            Item::get(Item::SLIME, 0, 1),
        ];
    }
}