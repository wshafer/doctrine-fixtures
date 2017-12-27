<?php

namespace WShafer\ZfDoctrineFixtures\Command;

interface ConfigInterface
{
    public function setConfig($config);
    public function getConfig();
}