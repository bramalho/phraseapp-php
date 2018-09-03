<?php

namespace PhraseAppPHP;

use Symfony\Component\Yaml\Yaml;

class Config
{
    public static function get($name = null)
    {
        $config = Yaml::parseFile(__DIR__ . '/../config.yml');

        return $config[$name] ?? $config;
    }
}
