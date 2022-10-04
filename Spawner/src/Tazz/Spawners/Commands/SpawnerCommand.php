<?php

namespace Tazz\Spawners\Commands;

use Heisenburger69\Spawners\Main;
use Heisenburger69\Spawners\Utilities\ConfigManager;
use Heisenburger69\Spawners\Utilities\Utils;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
use ReflectionException;

class SpawnerCommand extends PluginCommand
{

    /** @var Main */
    private $plugin;

    /**
     * SpawnerCommand constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        parent::__construct("nh", $plugin);
        $this->setUsage("/nh <string:spawner> [int:count] [string:player]");
        $this->setPermission("core.admin.spawner");
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool|mixed
     * @throws ReflectionException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("core.admin.spawner")) {
			//none
            return false;
        }
        if (empty($args)) {
            $sender->sendMessage("§6Usage: §a/nh <spawner/list> <count> <player>");
            return false;
        }
        $entities = Utils::getEntityArrayList();

        if(isset($args[0]) && $args[0] === "list") {
            $list = implode(", ", $entities);
            $sender->sendMessage(C::GOLD . "List of Available Spawners:\n".C::YELLOW.$list);
            return true;
        }

        $entities = $this->plugin->getRegisteredEntities();
        $entityName = strtolower($args[0]);
        if ($entities === null) {
            $sender->sendMessage(C::RED . "No registered entities!");
            return false;
        }

        $entities = array_change_key_case($entities, CASE_LOWER);
        if (!array_key_exists($entityName, $entities)) {
            $sender->sendMessage(C::RED . "Given entity " . C::DARK_AQUA . $entityName . C::RED . " not registered!");
            return false;
        }

        $count = 1;
        if (isset($args[1]) && (int)$args[1] >= 1) {
            $count = (int)$args[1];
        }

        $player = $sender;
        if (isset($args[2])) {
            $player = $this->plugin->getServer()->getPlayer($args[2]);
            if ($player === null) {
                $sender->sendMessage(C::RED . "Player " . C::DARK_AQUA . $args[2] . C::RED . " not found!");
                return false;
            }
        }

        $spawner = Main::$instance->getSpawner($entityName, $count);
        $spawnerName = $spawner->getCustomName();

        if ($player instanceof Player) {
            $message = ConfigManager::getMessage("player-given-spawner");
            $message = str_replace("{player}", $player->getName(), $message);
            $message = str_replace("{spawner}", $spawnerName, $message);

            $player->sendMessage("§a" . $message);
            $player->getInventory()->addItem($spawner);
            return true;
        } else {
            $sender->sendMessage(C::RED . "Player not found!");
        }

        return false;
    }
}