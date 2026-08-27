<?php

namespace App\Services;

use L5Swagger\ConfigFactory;
use L5Swagger\GeneratorFactory;
use L5Swagger\SecurityDefinitions;

/**
 * Factory personnalisée qui retourne un SilentSwaggerGenerator
 * pour éviter que les warnings d'annotations swagger bloquent la génération.
 */
class SilentGeneratorFactory extends GeneratorFactory
{
    private ConfigFactory $configFactory;

    public function __construct(ConfigFactory $configFactory)
    {
        parent::__construct($configFactory);
        $this->configFactory = $configFactory;
    }

    public function make(string $documentation): \L5Swagger\Generator
    {
        $config = $this->configFactory->documentationConfig($documentation);

        $paths       = $config['paths'];
        $scanOptions = $config['scanOptions'] ?? [];
        $constants   = $config['constants'] ?? [];
        $yamlCopy    = $config['generate_yaml_copy'] ?? false;
        $security    = new SecurityDefinitions(
            $config['securityDefinitions']['securitySchemes'] ?? [],
            $config['securityDefinitions']['security'] ?? [],
        );

        return new SilentSwaggerGenerator($paths, $constants, $yamlCopy, $security, $scanOptions);
    }
}
