<?php

return [
    'service_manager' => [
        'invokables' => [
            'doctrine.fixture.import' => \WShafer\ZfDoctrineFixtures\Command\Import::class,
            'doctrine.fixture.list' => \WShafer\ZfDoctrineFixtures\Command\ListFixtures::class,
        ]
    ]
];
