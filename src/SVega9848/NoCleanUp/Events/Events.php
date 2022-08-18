<?php

namespace SVega9848\NoCleanUp\Events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use SVega9848\NoCleanUp\Core\Main;
use SVega9848\NoCleanUp\Task\CombatTask;

class Events implements Listener {

    private $main;

    public function __construct(Main $main) {
        $this->main = $main;
    }

    public function onDeath(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            if($entity instanceof Player && $damager instanceof Player) {
                if($this->main->isCleanList($damager->getName()) == true && $this->main->isCleanList($entity->getName()) == false) {
                    $event->cancel();
                }
                if($this->main->isCleanList($damager->getName()) == false && $this->main->isCleanList($entity->getName()) == true) {
                    $event->cancel();
                    $damager->sendTip(TextFormat::colorize($this->main->replaceVars($this->main->getPluginConfig("alreadynoclean"), [
                        "PREFIX" => $this->main->getPluginConfig("prefix"),
                        "PLAYER" => $entity->getName()
                    ])));
                }
                    if($event->getFinalDamage() >= $entity->getHealth() && !$this->main->isCleanList($damager->getName()) == true) {
                        $this->main->addCleanList($damager->getName());
                        $this->main->getScheduler()->scheduleRepeatingTask(new CombatTask($this->main), 20);

                }
            }
        }
    }
}