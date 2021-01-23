<?php

namespace potpvp\commands\essentials;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use potpvp\Main;

class PingCommand extends Command
{
	private $main;

	function __construct(Main $main)
	{
		parent::__construct("ping", "Look at you or another players ping #PotPvP");
		$this->main = $main;
	}

	public function execute(CommandSender $s, string $commandLabel, array $args): bool
	{
		if ($s instanceof Player) {
			if (count($args) <= 0) {
				$ping = $s->getPing();
				$s->sendMessage("§b§lPotPvP §r§o§8» §gYour ping is: §b$ping" . "ms");
			}
			if (count($args) >= 1) {
					$p = $this->main->getServer()->getPlayer($args[0]);
					if ($this->main->getServer()->getPlayer($args[0]) !== null) {
						$ping = $p->getPing();
						$p->sendMessage("§b§lPotPvP §r§o§8» §gYour ping is: §b$ping" . "ms");
					} else {
						$s->sendMessage("§b§lPotPvP §r§o§8» §cPlayer not online");
					}
			}
		}
		return true;
	}
}