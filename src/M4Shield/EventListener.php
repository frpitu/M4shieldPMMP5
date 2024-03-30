<?php

namespace M4Shield;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\item\Item;
use pocketmine\Player;

use M4Shield\Main;

class EventListener implements Listener {

    private Main $main;

    public function __construct(Main $main) {
        $this->main = $main;
    }

    private function getServer(): \pocketmine\Server {
        return $this->main->getServer();
    }

    private function getConfig(): \pocketmine\utils\Config {
        return $this->main->getConfig();
    }

    private function removeBlockedItem(Player $player): void {
        $player->getInventory()->setItemInHand(Item::get(0, 0));
    }

    private function processBlockedWords(string $message): string {
        $blockedWords = $this->getConfig()->getNested("chatblocker.blockedWords", []);
        $replacementChar = $this->getConfig()->getNested("chatblocker.replacementChar", "*");

        $blockedWordsSet = array_flip($blockedWords);
        $pattern = '/\b(' . implode('|', array_map('preg_quote', $blockedWords)) . ')\b/i';
        $message = preg_replace_callback($pattern, function($matches) use ($replacementChar) {
            return str_repeat($replacementChar, mb_strlen($matches[0]));
        }, $message);

        return $message;
    }

    private function processLeak(string $message, string $replacementChar): string {
        $ipv4Pattern = '/\b(?:\d{1,3}(?:\s|\.|,)){3}\d{1,3}\b/';
        $ipv6Pattern = '/(?:[0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}/';

        if (preg_match($ipv4Pattern, $message) || preg_match($ipv6Pattern, $message)) {
            $message = preg_replace($ipv4Pattern, str_repeat($replacementChar, 7), $message);
            $message = preg_replace($ipv6Pattern, str_repeat($replacementChar, 7), $message);
        }

        return $message;
    }

    public function onLogin(PlayerLoginEvent $event): void {
        $player = $event->getPlayer();
        $ip = $player->getAddress();
        $users = 0;
        $antibotEnabled = $this->getConfig()->getNested("antibot.enabled", true);
        $antibotMaxCount = $this->getConfig()->getNested("antibot.maxCount", 4);

        if ($antibotEnabled) {
            foreach ($this->getServer()->getOnlinePlayers() as $onlinePlayer) {
                if ($ip == $onlinePlayer->getAddress()) {
                    $users++;
                }
            }
            if ($users >= $antibotMaxCount) {
                $this->getServer()->getNetwork()->blockAddress($ip, -1);
            }
        }
    }

    public function onUse(PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $id = $item->getId();
        $antigriefEnabled = $this->getConfig()->getNested("antigrief.enabled", true);
        $blockedItems = $this->getConfig()->getNested("antigrief.blockedItems", []);

        if ($antigriefEnabled && in_array($id, $blockedItems)) {
            $this->removeBlockedItem($player);
            $player->sendMessage($this->getConfig()->getNested("antigrief.message", "§8[§bM4Shield§8] §cEste item está bloqueado."));
            $event->setCancelled(true);
        }
    }
}
