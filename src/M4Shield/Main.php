<?php

/**
* 
* ███╗░░░███╗░░██╗██╗████████╗██╗░░██╗███████╗██╗░░░██╗░██████╗
* ████╗░████║░██╔╝██║╚══██╔══╝██║░░██║██╔════╝██║░░░██║██╔════╝
* ██╔████╔██║██╔╝░██║░░░██║░░░███████║█████╗░░██║░░░██║╚█████╗░
* ██║╚██╔╝██║███████║░░░██║░░░██╔══██║██╔══╝░░██║░░░██║░╚═══██╗
* ██║░╚═╝░██║╚════██║░░░██║░░░██║░░██║███████╗╚██████╔╝██████╔╝
* ╚═╝░░░░░╚═╝░░░░░╚═╝░░░╚═╝░░░╚═╝░░╚═╝╚══════╝░╚═════╝░╚═════╝░
*
* This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * @author m4theus.wtfkkj
*/

namespace M4Shield;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use M4Shield\EventListener;
use M4Shield\Util\Cache;

class Main extends PluginBase
{
    private Config $config;
    public const M4SHIELD_VERSION = 3.1;

    public function onEnable(): void
    {
        $this->getLogger()->info("|===============> M4Shield <===============|");
        $this->getLogger()->info("- Plugin by M4theuskkj (@m4theus.wtfkkj)");
        $this->getLogger()->info("- Versão do plugin: " . self::M4SHIELD_VERSION);
        $this->getLogger()->info("- Carregando a config.yml e registrando listener...");

        if (!$this->verifyPlugin()) {
            $this->initConfig();
            if (in_array("player1", $this->config->getNested("commandblocker.allowedPlayers")) || in_array("player2", $this->config->getNested("commandblocker.allowedPlayers"))) {
                $this->getLogger()->critical("|===============> M4Shield <===============|");
                $this->getLogger()->critical("- Altere os nicks \"player1\" ou \"player2\" no bloqueador de comandos.");
                $this->getLogger()->critical("- Desativando....");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                return;
            }
            $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        }
    }

    public function onDisable(): void
    {
        if (Cache::hasCache("all")) {
            Cache::clearAll();
        }
    }

    public function initConfig(): void
    {
        if (!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }

        $configFile = $this->getDataFolder() . "config.yml";

        if (!file_exists($configFile)) {
            $this->saveDefaultConfig();
        }

        $this->config = new Config($configFile, Config::YAML);

        if ($this->config->getNested("plugin.version") !== self::M4SHIELD_VERSION) {
            $this->getLogger()->warning("A config.yml do plugin está desatualizada ou não existe, criando uma nova config.yml...");
            @unlink($configFile);
            $this->saveDefaultConfig();
        }
    }

    public function verifyPlugin(): bool
    {
        $changed = false;
        if (base64_encode($this->getDescription()->getName()) !== "TTRzaGllbGRQTU1QNQ==" || base64_encode($this->getDescription()->getDescription()) !== "VW0gcGx1Z2luIHByYSBwcm90ZWdlciBzZXJ2aWRvcmVzIGNvbnRyYSBncmllZiwgYm90cyBlIG91dHJhcyBjb2lzYXMsIG9yaWdpbmFsbWVudGUsIGZlaXRvIHBhcmEgcG9ja2V0bWluZSAyLjAuMCwgaW1wb3J0YWRvIHBhcmEgNS4wLjA=") {
            $this->getLogger()->error(base64_decode("UGFyZWNlIHF1ZSBhbGd1bWFzIGluZm9ybWHDp8O1ZXMgZG8gcGx1Z2luIGZvcmFtIGFsdGVyYWRhcywgZGVzYXRpdmFuZG8uLi4="));
            $this->getServer()->getPluginManager()->disablePlugin($this);
            $changed = true;
        }

        return $changed;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }
}