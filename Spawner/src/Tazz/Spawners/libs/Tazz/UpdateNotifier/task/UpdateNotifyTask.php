<?php
declare(strict_types = 1);

#Author: Tazz

namespace Tazz\Spawners\libs\JackMD\UpdateNotifier\task;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;

class UpdateNotifyTask extends AsyncTask{
	
	/** @var string */
	private const POGGIT_RELEASES_URL =
	
	/** @var string */
	private $pluginName;
	/** @var string */
	private $pluginVersion;
	
	/**
	 * UpdateNotifyTask constructor.
	 *
	 * @param string $pluginName
	 * @param string $pluginVersion
	 */
	public function __construct(string $pluginName, string $pluginVersion){
		$this->pluginName = $pluginName;
		$this->pluginVersion = $pluginVersion;
	}
	
	public function onRun(): void{
		$json = Internet::getURL(self::POGGIT_RELEASES_URL . $this->pluginName, 10, [], $err);
		$highestVersion = $this->pluginVersion;
		$artifactUrl = "";
		$api = "";
		if($json !== false){
			$releases = json_decode($json, true);
			foreach($releases as $release){
				if(version_compare($highestVersion, $release["version"], ">=")){
					continue;
				}
				$highestVersion = $release["version"];
				$artifactUrl = $release["artifact_url"];
				$api = $release["api"][0]["from"] . " - " . $release["api"][0]["to"];
			}
		}
		
		$this->setResult([$highestVersion, $artifactUrl, $api, $err]);
	}
	
	/**
	 * @param Server $server
	 */
	public function onCompletion(Server $server): void{
		$pluginName = $this->pluginName;
		$plugin = $server->getPluginManager()->getPlugin($pluginName);
		if($plugin === null){
			return;
		}
		[$highestVersion, $artifactUrl, $api, $err] = $this->getResult();
		if($err !== null){
			$plugin->getLogger()->error("Update notify error: " . $err);
		}
		if($highestVersion === $this->pluginVersion){
			$plugin->getLogger()->info("No new updates were found. You are using the latest version.");
			return;
		}
		$artifactUrl = $artifactUrl . "/" . $pluginName . "_" . $highestVersion . ".phar";
		$plugin->getLogger()->notice(vsprintf("Version %s has been released for API %s. Download the new release at %s", [$highestVersion, $api, $artifactUrl]));
	}
}