<?php

namespace potpvp\commands\potkit;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\Player;
use potpvp\Main;

class PotRefillCommand extends Command
{
	private $main;

	function __construct(Main $main)
	{
		parent::__construct("potrefill", "Gives you more potions #PotPvP", null);
		$this->setPermission("potrefill.command");
		$this->main = $main;
	}

	function execute(CommandSender $s, string $commandLabel, array $args): bool
	{
		if ($s->hasPermission("potrefill.command")) {
			if ($s instanceof Player) {
				$potion = Item::get(Item::POTION, Potion::STRONG_HEALING, 35);
				$potion->setCustomName($this->main->getConfig()->get("PotName"));
				$potion->setLore([str_replace(["{line}"], ["\n"], $this->main->getConfig()->get("PotLore"))]);
				$s->getInventory()->addItem($potion);
				$s->sendMessage("§l§bPotPvP §r§o§8» §gYou have been given more potions");
			} else {
				$s->sendMessage("§l§bPotPvP §r§o§8» §cPlease use this command in game");
			}
		}else {
			$s->sendMessage("§b§lPotPvP §r§o§8» §gYou don't have permission to use this command");
		}
		return true;
	}
}