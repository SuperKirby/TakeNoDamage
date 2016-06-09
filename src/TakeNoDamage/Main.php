<?php

namespace TakeNoDamage;

use pocketmine\plugin\PluginBase;
use pocketmine\event\entity\EntityDamageEvent;
use Pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
public function onEnable() {
    $this->getLogger()->info("TakeNoDamage enabled!");
    }
    
public function onDisable() {
    $this->getlogger()->info("Take no damage enabled!");       
    }
    
public function onDamage(EntityDamageEvent $event) {
  if($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
    $event->setCancelled();
    }
   if($event->getCause() === EntityDamageEvent::CAUSE_LAVA) {
    $event->setCancelled();
    }
      if($event->getCause() === EntityDamageEvent::CAUSE_CONTACT) {
    $event->setCancelled();
    }
}
