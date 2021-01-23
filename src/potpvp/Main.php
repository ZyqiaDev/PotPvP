<?php

namespace potpvp;

use libs\muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use potpvp\commands\essentials\BroadCastCommand;
use potpvp\commands\essentials\PingCommand;
use potpvp\commands\essentials\SpawnCommand;
use potpvp\commands\kdr\ResetPlayersStatsCommand;
use potpvp\commands\kdr\StatsCommand;
use potpvp\commands\potkit\PotKitCommand;
use potpvp\commands\potkit\PotRefillCommand;
use potpvp\eventlistener\EventListener;
use potpvp\kdr\KDR;
use potpvp\kit\PotKit;

class Main extends PluginBase
{
	public $potkit;
	public $kdr;
	function onEnable()
	{
		$this->getLogger()->info("§o§bPotPvP §8» §2Enabled");
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->registerEvent();
		$this->registerCommands();
		$this->PlayerDataBaseYML();
		$this->kdr = new KDR($this);
		$this->potkit = new PotKit($this);
		if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}
	}

	function PlayerDataBaseYML()
	{
		if (!is_dir($this->getDataFolder() . "playerdatabase/")) {
			mkdir($this->getDataFolder() . "playerdatabase/");
		}
	}

	function registerEvent(){
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new KDR($this), $this);
	}

	function registerCommands()
	{
		$this->getServer()->getCommandMap()->register("kit", new PotKitCommand($this));
		$this->getServer()->getCommandMap()->register("potrefill", new PotRefillCommand($this));
		$this->getServer()->getCommandMap()->register("stats", new StatsCommand($this));
		$this->getServer()->getCommandMap()->register("spawn", new SpawnCommand($this));
		$this->getServer()->getCommandMap()->register("ping", new PingCommand($this));
		$this->getServer()->getCommandMap()->register("broadcast", new BroadCastCommand($this));
		$this->getServer()->getCommandMap()->register("resetstats", new ResetPlayersStatsCommand($this));
	}

	public function getPotKit(): PotKit
	{
		return $this->potkit;
	}

	public function getKDR(): KDR
	{
		return $this->kdr;
	}

	function onDisable()
	{
		$this->reloadConfig();
		$this->getLogger()->info("§o§bPotPvP §8» §cDisabled");
	}
}