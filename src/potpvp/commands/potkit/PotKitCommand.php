<?php

namespace potpvp\commands\potkit;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use potpvp\Main;

class PotKitCommand extends Command
{
	private $main;

	function __construct(Main $main)
	{
		parent::__construct("kit", "Gives you the a potkit #PotPvP", null, ["potkit"]);
		$this->main = $main;
	}

	function execute(CommandSender $s, string $commandLabel, array $args): bool
	{
		if($s instanceof Player){
			$this->main->getPotKit()->potKit($s);
			$s->sendMessage("§l§bPotPvP §r§o§8» §gKit Claimed");
		}else{
			$s->sendMessage("§l§bPotPvP §r§o§8» §cPlease use this command in game");
		}
		return true;
	}
}