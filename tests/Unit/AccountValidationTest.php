<?php

namespace Tests\Unit;

use App\Support\AccountValidation;
use PHPUnit\Framework\TestCase;

class AccountValidationTest extends TestCase
{
    public function test_pending_validation_payload_is_explicit_for_clients(): void
    {
        $payload = AccountValidation::payload(AccountValidation::REGISTRATION_MESSAGE);

        $this->assertSame('pending_admin_validation', $payload['account_status']);
        $this->assertTrue($payload['pending_validation']);
        $this->assertFalse($payload['verified']);
        $this->assertStringContainsString('validation par un administrateur', $payload['message']);
    }
}
