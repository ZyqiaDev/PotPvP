<?php

namespace potpvp\kit;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\Player;
use potpvp\Main;

class PotKit
{
	private $main;

	function __construct(Main $main)
	{
		$this->main = $main;
	}

	public function potKit(Player $p): void
	{
		$p->getInventory()->clearAll();
		$p->getArmorInventory()->clearAll();
		$a = new EnchantmentInstance(Enchantment::getEnchantment($this->main->getConfig()->get("Enchantment1")), $this->main->getConfig()->get("Level1"));
		$b = new EnchantmentInstance(Enchantment::getEnchantment($this->main->getConfig()->get("Enchantment2")), $this->main->getConfig()->get("Level2"));

		$helmet = Item::get($this->main->getConfig()->get("Helmet"));
		$helmet->setCustomName($this->main->getConfig()->get("HelmetName"));
		$chestplate = Item::get($this->main->getConfig()->get("Chestplate"));
		$chestplate->setCustomName($this->main->getConfig()->get("ChestplateName"));
		$leggings = Item::get($this->main->getConfig()->get("Leggings"));
		$leggings->setCustomName($this->main->getConfig()->get("LeggingsName"));
		$boots = Item::get($this->main->getConfig()->get("Boots"));
		$boots->setCustomName($this->main->getConfig()->get("BootsName"));

		$weapon = Item::get($this->main->getConfig()->get("Weapon"));
		$weapon->setCustomName($this->main->getConfig()->get("WeaponName"));
		$soup = Item::get(Item::SPLASH_POTION, Potion::STRONG_HEALING, 35);
		$soup->setCustomName($this->main->getConfig()->get("PotName"));
		$soup->setLore([str_replace(["{line}"], ["\n"], $this->main->getConfig()->get("PotLore"))]);

		$helmet->addEnchantment($a);
		$chestplate->addEnchantment($a);
		$leggings->addEnchantment($a);
		$boots->addEnchantment($a);
		$helmet->addEnchantment($b);
		$chestplate->addEnchantment($b);
		$leggings->addEnchantment($b);
		$boots->addEnchantment($b);
		$p->getArmorInventory()->setHelmet($helmet);
		$p->getArmorInventory()->setChestplate($chestplate);
		$p->getArmorInventory()->setLeggings($leggings);
		$p->getArmorInventory()->setBoots($boots);

		if ($this->main->getConfig()->get("AllowWeaponToGetEnchantTwo") == "True") {
			$weapon->addEnchantment($b);
		}
		$p->getInventory()->addItem($weapon);
		$p->getInventory()->addItem($soup);
		$p->addEffect(new EffectInstance(Effect::getEffect($this->main->getConfig()->get("Effect1")), $this->main->getConfig()->get("Duration1"), $this->main->getConfig()->get("Amplifier1"), true));
		$p->addEffect(new EffectInstance(Effect::getEffect($this->main->getConfig()->get("Effect2")), $this->main->getConfig()->get("Duration2"), $this->main->getConfig()->get("Amplifier2"), true));
	}
}