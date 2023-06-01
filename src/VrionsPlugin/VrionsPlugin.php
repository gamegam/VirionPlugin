<?php

namespace VrionsPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class VrionsPlugin extends PluginBase implements Listener{

    public function onEnable():void{
        $this->file();
        $this->getServer()->getCommandMap()->registerAll("cmd", 
        [new \VrionsPlugin\cmd\VCommand()]);
    }

    public function getFiles($type, string $folder, int $int){
        $dataPath = Server::getInstance()->getDataPath();
        $myPluginPath = $dataPath . "virions/";
        if (!file_exists($myPluginPath)) {
            return false;
        }
        $folders = scandir($myPluginPath);
        $list = [];  
        if ($folder !== "." && $folder !== ".." && is_dir($myPluginPath . $folder)) {  
            $srcPath = $myPluginPath . $folder . "/src";  
            $plugin = $myPluginPath . $folder . "/virion.yml";    
            if (file_exists($plugin)) {
                $content = file_get_contents($plugin);
                $pos = strpos($content, $type);
                if ($pos !== false) {
                    $name = substr($content, $pos, $int);
                    return $name;
                }
            }
        }
    }
    
    public function file(Player $p = null){ 
        $dataPath = Server::getInstance()->getDataPath();
        $myPluginPath = $dataPath . "virions/";
        if (!file_exists($myPluginPath)) {
            return false;
        }
        $folders = scandir($myPluginPath);
        $list = [];  
        foreach ($folders as $folder) {
            if ($folder !== "." && $folder !== ".." && is_dir($myPluginPath . $folder)) {  
                $srcPath = $myPluginPath . $folder . "/src";  
                $plugin = $myPluginPath . $folder . "/virion.yml";    
                if (file_exists($plugin)) {
                    $content = file_get_contents($plugin);
                    if (strpos($content, 'api: 4.0.0') == false){
                        Server::getInstance()->getLogger()->info("§c" . $folder . ": That Vrions is not 4.0.0. ");   
                        return true;
                    } 
                    $file = $this->getFiles("name", $folder, 4) ?? "(§aName§c is missing.)";
                    $ab = $this->getFiles("antigen", $folder, 7) ?? "(§aAntigen§c is missing.)";
                    $author = $this->getFiles("author", $folder, 6) ?? "(§aAuthor§c is missing.)";
                    $version = $this->getFiles("version", $folder, 7) ?? "(§aVersion§c is missing.)";
                    if ($file === "" || $ab === "" || $author === "" || $version == "" || $version !== "version" || $file !== "name" || $ab !== "antigen" || $author !== "author") {
                        Server::getInstance()->getLogger()->info("§c" . $folder . "test: The Virion does not contain all or part of [name, antigen, author, version], or has a typo or indentation error.\nError information: {$file}:{$ab}:{$author} :{$version}");
                    }
                        if (!file_exists($srcPath) || !file_exists($plugin)) {             
                            Server::getInstance()->getLogger()->info("§cPlease remove the {$srcPath}: file or move it elsewhere! Do not put unnecessary folders in virions!");   
                            return false;
                        }
                        if ($p == null){
                            Server::getInstance()->getLogger()->info("§a" . $folder . ": load success!"); 
                        }else{
                            $p->sendMessage("§a". $folder);
                        }
                    }      
                }
            }
        }
    }
