<?php

namespace TakeNoDamage;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Main extends PluginBase implements Listener {
  public function onEnable() {
    $this->enabled = array();
    $this->getServer()->info(TF::GREEN."TakeNoDamage 2.0");
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
  }
  
  public function onCommand(CommandSender $issuer,Command $cmd,$label,array $args) {
    if((strtolower($cmd->getName()) == "tnd") && !(isset($args[0])) && ($issuer instanceof Player) && ($issuer->hasPermission("tnd".toggle") || $issuer->hasPermission("tnd.toggle.self"))) {
      $this->enabled[$issuer->getName()] = !$this->enabled[$issuer->getName()];
      if($this->enabled[$issuer->getName()]) {
        $issuer->sendMessage("You are invincable!");
      } else {
        $issuer->sendMessage("You can take damage!");
      }
      return true;
    } else if((strtolower($cmd->getName()) == "tnd") && isset($args[0]) && ($issuer->hasPermission("tnd.toggle") || $issuer->hasPermission("tnd.toggle.others"))) {
      if($this->getServer()->getPlayer($args[0]) instanceof Player) {
        if(isset($this->enabled[$this->getServer()->getPlayer($args[0])->getName()])) {
          $this->enabled[$this->getServer()->getPlayer($args[0])->getName()] = !$this->enabled[$this->getServer()->getPlayer($args[0])->getName()];
          if($this->enabled[$this->getServer()->getPlayer($args[0])->getName()]) {
            $this->getServer()->getPlayer($args[0])->sendMessage("You can no longer take damage");
            $issuer->sendMessage("No damage for " . $this->getServer()->getPlayer($args[0])->getName() . "!");
          } else {
            $this->getServer()->getPlayer($args[0])->sendMessage("You are now able to take damage!");
            $issuer->sendMessage("Damage has been allowed for " . $this->getServer()->getPlayer($args[0])->getName() . "!");
          }
        } else {
          $this->enabled[$this->getServer()->getPlayer($args[0])->getName()] = true;
          $this->getServer()->getPlayer($args[0])->sendMessage("You can no longer take damage!");
          $issuer->sendMessage("No damage for " . $this->getServer()->getPlayer($args[0])->getName() . "!");
        }
      } else {
        $issuer->sendMessage("This player cannot take damage");
      }
      return true;
    } else {
      return false;
    }
  }

  public function onHurt(EntityDamageEvent $event) {
    $entity = $event->getEntity();
    if($entity instanceof Player && isset($this->enabled[$entity->getName()])) {
      if($this->enabled[$entity->getName()]) {
        $event->setCancelled();
      }
    }
  }
}
