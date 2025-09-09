<?php
namespace Game;


class DatabaseManager
{
    private static ?Database $instance;


    public static function setIntance(?Database $instance): void
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?Database
    {
        return self::$instance;
    }

    public static function hasInstance(): bool
    {
        return !empty(self::$instance);
    }
}