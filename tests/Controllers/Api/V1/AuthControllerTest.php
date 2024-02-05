<?php


namespace Tests\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest  extends TestCase
{
    public function testLogin()
    {
        $customerData = self::$customer;
        $customerData['password'] = 'password';

        $this->json('post', 'api/v1/login', $customerData)->assertStatus(Response::HTTP_UNAUTHORIZED);

        User::factory()->create(self::$customer);

        $this->json('post', 'api/v1/login', [
            'email' => self::$customer['email'],
            'password' => 'password1'
        ])->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response = $this->json('post', 'api/v1/login', [
            'email' => self::$customer['email'],
            'password' => 'password'
        ])->assertStatus(Response::HTTP_OK);

        $this->assertArrayHasKey('access_token', $response);
    }
}
