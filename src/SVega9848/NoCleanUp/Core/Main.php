<?php

declare(strict_types=1);

namespace SVega9848\NoCleanUp\Core;

use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use SVega9848\NoCleanUp\Task\CombatTask;
use SVega9848\NoCleanUp\Events\Events;
use function array_shift;

class Main extends PluginBase implements Listener {

    private array $cleanlist = [];

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);
        $this->saveResource("config.yml");
	}

    public function isCleanList(string $name) : bool {
        return in_array($name, $this->cleanlist);
    }

    public function addCleanList(string $name) {
        $this->cleanlist[] = $name;
    }

    public function deleteCleanlist(string $name) {
        $search = array_search($name, $this->cleanlist);
        unset($this->cleanlist[$search]);
    }

    public function getCleans() : string {
        return implode(",", $this->cleanlist);
    }

    public function replaceVars(string $str, array $vars) : string {
        foreach($vars as $key => $value){
            $str = str_replace("{" . $key . "}", $value, $str);
        }
        return $str;
    }

    public function getPluginConfig(string $file) {
        $config = new Config($this->getDataFolder(). "/config.yml");
        return $config->get($file);
    }

}
