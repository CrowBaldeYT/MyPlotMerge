<?php
declare(strict_types=1);
namespace MyPlot\subcommand;

use MyPlot\MyPlot;
use MyPlot\RandChest;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class WandCommand extends SubCommand
{

	protected $pl;
	/**
	 * @param CommandSender $sender
	 *
	 * @return bool
	 */

	/**
	 * @param CommandSender $sender
	 *
	 * @return bool
	 */
	public function canUse(CommandSender $sender): bool
	{
		return ($sender instanceof Player) and $sender->hasPermission("myplot.command.wand");
	}
	/**
	 * @param Player $sender
	 * @param string[] $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, array $args) : bool {
		$prefix = TF::GOLD . "Plot" . TF::DARK_GRAY . TF::BOLD . " » " . TF::RESET . TF::GRAY;
		$plot = MyPlot::getInstance()->getPlotByPosition($sender);
		if($plot === null) {
			$sender->sendMessage($prefix . TF::GRAY . "Du befindest dich nicht auf einem Grundstück.");
			return true;
		}
		if($plot->owner !== $sender->getName() and !$sender->hasPermission("myplot.admin.wand")) {
			$sender->sendMessage($prefix . TF::GRAY . "Dieses Grundstück gehört dir nicht.");
			return true;
		}
		$merge = new Config($this->getPlugin()->getDataFolder() . "merge_" . $sender->getLevel()->getName() . ".yml", 2);
		if ($merge->exists("$plot")) {
			$sender->sendMessage($prefix . TF::GRAY . "§cDu kannst nicht die Wand von einem Merge verändern!");
			return true;
		}

		$cos = new WandChest($this->getPlugin(), $sender);
		$cos->Wand($sender);

		return true;

	}
}
