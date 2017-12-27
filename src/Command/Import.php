<?php

namespace WShafer\ZfDoctrineFixtures\Command;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Import extends ListFixtures
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
        /** @var ORMPurger $purger */
        $purger = new ORMPurger();

        /** @var ORMExecutor $executor */
        $executor = new ORMExecutor($em, $purger);

        $this->loadFixtures($input->getOption('object-manager'));

        $executor->execute($this->loader->getFixtures());

        $output->writeln('<info>Complete</info>');
    }
}
