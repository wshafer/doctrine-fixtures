<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace WShafer\ZfDoctrineFixtures;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputOption;
use WShafer\ZfDoctrineFixtures\Command\ConfigInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\EventManager\EventInterface;

/**
 * Base module for Doctrine ORM.
 *
 * @license MIT
 * @link    www.doctrine-project.org
 * @author  Kyle Spraggs <theman@spiffyjr.me>
 * @author  Marco Pivetta <ocramius@gmail.com>
 */
class Module implements
    ConfigProviderInterface,
    InitProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function init(ModuleManagerInterface $manager)
    {
        $events = $manager->getEventManager();
        $events->getSharedManager()->attach(
            'doctrine',
            'loadCli.post',
            [$this, 'initializeConsole']
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getModuleDependencies()
    {
        return ['DoctrineModule'];
    }


    public function initializeConsole(EventInterface $event)
    {
        /* @var $cli \Symfony\Component\Console\Application */
        $cli            = $event->getTarget();
        /* @var $serviceLocator \Zend\ServiceManager\ServiceLocatorInterface */
        $serviceLocator = $event->getParam('ServiceManager');

        $commands = [
            'doctrine.fixture.import',
            'doctrine.fixture.list',
        ];

        foreach ($commands as $commandName) {
            /* @var $command \Symfony\Component\Console\Command\Command */
            $command = $serviceLocator->get($commandName);

            $command->getDefinition()->addOption(new InputOption(
                'object-manager',
                null,
                InputOption::VALUE_OPTIONAL,
                'The name of the object manager to use.',
                'doctrine.entitymanager.orm_default'
            ));

            if ($command instanceof ConfigInterface) {
                $config = $serviceLocator->get('config');
                $doctrineConfig = $config['doctrine'] ?? [];
                $command->setConfig($doctrineConfig);
            }

            $cli->add($command);
        }
    }
}
