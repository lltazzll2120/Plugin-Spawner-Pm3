<?php
declare(strict_types = 1);

#Author: Tazz

namespace Tazz\Spawners\libs\JackMD\UpdateNotifier;

use Tazz\Spawners\libs\JackMD\UpdateNotifier\task\UpdateNotifyTask;
use pocketmine\plugin\Plugin;

class UpdateNotifier{
	
	/**
	 * Soumet une tâche asynchrone qui vérifie ensuite si une nouvelle version du plugin est disponible.
	 * Si une mise à jour est disponible, elle imprimera un message sur la console.
	 *
	 * @param Plugin $plugin
	 * @param string $pluginName
	 * @param string $pluginVersion
	 */
	public static function checkUpdate(Plugin $plugin, string $pluginName, string $pluginVersion){
		$plugin->getLogger()->info("Checking for updates...");
		$plugin->getServer()->getAsyncPool()->submitTask(new UpdateNotifyTask($pluginName, $pluginVersion));
	}
}