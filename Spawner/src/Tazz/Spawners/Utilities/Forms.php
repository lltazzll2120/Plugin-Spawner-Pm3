<?php

namespace Tazz\Spawners\Utilities;

use Tazz\Spawners\Events\SpawnerStackEvent;
use Tazz\Spawners\Events\SpawnerUnstackEvent;
use Tazz\Spawners\Main;
use Tazz\Spawners\Tiles\MobSpawnerTile;
use Tazz\FormAPI\CustomForm;
use Tazz\FormAPI\SimpleForm;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

class Forms
{

    /**
     * @var array
     */
    public static $usingSpawner = [];

    /**
     * @param MobSpawnerTile $spawner
     * @param Player $player
     */
    public static function sendSpawnerForm(MobSpawnerTile $spawner, Player $player): void
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $spawner = Forms::$usingSpawner[$player->getName()];
                    if ($spawner instanceof MobSpawnerTile) {
                        $spawner->sendAddSpawnersForm($player);
                    }
                    break;
                case 1:
                    $player->sendMessage("§a");
                    $player->sendMessage("§a");
                 	$player->sendMessage("§aChicken §8- §70.05% Chance to drop Mob Coins");
            		$player->sendMessage("§aPig §8- §70.01% Chance to drop Mob Coins");
             		$player->sendMessage("§aCow §8- §70.9% Chance to drop Mob Coins");
             		$player->sendMessage("§aSpider §8- §70.7% Chance to drop Mob Coins");
            		$player->sendMessage("§aSkeleton §8- §70.4% Chance to drop Mob Coins");
             		$player->sendMessage("§aEnderman §8- §70.1% Chance to drop Mob Coins");
             		$player->sendMessage("§aBlaze §8- §71% Chance to drop Mob Coins");
             		$player->sendMessage("§aIron Golem §8- §75% Chance to drop Mob Coins");
            		$player->sendMessage("§aElder Guardian §8- §710% Chance to drop Mob Coins");
              		$player->sendMessage("§aVex §8- §750% Chance to drop Mob Coins");
             		$player->sendMessage("");
             		$player->sendMessage("§a");				
                    break;
					case 3:
					break;
            }
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;

        $spawnerName = $spawner->getName();
        $count = $spawner->getCount();

        $form->setTitle("§e§lManage Spawners");
		$form->setContent("§6You've stacked: §r§a" . $count . "§r §a" . $spawnerName);
        $form->addButton("§r§aStack more §6" . $spawnerName);
        $form->addButton("§r§aSpawner Info");
		$form->addButton("§r§cClose");
        $player->sendForm($form);
    }

    public static function sendAddSpawnerForm(Player $player, MobSpawnerTile $spawner): void
    {
        $form = new CustomForm(function (Player $player, array $response = null) {
            if ($response === null) {
                return;
            }
            if (isset($response[1])) {
                $spawner = Forms::$usingSpawner[$player->getName()];
                if ($spawner instanceof MobSpawnerTile) {

                    $entityId = $spawner->getEntityId();
                    $count = (int)$response[1];

                    $item = $player->getInventory()->getItemInHand();

                    if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
                        $stackCount = $item->getCount();
                        $max = $stackCount;
                    } else {
                        $message = ConfigManager::getMessage("no-available-spawners");
                        $player->sendMessage("§a§l||§r§f You must hold the exact spawner type to stack!");
                        return;
                    }

                    if ($count > $max) {
                        $count = $max;
                        $message = ConfigManager::getMessage("all-spawners-stacked");
                        $player->sendMessage(Main::PREFIX . $message);
                    }

                    $item = $player->getInventory()->getItemInHand();
                    if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
                        ($event = new SpawnerStackEvent($player, $spawner, $count))->call();
                        if($event->isCancelled()) return;
                        $stackCount = $item->getCount();
                        $leftover = $stackCount - $count;
                        if ($leftover > 0) {
                            $item->setCount($leftover);
                            $player->getInventory()->setItemInHand($item);
                        } else {
                            $player->getInventory()->setItemInHand(Item::get(Item::AIR));
                        }
                        $spawner->setCount($spawner->getCount() + $count);
                    }
                }
            }
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;

        $spawnerName = $spawner->getName();
        $count = $spawner->getCount();
        $entityId = $spawner->getEntityId();

        $max = 1;

        $item = $player->getInventory()->getItemInHand();
        if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
            $stackCount = $item->getCount();
            $max = $stackCount;
        }

        $form->setTitle("§l§eManage Spawners");
        $form->addLabel("§7This spawner spawns:\n§a" . $spawnerName . "\n§a\n§a§7Stacking spawners are a way to increase the productivity of your spawners\n§e- §7Stacknig spawners will increases the spawn time of a spawner\n§a\n§71 Spawner increases the spawn rate by 10%\n\n§6You've stacked:§a " . $count . "\n§a");
        $form->addSlider(C::BOLD . C::GOLD . "§r§6Amount" . C::GREEN, 1, $max, 1);
		$player->sendForm($form);
	}

    public static function sendRemoveSpawnersForm(Player $player, MobSpawnerTile $spawner): void
    {
        $form = new CustomForm(function (Player $player, array $response = null) {
            if ($response === null) {
                return;
            }
            if (isset($response[1])) {
                $spawner = Forms::$usingSpawner[$player->getName()];
                if ($spawner instanceof MobSpawnerTile) {

                    $entityId = $spawner->getEntityId();
                    $count = (int)$response[1];
                    $max = $spawner->getCount();

                    $item = $player->getInventory()->getItemInHand();

                    if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
                        $stackCount = $item->getCount();
                        $max = $stackCount;
                    } else {
                        $message = ConfigManager::getMessage("no-available-spawners");
                        $player->sendMessage("§a§l||§r§f You must hold the exact spawner type to remove this spawner!");
                        return;
                    }
					
                    $message = "";
                    if ($count > $max) {
                        $count = $max;
                        $message = ConfigManager::getMessage("all-spawners-removed");
                        if($message === "") {
                            $message = C::colorize("&aAll available Spawners removed");
                        }
                    }
                    ($event = new SpawnerUnstackEvent($player, $spawner, $count))->call();
                    if($event->isCancelled()) return;

                    $entityName = Utils::getEntityNameFromID($entityId);
                    $spawnerItem = Main::$instance->getSpawner($entityName, $count);
                    $player->getInventory()->addItem($spawnerItem);

                    $spawner->setCount($spawner->getCount() - $count);

                    if($spawner->getCount() <= 0) {
                        $spawner->getLevel()->setBlock($spawner, Block::get(Block::AIR));
                        $spawner->close();
                    } else {
						$player->sendMessage("§c§l(!)§r§c There's no spawner");
					}
                }
            }
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;

        $spawnerName = $spawner->getName();
        $count = $spawner->getCount();
		
        $form->setTitle("§l§eManage Spawners");
        $form->addLabel("§6You've stacked:§a " . $count . "\n§a");
        if($count > 64) $count = 64;
		$form->addSlider("§r§6Amount" . C::GREEN, 1, $count, 1);
        $player->sendForm($form);		
    }
}