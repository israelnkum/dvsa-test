<?php

namespace Tests\Feature\Trait;

use App\Models\User;

trait Auth
{
    private function authenticateUser(): string
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $this->withHeaders(['Authorization' => "Bearer {$token}"]);

        return $token;
    }
}
