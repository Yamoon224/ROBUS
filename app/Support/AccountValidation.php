<?php

namespace App\Support;

final class AccountValidation
{
    public const STATUS_PENDING = 'pending_admin_validation';

    public const MESSAGE = 'Votre compte est en attente de validation par un administrateur.';

    public const REGISTRATION_MESSAGE = 'Votre compte a été créé avec succès. Il est en attente de validation par un administrateur.';

    public static function payload(string $message = self::MESSAGE): array
    {
        return [
            'message' => $message,
            'account_status' => self::STATUS_PENDING,
            'pending_validation' => true,
            'verified' => false,
        ];
    }
}
