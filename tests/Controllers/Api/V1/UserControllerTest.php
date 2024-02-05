<?php


namespace Tests\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testGetAllUsers()
    {
        // Unauthenticated
        $this->json('get', 'api/v1/user')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('get', 'api/v1/user')->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()->create(self::$admin);

        $response = $this->actingAs($admin)->json('get', 'api/v1/user');

        $this->assertEquals(Response::HTTP_OK, $response->status());

        $data = $response->json();

        unset($data['links']);
        unset($data['meta']);

        $this->assertEquals([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'John',
                    'email' => 'john@mail.com',
                ],
                [
                    'id' => 2,
                    'name' => 'Admin',
                    'email' => 'admin@mail.com',
                ]
            ]], $data);
    }

    public function testGetSingleUser()
    {
        // Unauthenticated
        $this->json('get', 'api/v1/user/1')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('get', 'api/v1/user/1')->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()->create(self::$admin);

        $this->actingAs($admin)->json('get', 'api/v1/user/1')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    'id' => 1,
                    'name' => 'John',
                    'email' => 'john@mail.com',
                ]
            ]);
    }

    public function testCreateUser()
    {
        $this->json('post', 'api/v1/user', self::$customer)->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('post', 'api/v1/user', self::$customer)->assertStatus(Response::HTTP_FORBIDDEN);

        $user = User::factory()->create(self::$admin);

        $this->actingAs($user)
            ->json('post', 'api/v1/user', [
                'name' => 'Peter',
                'email' => 'peter@mail.com',
                'password' => 'password'
            ])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertExactJson([
                'data' => [
                    'id' => 3,
                    'name' => 'Peter',
                    'email' => 'peter@mail.com',
                ]
            ])
        ;
    }

    public function testUpdateUser()
    {
        $this->json('put', 'api/v1/user/1', self::$customer)->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('put', 'api/v1/user/1', self::$customer)->assertStatus(Response::HTTP_FORBIDDEN);

        $user = User::factory()->create(self::$admin);

        $this->actingAs($user)
            ->json('put', 'api/v1/user/1', ['name' => 'John Jackson'])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    'id' => 1,
                    'name' => 'John Jackson',
                    'email' => 'john@mail.com',
                ]
            ])
        ;
    }

    public function testDestroyUser()
    {
        $this->json('delete', 'api/v1/user/1', self::$customer)->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('delete', 'api/v1/user/1', self::$customer)->assertStatus(Response::HTTP_FORBIDDEN);

        $user = User::factory()->create(self::$admin);

        $this->actingAs($user)
            ->json('delete', 'api/v1/user/1')
            ->assertStatus(Response::HTTP_NO_CONTENT)
        ;
    }
}
