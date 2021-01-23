<?php

namespace potpvp\commands\essentials;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use potpvp\Main;

class SpawnCommand extends Command
{
	private $main;

	function __construct(Main $main)
	{
		parent::__construct("spawn", "Teleports you to the servers spawn #PotPvP");
		$this->main = $main;
	}

	public function execute(CommandSender $s, string $commandLabel, array $args): bool
	{
		if ($s instanceof Player) {
			if (count($args) <= 0) {
				$s->teleport($this->main->getServer()->getDefaultLevel()->getSafeSpawn());
			}
			if (count($args) >= 1) {
				if ($s->hasPermission("spawnother.command")) {
					$p = $this->main->getServer()->getPlayer($args[0]);
					if ($this->main->getServer()->getPlayer($args[0]) !== null) {
						$p->teleport($this->main->getServer()->getDefaultLevel()->getSafeSpawn());
						$p->sendMessage("§b§lPotPvP §r§o§8» §b" . $s->getDisplayName() . " §ghas teleported you to spawn");
						$p->sendMessage("§b§lPotPvP §r§o§8» §b" . $s->getDisplayName() . " §ghas been teleported to spawn");
					} else {
						$s->sendMessage("§b§lPotPvP §r§o§8» §cPlayer not online");
					}
				} else {
					$s->teleport($this->main->getServer()->getDefaultLevel()->getSafeSpawn());
				}
			}
		}
		return true;
	}
}