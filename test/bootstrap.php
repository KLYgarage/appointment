<?php

use DI\ContainerBuilder;

include __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions(__DIR__ . '/di-config.php');

$container = $containerBuilder->build();

return $container;
