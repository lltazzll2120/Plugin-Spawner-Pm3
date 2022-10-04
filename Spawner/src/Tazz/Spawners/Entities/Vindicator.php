<?php

namespace Tazz\Spawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Vindicator extends Monster {

    public const NETWORK_ID = self::VINDICATOR;

    public $width = 0.6;
    public $height = 1.95;


    public function getName(): string{
        return "Vindicator";
    }

    public function initEntity(): void{
        $this->setMaxHealth(24);
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
		    $o = mt_rand(0, 1),
            $uncommon = Item::get(339, 2, $o),
            $uncommon->setCustomName("§r§a§lVindicator XP Note"),
            $uncommon->setLore([
                '§r§7Congrats! You have killed an',
                '§r§7Vindicator that this XP NOTE',
                '§r§a§lXP:',
                '§r§a§l',
                '§r§a50 - 1,000 XP',
                '§r§a§l',
                '§r§eRight click to redeem this XP paper'	
                ]), 		
        ];
    }
}