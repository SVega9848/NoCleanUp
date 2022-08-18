<?php

namespace SVega9848\NoCleanUp\Task;

use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use SVega9848\NoCleanUp\Core\Main;

class CombatTask extends Task {

    private $main;
    private int $time = 11;

    public function __construct(Main $main) {
        $this->main = $main;
    }

    public function onRun(): void
    {
        foreach($this->main->getServer()->getOnlinePlayers() as $player) {
            if($this->main->isCleanList($player->getName())) {
                if($this->time >= 1) {
                    $this->time--;
                    $player->sendTip(TextFormat::colorize($this->main->replaceVars($this->main->getPluginConfig("cooldownremaining"), [
                        "PREFIX" => $this->main->getPluginConfig("prefix"),
                        "TIME" => "$this->time"
                    ])));
                    } else {
                    $this->main->deleteCleanlist($player->getName());
                    $this->getHandler()->cancel();
                }
            }
         }
    }
}