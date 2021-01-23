<?php

declare(strict_types = 1);

namespace potpvp\kdr;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
use potpvp\Main;

class KDR implements Listener
{
	private $main;

	function __construct(Main $main)
	{
		$this->main = $main;
	}

	function playerExists(PlayerJoinEvent $ev): bool
	{
		$p = $ev->getPlayer();
		@mkdir($this->main->getDataFolder());
		$playerdatabase = new Config($this->main->getDataFolder() . "playerdatabase/" . $p->getLowerCaseName() . ".yml", Config::YAML);
		if ((!$playerdatabase->exists("kills")) && (!$playerdatabase->exists("deaths")) && (!$playerdatabase->exists("highestkillstreak")) && (!$playerdatabase->exists("killstreak"))) {
			$playerdatabase->setAll(["kills" => 0, "deaths" => 0, "highestkillstreak" => 0, "killstreak" => 0]);
			$playerdatabase->save();
		}
		return true;
	}

	function getKDR(Player $p): string
	{
		$playerdatabase = new Config($this->main->getDataFolder() . "playerdatabase/" . $p->getLowerCaseName() . ".yml", Config::YAML);
		$kills = (int)$playerdatabase->get("kills");
		$deaths = (int)$playerdatabase->get("deaths");
		if ($deaths !== 0) {
			$ratio = $kills / $deaths;
			if ($ratio !== 0) {
				return number_format($ratio, 1);
			}
		}
		return "0.0";
	}

	function getKillStreakMessages(Player $p)
	{
		$playerdatabase = new Config($this->main->getDataFolder() . "playerdatabase/" . $p->getLowerCaseName() . ".yml", Config::YAML);
		$pks = (int)$playerdatabase->get("killstreak");
		$ks = $this->main->getConfig()->get("KillStreaks");
		if(in_array($pks, $ks)){
			$pks = (int)$playerdatabase->get("killstreak") + 1;
			$this->main->getServer()->broadcastMessage(str_replace(["{player}", "{killstreak}"], [$p->getDisplayName(), $pks], $this->main->getConfig()->get("KillStreakMessage")));
		}
	}

	function KillDeathKillStreakEvent(PlayerDeathEvent $ev)
	{
		$p = $ev->getPlayer();
		$playerdatabase = new Config($this->main->getDataFolder() . "playerdatabase/" . $p->getLowerCaseName() . ".yml", Config::YAML);
		if ($p instanceof Player) {
			if ((int)$playerdatabase->get("killstreak") >= 1) {
				$p->sendMessage("§b§lPotPvP §o§r§8» §cYou have lost your §b" . (int)$playerdatabase->get("killstreak") . " §ckill streak");
				$playerdatabase->set("killstreak", (int)0);
			}
			$playerdatabase->set("deaths", (int)$playerdatabase->get("deaths") + (int)1);
			$playerdatabase->save();
		}
		$cause = $p->getLastDamageCause();
		if ($cause instanceof EntityDamageByEntityEvent) {
			$damager = $cause->getDamager();
			$playerdatabase = new Config($this->main->getDataFolder() . "playerdatabase/" . $damager->getLowerCaseName() . ".yml", Config::YAML);
			if ($damager instanceof Player) {
				if((int)$playerdatabase->get("killstreak") >= (int)$playerdatabase->get("highestkillstreak")) {
					$playerdatabase->set("highestkillstreak", (int)$playerdatabase->get("highestkillstreak") + (int)1);
				}
				$this->getKillStreakMessages($damager);
				$playerdatabase->set("killstreak", (int)$playerdatabase->get("killstreak") + (int)1);
				$playerdatabase->set("kills", (int)$playerdatabase->get("kills") + (int)1);
				$playerdatabase->save();
			}
		}
	}
}