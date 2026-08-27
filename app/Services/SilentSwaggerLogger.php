<?php

namespace App\Services;

use Psr\Log\AbstractLogger;

/**
 * Logger silencieux pour swagger-php — évite que les warnings de syntaxe
 * d'annotations fassent planter la génération via trigger_error.
 */
class SilentSwaggerLogger extends AbstractLogger
{
    public function log($level, $message, array $context = []): void
    {
        // Absorbe tous les messages sans déclencher trigger_error
    }
}
