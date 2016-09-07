<?php
/** Created By Thunder33345 **/
namespace Thunder33345\AppleRates;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{
	private $chance,$dt;

	public function onLoad()
	{
	}

	public function onEnable()
	{
		if (!file_exists($this->getDataFolder()."config.yml")) {
			@mkdir($this->getDataFolder());
			$this->saveDefaultConfig();
			$this->getLogger()->notice(TextFormat::AQUA . 'Thanks for installing AppleRates by Thunder33345!');
		}
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
		$config = $config->getAll();
		$this->chance = $config['chance'];
		$dt = [];
		foreach ($config['drops'] as $drop) {
			for ($i = 1; $i <= $drop['weight']; $i++) {
				array_push($dt, $drop);
			}
		}
		print_r($dt);
		$this->dt = $dt;
		$this->getLogger()->info(TextFormat::AQUA . 'AppleRates By Thunder33345 Loaded');
	}

	public function onDisable()
	{
	}

	public function OnLeaveBreak(BlockBreakEvent $event)
	{
		if ($event->getBlock()->getId() == Item::LEAVES OR $event->getBlock()->getId() == Item::LEAVES2) {
			if (mt_rand(1, 1000) < $this->chance) {
				$dt = $this->dt;
				shuffle($dt);
				$drop = $dt[mt_rand(0, count($dt))];
				$dt = $event->getDrops();
				array_push($dt, new Item($drop['id'], $drop['meta'], $drop['count']));
				$event->setDrops($dt);
			}
		}
	}
}