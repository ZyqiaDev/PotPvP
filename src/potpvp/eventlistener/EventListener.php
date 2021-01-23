<?php

namespace potpvp\eventlistener;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\network\mcpe\protocol\types\GameMode;
use pocketmine\Player;
use potpvp\Main;

class EventListener implements Listener
{
	private $main;

	function __construct(Main $main)
	{
		$this->main = $main;
	}

	function potkitWhenJoin(PlayerJoinEvent $ev)
	{
		$p = $ev->getPlayer();
		$ev->setJoinMessage(str_replace(["{player}"], [$p->getDisplayName()], $this->main->getConfig()->get("JoinMsg")));
		if ($this->main->getConfig()->get("KitOnJoin") == "True") {
			$this->main->getPotKit()->potKit($p);
		}
		if ($this->main->getConfig()->get("OnJoinAdventureMode") == "True") {
			$p->setGamemode(GameMode::ADVENTURE);
		}
	}

	function potkitWhenRespawn(PlayerRespawnEvent $ev)
	{
		$p = $ev->getPlayer();
		if ($this->main->getConfig()->get("KitOnRespawn") == "True") {
			$this->main->getPotKit()->potKit($p);
		}
		if($this->main->getConfig()->get("SpawnOnJoin") == "True") {
			$p->teleport($this->main->getServer()->getDefaultLevel()->getSafeSpawn());
		}
	}

	function playerQuits(PlayerQuitEvent $ev)
	{
		$p = $ev->getPlayer();
		$ev->setQuitMessage(str_replace(["{player}"], [$p->getDisplayName()], $this->main->getConfig()->get("QuitMsg")));
	}

	function noFallAntiVoid(EntityDamageEvent $ev)
	{
		$p = $ev->getEntity();
		if ($this->main->getConfig()->get("NoFall") == "True") {
			if ($ev->getCause() === EntityDamageEvent::CAUSE_FALL)
				$ev->setCancelled();
		}
		if ($this->main->getConfig()->get("AntiVoid") == "True") {
			if ($ev->getCause() === EntityDamageEvent::CAUSE_VOID) {
				$p->teleport($this->main->getServer()->getDefaultLevel()->getSpawnLocation());
				$ev->setCancelled();
			}
		}
	}

	function noHunger(PlayerExhaustEvent $ev)
	{
		if ($this->main->getConfig()->get("NoHunger") == "True") {
			$ev->setCancelled(true);
		}
	}

	function killHealthPot(PlayerDeathEvent $ev): void
	{
		$killer = $ev->getPlayer()->getLastDamageCause();
		if ($killer instanceof EntityDamageByEntityEvent) {
			$killer = $killer->getDamager();
			if ($killer instanceof Player) {
				$ev->setDeathMessage(str_replace(["{victim}", "{killer}"], [$ev->getPlayer()->getName(), $killer->getName()], $this->main->getConfig()->get("KillMsg")));
				if ($this->main->getConfig()->get("KillHealth") == "True") {
					$killer->setHealth($killer->getHealth() + $this->main->getConfig()->get("HealthGainedPerKill"));
				}
				if ($this->main->getConfig()->get("KillPot") == "True") {
					$killer->getInventory()->addItem(Item::get(Item::SPLASH_POTION, Potion::STRONG_HEALING, $this->main->getConfig()->get("PotGainedPerKill")));
				}
			}
		}
		if($this->main->getConfig()->get("AllowDeathDrops") == "False") {
			$ev->setDrops([]);
		}
	}

	function throwingItems(PlayerDropItemEvent $ev)
	{
		if ($this->main->getConfig()->get("AllowDroppingItems") == "True") {
			$ev->setCancelled(false);
		}
	}
}