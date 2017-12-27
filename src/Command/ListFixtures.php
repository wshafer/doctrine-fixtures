<?php

namespace WShafer\ZfDoctrineFixtures\Command;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListFixtures extends ConfiguredCommandAbstract
{
    /** @var Loader */
    protected $loader;

    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->loader = new Loader();
    }

    protected function configure()
    {
        parent::configure();

        $this->setName('fixtures:list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loadFixtures($input->getOption('object-manager'));

        $fixtures = $this->loader->getFixtures();

        if (!$fixtures) {
            $output->writeln('<error>No fixtures found.</error>');
            return;
        }

        foreach ($fixtures as $fixtureName => $fixture) {
            $output->writeln('<info>'.$fixtureName.'</info>');
        }
    }

    protected function loadFixtures($emName)
    {
        $paths = $this->getFixtureConfig($emName);
        $this->addFixtures($paths);
    }

    protected function addFixtures(array $paths)
    {
        foreach ($paths as $path) {
            $this->addFixture($path);
        }
    }

    protected function addFixture($path) {
        if (!file_exists($path)) {
            throw new \RuntimeException($path.' does not exist.');
        }

        if (is_dir($path)) {
            $this->loader->loadFromDirectory($path);
            return;
        }

        $this->loader->loadFromFile($path);
    }

    protected function getFixtureConfig($emName)
    {
        $key = $this->getFixtureConfigKey($emName);

        $config = $this->getConfig();

        if (empty($config['fixtures'][$key])) {
            throw new \RuntimeException('Unable to locate fixture configuration for: $config[\'doctrine\'][\'fixtures\'][\''.$key.'\']');
        }

        if (!is_array($config['fixtures'][$key])) {
            throw new \RuntimeException('$config[\'doctrine\'][\'fixtures\'][\''.$key.'\'] must be an array of fixture paths');
        }

        return $config['fixtures'][$key];
    }

    protected function getFixtureConfigKey($emName)
    {
        $parts = explode('.', $emName);
        return end($parts);
    }
}
