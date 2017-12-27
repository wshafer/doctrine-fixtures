<?php

namespace WShafer\ZfDoctrineModule;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Import extends Command
{
    protected function configure()
    {
        parent::configure();

        $this->setName('fixtures:import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getHelper('em')->getEntityManager();

        $output->writeln('Hi');
    }
}
