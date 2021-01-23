<?php

namespace potpvp\commands\kdr;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use potpvp\Main;

class ResetPlayersStatsCommand extends Command
{
	private $main;

	function __construct(Main $main)
	{
		parent::__construct("resetstats", "Resets a players stats #PotPvP", null, ["resetplayersstats"]);
		$this->setPermission("resetstats.command");
		$this->main = $main;
	}

	public function execute(CommandSender $s, string $commandLabel, array $args): bool
	{
		if ($s->hasPermission("resetstats.command")) {
			if ($s instanceof Player) {
				if (count($args) <= 0) {
					$s->sendMessage("§b§lPotPvP Usage§r§o§8» §c/resetstats (player)");
				}
				if (count($args) >= 1) {
					$p = $this->main->getServer()->getPlayer($args[0]);
					if ($this->main->getServer()->getPlayer($args[0]) !== null) {
						$playerdatabase = new Config($this->main->getDataFolder() . "playerdatabase/" . $p->getLowerCaseName() . ".yml", Config::YAML);
						$playerdatabase->setAll(["kills" => 0, "deaths" => 0, "highestkillstreak" => 0, "killstreak" => 0]);
						$s->sendMessage("§b§lPotPvP §r§o§8» §b" . $s->getDisplayName() . " §gstats have been reset!");
						$p->sendMessage("§b§lPotPvP §r§o§8» §gYour stats have been reset!");
						$playerdatabase->save();
					} else {
						$s->sendMessage("§b§lPotPvP §r§o§8» §cPlayer not online");
					}
				}
			}
		}
		return true;
	}
}