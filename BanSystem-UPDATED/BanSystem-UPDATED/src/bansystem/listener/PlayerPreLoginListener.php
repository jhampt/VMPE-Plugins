<?php

namespace bansystem\listener;

use bansystem\util\date\Countdown;
use DateTime;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\TextFormat;

class PlayerPreLoginListener implements Listener {
    
    public function onPlayerPreLogin(PlayerPreLoginEvent $event) {
        $player = $event->getPlayer();
        $senderName = $event->getName();
        $banList = $player->getServer()->getNameBans();
        if ($banList->isBanned(strtolower($player->getName()))) {
            $kickMessage = "";
            $banEntry = $banList->getEntries();
            $entry = $banEntry[strtolower($player->getName())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                $senderName = $event->getName();
                if ($reason != null || $reason != "") {
                    $kickMessage = TextFormat::RED . "§cYou are currently banned. Banned by: §b$senderName §aReason: " . TextFormat::AQUA . $reason . TextFormat::RED . ".";
                } else {
                    $kickMessage = TextFormat::RED . "You are currently banned by §b$senderName";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $banList->remove($entry->getName());
                    return;
                }
                $banReason = $entry->getReason();
                $senderName = $event->getName();
                if ($banReason != null || $banReason != "") {
                    $kickMessage = TextFormat::RED . "You are currently banned by: §a$senderName §bReason: " . TextFormat::LIGHT_PURPLE . $banReason . TextFormat::RED . " until " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                } else {
                    $kickMessage = TextFormat::RED . "You are currently banned by §a$senderName §buntil " . TextFormat::LIGHT_PURPLE . $expiry . TextFormat::RED . ".";
                }
            }
            $player->close("", $kickMessage);
        }
    }
    
    public function onPlayerPreLogin2(PlayerPreLoginEvent $event) {
        $player = $event->getPlayer();
        $senderName = $event->getName();
        $banList = $player->getServer()->getIPBans();
        if ($banList->isBanned(strtolower($player->getAddress()))) {
            $kickMessage = "";
            $banEntry = $banList->getEntries();
            $entry = $banEntry[strtolower($player->getAddress())];
            if ($entry->getExpires() == null) {
                $reason = $entry->getReason();
                $senderName = $event->getName();
                if ($reason != null || $reason != "") {
                    $kickMessage = TextFormat::RED . "You are currently IP banned by §b$senderName §aReason: " . TextFormat::AQUA . $reason . TextFormat::RED . ".";
                } else {
                    $kickMessage = TextFormat::RED . "You are currently IP banned by §b$senderName";
                }
            } else {
                $expiry = Countdown::expirationTimerToString($entry->getExpires(), new DateTime());
                if ($entry->hasExpired()) {
                    $banList->remove($entry->getName());
                    return;
                }
                $banReason = $entry->getReason();
                $senderName = $event->getName();
                if ($banReason != null || $banReason != "") {
                    $kickMessage = TextFormat::RED . "You are currently IP banned by §b$senderName §aReason: " . TextFormat::AQUA . $banReason . TextFormat::RED . " until " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                } else {
                    $kickMessage = TextFormat::RED . "You are currently IP banned by §b$senderName §auntil " . TextFormat::AQUA . $expiry . TextFormat::RED . ".";
                }
            }
            $player->close("", $kickMessage);
        }
    }
}
