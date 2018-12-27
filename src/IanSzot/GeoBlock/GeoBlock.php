<?php

declare(strict_types=1);


namespace IanSzot\GeoBlock;

use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;
use pocketmine\utils\Internet;


class GeoBlock extends PluginBase implements Listener{



    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }


    // Connects to external APIs to check for the player's country.
    // With some ugly failovers in case any go offline or reach some query limit
    public function checkCountry($player) : string{
        $json = json_decode(file_get_contents("http://ip-api.com/json/" .  $player), true);
        if($json['status'] == "success"){
            $country = $json['countryCode'];
        }else{
            $json = json_decode(file_get_contents("https://extreme-ip-lookup.com/json/" . $player), true);
            if($json['status'] == "success"){
                $country = $json['countryCode'];
            }else{
                // If everything fails
                $country = "fail";
                $this->getLogger()->alert("All APIs failed. Query limit reached or connection failed");
                }
            }

        return $country;
        
    }

    // Handles the player login
    public function playerLogin(PlayerPreLoginEvent $e) : void{

        // Players with permission can bypass the GeoBlock
        if(!$e->getPlayer()->hasPermission("geoblock.bypass")){
            $country = $this->checkCountry($e->getPlayer()->getAddress());

            if($country === "fail"){
                switch($this->config->get("fail")){
                    case 'deny':
                        $e->getPlayer()->kick(TextFormat::RED . $this->config->get("failMsg"), false);
                    break;
                    case 'allow':
                        $this->getLogger()->info("Allowing player " . $e->getPlayer()->getName() . " but could not confirm his country");
                    break;
                }
            }else{
                switch($this->config->get("action")){
                    case 'allow':
                        // strtoupper needed just in case countries in config.yml are not yet uppercase
                        if(!in_array($country, array_map('strtoupper', ($this->config->get("countries"))))){
                            $e->getPlayer()->kick(TextFormat::RED . $this->config->get("kickMsg"), false);
                            $this->getLogger()->info("Kicked player " . $e->getPlayer()->getName() . ". Joining from country " . $country);

                        }        
                    break;
                    case 'deny':
                        if(in_array($country, array_map('strtoupper', ($this->config->get("countries"))))){
                            $e->getPlayer()->kick(TextFormat::RED . $this->config->get("kickMsg"), false);
                            $this->getLogger()->info("Kicked player " . $e->getPlayer()->getName() . ". Joining from country " . $country);
                
                        }
                    break;
                }
            }
        }
        

    }
}