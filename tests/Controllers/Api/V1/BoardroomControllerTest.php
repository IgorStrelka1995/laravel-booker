<?php


namespace Tests\Controllers\Api\V1;


use App\Models\Boardroom;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class BoardroomControllerTest extends TestCase
{
    private static $customer = [
        'name' => 'John',
        'email' => 'john@mail.com',
        'is_admin' => false
    ];

    private static $admin = [
        'name' => 'Admin',
        'email' => 'admin@mail.com',
        'is_admin' => true
    ];

    public function testGetAllBoardrooms()
    {
        // Unauthenticated
        $this->json('get', 'api/v1/boardroom')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($user)->json('get', 'api/v1/boardroom')->assertStatus(Response::HTTP_FORBIDDEN);

        $user = User::factory()->create(self::$admin);

        Boardroom::factory()->create(['name' => 'Boardroom 1', 'active' => true]);
        Boardroom::factory()->create(['name' => 'Boardroom 2', 'active' => false]);

        $this->actingAs($user)
            ->json('get', 'api/v1/boardroom')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Boardroom 1',
                        'active' => 1
                    ]
                ]
            ]);
    }

    public function testGetAllBoardroomsIncludeEvents()
    {
        // Unauthenticated
        $this->json('get', 'api/v1/boardroom')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($user)->json('get', 'api/v1/boardroom')->assertStatus(Response::HTTP_FORBIDDEN);

        $user = User::factory()->create(self::$admin);

        Boardroom::factory()->create(['name' => 'Boardroom 1', 'active' => true]);
        Boardroom::factory()->create(['name' => 'Boardroom 2', 'active' => false]);

        Event::factory()->create([
            "description" => "Event has been booked",
            "starttime" => "34934035",
            "endtime" => "34934036",
            "boardroom_id" => "1",
            "user_id" => "1"
        ]);

        $this->actingAs($user)
            ->json('get', 'api/v1/boardroom?include=events')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Boardroom 1',
                        'active' => 1,
                        'events' => [
                            [
                                "id" => 1,
                                "description" => "Event has been booked",
                                "starttime" => 34934035,
                                "endtime" => 34934036,
                                "boardroom_id" => 1,
                                "user_id" => 1,
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testCreateBoardroom()
    {
        $boardroomData = ['name' => 'Boardroom 1', 'active' => true];

        // Unauthenticated
        $this->json('post', 'api/v1/boardroom', $boardroomData)->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($user)->json('post', 'api/v1/boardroom', $boardroomData)->assertStatus(Response::HTTP_FORBIDDEN);

        $user = User::factory()->create(self::$admin);

        $this->actingAs($user)
            ->json('post', 'api/v1/boardroom', $boardroomData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertExactJson([
                'data' => [
                    'id' => 1,
                    'name' => 'Boardroom 1',
                    'active' => true
                ]
            ])
        ;
    }

    public function testUpdateBoardroom()
    {
        $boardroomData = ['name' => 'Boardroom 1', 'active' => true];

        Boardroom::factory()->create($boardroomData);

        // Unauthenticated
        $this->json('put', 'api/v1/boardroom/1', $boardroomData)->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($user)->json('put', 'api/v1/boardroom/1', $boardroomData)->assertStatus(Response::HTTP_FORBIDDEN);

        $user = User::factory()->create(self::$admin);

        $boardroomDataUpdated = ['name' => 'Boardroom 1 (updated)', 'active' => true];

        $this->actingAs($user)
            ->json('put', 'api/v1/boardroom/1', $boardroomDataUpdated)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    'id' => 1,
                    'name' => 'Boardroom 1 (updated)',
                    'active' => true
                ]
            ])
        ;
    }

    public function testDestroyBoardroom()
    {
        $boardroomData = ['name' => 'Boardroom 1', 'active' => true];

        Boardroom::factory()->create($boardroomData);

        // Unauthenticated
        $this->json('delete', 'api/v1/boardroom/1', $boardroomData)->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($user)->json('delete', 'api/v1/boardroom/1', $boardroomData)->assertStatus(Response::HTTP_FORBIDDEN);

        $user = User::factory()->create(self::$admin);

        $this->assertNotEmpty(Boardroom::all()->toArray());

        $this->actingAs($user)
            ->json('delete', 'api/v1/boardroom/1')
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertEmpty(Boardroom::all()->toArray());
    }
}
