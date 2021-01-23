<?php

declare(strict_types = 1);

namespace potpvp\commands\essentials;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use potpvp\Main;

class BroadCastCommand extends Command
{
	private $main;

	public function __construct(Main $main)
	{
		parent::__construct("broadcast", "Broadcast a message #PotPvP", null, ["bc"]);
		$this->setPermission("broadcast.command");
		$this->main = $main;
	}

	public function execute(CommandSender $s, string $commandLabel, array $args): bool
	{
		if ($s->hasPermission("broadcast.command")) {
			$message = implode(" ", $args);
			$this->main->getServer()->broadcastMessage(str_replace(["&"], [TextFormat::ESCAPE], "§c§lBroadcaster §r§o§8» §r" . $message));
		} else {
			$s->sendMessage("§b§lPotPvP §r§o§8» §gYou don't have permission to use this command");
		}
		return true;
	}
}