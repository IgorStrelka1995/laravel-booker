<?php


namespace Tests\Controllers\Api\V1;


use App\Models\Boardroom;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class BoardroomControllerTest extends TestCase
{
    public function testGetAllBoardrooms()
    {
        // Unauthenticated
        $this->json('get', 'api/v1/boardroom')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('get', 'api/v1/boardroom')->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()->create(self::$admin);

        $boardroomActive = Boardroom::factory()->create(self::$boardroomActive);
        $boardroomDeactivated = Boardroom::factory()->create(self::$boardroomDeactivated);

        $this->actingAs($admin)
            ->json('get', 'api/v1/boardroom')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    [
                        'id' => $boardroomActive->getAttribute('id'),
                        'name' => $boardroomActive->getAttribute('name'),
                        'active' => $boardroomActive->getAttribute('active')
                    ]
                ]
            ]);
    }

    public function testGetAllBoardroomsIncludeEvents()
    {
        // Unauthenticated
        $this->json('get', 'api/v1/boardroom')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('get', 'api/v1/boardroom')->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()->create(self::$admin);

        $boardroomActive = Boardroom::factory()->create(self::$boardroomActive);
        $boardroomDeactivated = Boardroom::factory()->create(self::$boardroomDeactivated);

        $event = Event::factory()->create([
            "description" => "Event has been booked",
            "starttime" => "34934035",
            "endtime" => "34934036",
            "boardroom_id" => $boardroomActive->getAttribute('id'),
            "user_id" => $admin->getAttribute('id')
        ]);

        $this->actingAs($admin)
            ->json('get', 'api/v1/boardroom?include=events')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    [
                        'id' => $boardroomActive->getAttribute('id'),
                        'name' => $boardroomActive->getAttribute('name'),
                        'active' => $boardroomActive->getAttribute('active'),
                        'events' => [
                            [
                                "id" => $event->getAttribute('id'),
                                "description" => $event->getAttribute('description'),
                                "starttime" => (int)$event->getAttribute('starttime'),
                                "endtime" => (int)$event->getAttribute('endtime'),
                                "boardroom_id" => $event->getAttribute('boardroom_id'),
                                "user_id" => $event->getAttribute('user_id'),
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testCreateBoardroom()
    {
        $boardroomData = self::$boardroomActive;

        // Unauthenticated
        $this->json('post', 'api/v1/boardroom', $boardroomData)->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('post', 'api/v1/boardroom', $boardroomData)->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()->create(self::$admin);

        $boardroomDataResponse = array_merge($boardroomData, ['id' => 1]);

        $this->actingAs($admin)
            ->json('post', 'api/v1/boardroom', $boardroomData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertExactJson(['data' => $boardroomDataResponse])
        ;
    }

    public function testUpdateBoardroom()
    {
        $boardroomData = self::$boardroomActive;

        $boardroomActive = Boardroom::factory()->create($boardroomData);

        // Unauthenticated
        $this->json('put', 'api/v1/boardroom/1', $boardroomData)->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('put', 'api/v1/boardroom/1', $boardroomData)->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()->create(self::$admin);

        $boardroomDataUpdated = ['name' => 'Boardroom 1 (updated)', 'active' => true];
        $boardroomDataUpdatedResponse = array_merge(['id' => 1], $boardroomDataUpdated);

        $this->actingAs($admin)
            ->json('put', 'api/v1/boardroom/1', $boardroomDataUpdated)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => $boardroomDataUpdatedResponse
            ])
        ;
    }

    public function testDestroyBoardroom()
    {
        $boardroomData = self::$boardroomActive;

        Boardroom::factory()->create($boardroomData);

        // Unauthenticated
        $this->json('delete', 'api/v1/boardroom/1', $boardroomData)->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('delete', 'api/v1/boardroom/1', $boardroomData)->assertStatus(Response::HTTP_FORBIDDEN);

        $admin = User::factory()->create(self::$admin);

        $this->assertNotEmpty(Boardroom::all()->toArray());

        $this->actingAs($admin)
            ->json('delete', 'api/v1/boardroom/1')
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertEmpty(Boardroom::all()->toArray());
    }
}
