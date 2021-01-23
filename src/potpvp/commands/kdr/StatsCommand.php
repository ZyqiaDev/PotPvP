<?php

namespace potpvp\commands\kdr;

use libs\muqsit\invmenu\InvMenu;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\Config;
use potpvp\Main;

class StatsCommand extends Command
{
	private $main;

	function __construct(Main $main)
	{
		parent::__construct("stats", "Shows your or another players stats #PotPvP");
		$this->main = $main;
	}

	public function execute(CommandSender $s, string $commandLabel, array $args): bool
	{
		if ($s instanceof Player) {
			$p = $s;
			if (count($args) <= 0) {
				$this->yourStatsMenu($p);
			}
			if (count($args) >= 1) {
				$p1 = $this->main->getServer()->getPlayer($args[0]);
				$playerdatabase = new Config($this->main->getDataFolder() . "playerdatabase/" . $p1->getLowerCaseName() . ".yml", Config::YAML);
				if ($this->main->getServer()->getPlayer($args[0]) !== null) {
					if ((int)$playerdatabase->get("kills") >= 0) {
						$menu = InvMenu::create(InvMenu::TYPE_CHEST);
						$menu->setName("§g§o" . $p1->getName() . "'s §l§bStats");
						$menu->readonly();
						$kills = (int)$playerdatabase->get("kills");
						$deaths = (int)$playerdatabase->get("deaths");
						$kdr = $this->main->getKDR()->getKDR($p1);
						$ks = (int)$playerdatabase->get("killstreak");
						$hks = (int)$playerdatabase->get("highestkillstreak");
						$menu->getInventory()->setItem(0, Item::get(35, 5)->setCustomName("§g" . $p1->getName() . "'s §l§o§bKills§r§g: §o§c$kills §r§g✓"));
						$menu->getInventory()->setItem(2, Item::get(35, 14)->setCustomName("§g" . $p1->getName() . "'s §l§o§bDeaths§r§g: §o§c$deaths §r§g✓"));
						$menu->getInventory()->setItem(4, Item::get(35, 1)->setCustomName("§g" . $p1->getName() . "'s §l§o§bKillStreak§r§g: §o§c$ks §r§g✓"));
						$menu->getInventory()->setItem(6, Item::get(35, 7)->setCustomName("§g" . $p1->getName() . "'s §l§o§bHighest KillStreak§r§g: §o§c$hks §r§g✓"));
						$menu->getInventory()->setItem(8, Item::get(35, 4)->setCustomName("§g" . $p1->getName() . "'s §l§o§bKDR§r§g: §o§c$kdr §r§g✓"));
						$menu->getInventory()->setItem(22, Item::get(35, 6)->setCustomName("§g" . $p1->getName() . "'s §l§o§bPING§r§g: §r§o§a" . $p1->getPing() . "ms §g✓"));
						$menu->send($p);
					}
				} else {
					$s->sendMessage("§b§lPotPvP §r§o§8» §cPlayer not online");
				}
			}
		}
		return true;
	}

	function yourStatsMenu(Player $p)
	{
		$playerdatabase = new Config($this->main->getDataFolder() . "playerdatabase/" . $p->getLowerCaseName() . ".yml", Config::YAML);
		if ((int)$playerdatabase->get("kills") >= 0) {
			$menu = InvMenu::create(InvMenu::TYPE_CHEST);
			$menu->setName("§l§o§bStats");
			$menu->readonly();
			$kills = (int)$playerdatabase->get("kills");
			$deaths = (int)$playerdatabase->get("deaths");
			$kdr = $this->main->getKDR()->getKDR($p);
			$ks = (int)$playerdatabase->get("killstreak");
			$hks = (int)$playerdatabase->get("highestkillstreak");
			$menu->getInventory()->setItem(0, Item::get(35, 5)->setCustomName("§l§o§bKills§r§g: §o§c$kills §r§g✓"));
			$menu->getInventory()->setItem(2, Item::get(35, 14)->setCustomName("§l§o§bDeaths§r§g: §o§c$deaths §r§g✓"));
			$menu->getInventory()->setItem(4, Item::get(35, 1)->setCustomName("§l§o§bKillStreak§r§g: §o§c$ks §r§g✓"));
			$menu->getInventory()->setItem(6, Item::get(35, 7)->setCustomName("§l§o§bHighest KillStreak§r§g: §o§c$hks §r§g✓"));
			$menu->getInventory()->setItem(8, Item::get(35, 4)->setCustomName("§l§o§bKDR§r§g: §o§c$kdr §r§g✓"));
			$menu->getInventory()->setItem(22, Item::get(35, 6)->setCustomName("§l§o§bPING§r§g: §r§o§a" . $p->getPing() . "ms §g✓"));
			$menu->send($p);
		}
	}
}