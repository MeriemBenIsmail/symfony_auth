<?php

namespace App\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DemandService{

}

$containerBuilder = new ContainerBuilder();
$containerBuilder->register('DemandService', 'DemandService');