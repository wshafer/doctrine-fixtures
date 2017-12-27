<?php

namespace WShafer\ZfDoctrineFixtures\Command;

use Symfony\Component\Console\Command\Command;

abstract class ConfiguredCommandAbstract extends Command implements ConfigInterface
{
    protected $config;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        if (empty($this->config)) {
            throw new \RuntimeException('No Configuration found');
        }

        return $this->config;
    }
}
