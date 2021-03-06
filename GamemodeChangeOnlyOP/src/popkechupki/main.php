<?php

namespace popkechupki;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\{TextFormat, Config};
use pocketmine\event\{Listener, Cancellable};
use pocketmine\event\player\PlayerGameModeChangeEvent;

class main extends PluginBase implements Listener{

	public function onEnable(){
        $this->getLogger()->info(TextFormat::GREEN."GamemodeChangeOnlyOPを読み込みました".TextFormat::GOLD." By popkechupki");
        $this->getLogger()->info(TextFormat::RED."このプラグインはpopke LICENSEに同意した上で使用してください。");

        if (!file_exists($this->getDataFolder())) @mkdir($this->getDataFolder(), 0740, true);
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML,
        array(
            'Kick' => 'true'
            ));

        $this->config->save();    
        $this->getServer()->getPluginManager()->registerEvents($this,$this); 
    } 

	function GamemodeChange(PlayerGameModeChangeEvent $event){
		$player = $event->getPlayer();
		$Name = $player->getName();
		$date = $this->config->get("Kick");
		if ($date == "true") {
			if($player->isOP()){
				$player->sendMessage("[§b認証§r] あなたがOPであることが確認されました。");
			}else{
				$this->getServer()->broadcastMessage("[§c警告§r] §b".$Name."§aがゲームモードを変更しようとしたのでKickしました。");
				$event->setCancelled(true);
				$player->kick("OPでないのにゲームモードを変更しようとしたためkickしました。", false);
			}
		}else if ($date == "false") {
			if($player->isOP()){
				$player->sendMessage("[§b認証§r] あなたがOPであることが確認されました。");
			}else{
				$player->sendMessage("[§c警告§r] §aあなたはOPではないのでゲームモードの変更ができません。");
				$this->getServer()->broadcastMessage("[§c警告§r] §b".$Name."§aがゲームモードを変更しようとしました。");
				$event->setCancelled(true);
			}
		}
	}
}
