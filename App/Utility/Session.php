<?php

namespace App\Utility;

class Session
{
    // Démarrer la session si ce n'est pas déjà fait
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Ajouter un message flash
    public static function addFlash($type, $message)
    {
        self::start();
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        if (!isset($_SESSION['flash'][$type])) {
            $_SESSION['flash'][$type] = [];
        }
        $_SESSION['flash'][$type][] = $message;
    }

    // Récupérer et supprimer les messages flash
    public static function getFlashes()
    {
        self::start();
        if (isset($_SESSION['flash'])) {
            $flashes = $_SESSION['flash'];
            unset($_SESSION['flash']); // Supprimer les messages flash après lecture
            return $flashes;
        }
        return [];
    }
}
