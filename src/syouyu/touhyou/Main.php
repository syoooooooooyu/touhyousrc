<?php

/**
 *    ＿＿＿＿＿   _     ＿   ＿　　  ＿　＿　
 *   /  ＿＿＿/\ //＿＿ | |  | |\  / /|  | |
 *   \＿＿＿＿\ \// ＿  \ |  | | \/ / |  | |
 *    ＿＿＿  \  / |＿| | |__| |   /| \__/ |
 *   /＿＿＿＿/　/\＿＿＿/\_____/  / \_____/
 *          /＿/            　 /__/
 *
 * @author Syouyu(syoooooooooyu)
 * @link https://github.com/syoooooooooyu/
 */

namespace syouyu\touhyou;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{
        
    private $sansei;

    private $hantai;

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $event){
        $this->touhyou[$event->getPlayer()->getName()] = false;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        switch($command->getName()){
            case "touhyou":
                if(!isset($args[0])){
                    $sender->sendMessage("入力されていません");
                    return true;
                }
                switch($args[0]){
                    case "start":
                        if($sender->isOp()){
                            if(!isset($args[1])){
                                $sender->sendMessage("議題が設定されていません。");
                                return true;
                            }
                            $name = $sender->getName();
                            Server::getInstance()->broadcastMessage("§a".$name."さんが".$args[1]."の議題の投票を開始しました。/touhyou s または /touhyou h をしてください。");
                            $sansei = $this->sansei = 0;
                            $hantai = $this->hantai = 0;
                            $this->b["b"] = true;
                            return true;
                        }
                        $sender->sendMessage("§c権限がありません。");
                    break;
                    case "s":
                    if($this->b["b"] == true){
                        if($this->touhyou[$sender->getName()] == true){
                            return true;
                        }
                        $this->sansei = $this->sansei + 1;
                        $this->touhyou[$sender->getName()] = true;
                    }
                    break;
                    case "h":
                        if($this->b["b"] == true){
                            if($this->touhyou[$sender->getName()] == true){
                                return true;
                            }
                            $this->hantai = $this->hantai + 1;
                            $this->touhyou[$sender->getName()] = true;   
                        }                         
                    break;
                    case "stop":
                        if($sender->isOp()){
                            Server::getInstance()->broadcastMessage("賛成".$this->sansei."票|反対".$this->hantai."票");
                            if($this->sansei > $this->hantai){
                                Server::getInstance()->broadcastMessage("賛成多数");
                            }elseif($this->sansei > $this->hantai){
                                Server::getInstance()->broadcastMessage("反対多数");
                            }
                            $sansei = $this->sansei = 0;
                            $hantai = $this->hantai = 0;
                            $this->b["b"] = false;
                            return true;
                        }
                        $sender->sendMessage("§c権限がありません。");
                    break;
                }
            break;
        }
        return true;
    }
}
