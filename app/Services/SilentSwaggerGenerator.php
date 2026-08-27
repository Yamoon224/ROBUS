<?php

namespace App\Services;

use L5Swagger\Generator;
use OpenApi\Generator as OpenApiGenerator;

/**
 * Étend le générateur L5Swagger pour injecter un logger silencieux
 * à la place du DefaultLogger qui appelle trigger_error sur chaque warning.
 */
class SilentSwaggerGenerator extends Generator
{
    protected function createOpenApiGenerator(): OpenApiGenerator
    {
        $generator = new OpenApiGenerator(new SilentSwaggerLogger());

        if (method_exists($generator, 'setVersion')) {
            $generator->setVersion(
                $this->scanOptions['open_api_spec_version'] ?? self::OPEN_API_DEFAULT_SPEC_VERSION
            );
        }

        $this->setProcessors($generator);
        $this->setAnalyser($generator);

        return $generator;
    }
}
