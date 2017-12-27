<?php

return [
    'service_manager' => [
        'invokables' => [
            'doctrine.fixture.import' => \WShafer\ZfDoctrineModule\Import::class,
        ]
    ]
];
