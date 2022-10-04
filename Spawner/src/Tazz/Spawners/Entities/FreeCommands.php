<?php

namespace Tazz\Commands;

use Tazz\Main;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

class FreeCommands extends PluginCommand{

    public $plugin;
	
	public function __construct($name, Main $plugin) {
        parent::__construct($name, $plugin);
        $this->setDescription("Get yourself a free elder guardian spawner");
        $this->setUsage("/free");
		$this->console = new ConsoleCommandSender();
		$this->plugin = $plugin;
    }

	/**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
		if ($sender instanceof ConsoleCommandSender) {
			$sender->sendMessage(TextFormat::RED . "This command can be only used in-game.");
			return false;
		}
		$kits = new Config($this->plugin->getDataFolder(). "cooldown/free.yml", Config::YAML);
		if ($kits->exists($sender->getLowerCaseName(), true)) {
			$sender->sendMessage("§cYou can only do this once");
		} else {
			$kits->set($sender->getLowerCaseName(), 5);
            $kits->save();				
			$this->plugin->getServer()->dispatchCommand($this->console, 'nh elderguardian 1 ' . $sender->getName());	
		}
		return true;
	}
}