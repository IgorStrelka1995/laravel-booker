<?php


namespace Tests\Controllers\Api\V1;

use App\Models\Boardroom;
use App\Models\Event;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Http\Response;

class EventControllerTest extends TestCase
{
    public function testGetAllEvents()
    {
        // Unauthenticated
        $this->json('get', 'api/v1/event')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $customer = User::factory()->create(self::$customer);

        // Unauthorized
        $this->actingAs($customer)->json('get', 'api/v1/event')->assertStatus(Response::HTTP_FORBIDDEN);

        $boardroomActive = Boardroom::factory()->create(self::$boardroomActive);

        $admin = User::factory()->create(self::$admin);

        Event::factory()->create([
            "description" => "Event has been created",
            "starttime" => 34934035,
            "endtime" => 34934036,
            "boardroom_id" => $boardroomActive->getAttribute('id'),
            "user_id" => $customer->getAttribute('id')
        ]);

        $response = $this->actingAs($admin)->json('get', 'api/v1/event');

        $this->assertEquals(Response::HTTP_OK, $response->status());

        $data = $response->json();

        unset($data['links']);
        unset($data['meta']);

        $this->assertEquals([
            'data' => [
                [
                    "id" => 1,
                    "description" => "Event has been created",
                    "starttime" => 34934035,
                    "endtime" => 34934036,
                    "boardroom_id" => $boardroomActive->getAttribute('id'),
                    "user_id" => $customer->getAttribute('id')
                ]
            ]], $data);
    }

    public function testGetSingleEvent()
    {
        $customer = User::factory()->create(self::$customer);

        $boardroomActive = Boardroom::factory()->create(self::$boardroomActive);

        $event = Event::factory()->create([
            "description" => "Event has been created",
            "starttime" => 34934035,
            "endtime" => 34934036,
            "boardroom_id" => $boardroomActive->getAttribute('id'),
            "user_id" => $customer->getAttribute('id')
        ]);

        $eventResponse = $event->toArray();

        unset($eventResponse['created_at']);
        unset($eventResponse['updated_at']);

        // Unauthenticated
        $this->json('get', 'api/v1/event/1')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->actingAs($customer)->json('get', 'api/v1/event/1')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => $eventResponse
            ]);

        $admin = User::factory()->create(self::$admin);

        $this->actingAs($admin)->json('get', 'api/v1/event/1')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => $eventResponse
            ]);

        $customer = User::factory()->create(['name' => 'Karl', 'email' => 'karl@mail.com', 'is_admin' => false]);

        $this->actingAs($customer)->json('get', 'api/v1/event/1')->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateEvent()
    {
        $customer = User::factory()->create(self::$customer);
        $boardroom = Boardroom::factory()->create(self::$boardroomActive);

        $eventData = [
            "description" => "Meeting!",
            "starttime" => 34934035,
            "endtime" => 34934136,
            "boardroom_id" => $boardroom->getAttribute('id'),
            "user_id" => $customer->getAttribute('id')
        ];

        // Unauthenticated
        $this->json('post', 'api/v1/event')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $eventDataResponse = array_merge(['id' => 1], $eventData);

        $this->actingAs($customer)
            ->json('post', 'api/v1/event', $eventData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertExactJson([
                'data' => $eventDataResponse
            ])
        ;

        $eventDataBooked = [
            "description" => "Meeting!",
            "starttime" => 34934039,
            "endtime" =>   34934151,
            "boardroom_id" => $boardroom->getAttribute('id'),
            "user_id" => $customer->getAttribute('id')
        ];

        $this->actingAs($customer)
            ->json('post', 'api/v1/event', $eventDataBooked)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => "Boardroom already booked at this time. Choose another boardroom or change meeting time.",
                "errors" => [
                    "endtime" => [
                        "Boardroom already booked at this time. Choose another boardroom or change meeting time."
                    ]
                ]
            ])
        ;
    }

    public function testUpdateEvent()
    {
        $customer = User::factory()->create(self::$customer);
        $customerTwo = User::factory()->create(self::$customerTwo);

        $boardroom = Boardroom::factory()->create(self::$boardroomActive);

        $event = Event::factory()->create([
            "description" => "Event has been created",
            "starttime" => 34934035,
            "endtime" => 34934036,
            "boardroom_id" => $boardroom->getAttribute('id'),
            "user_id" => $customer->getAttribute('id')
        ]);

        // Unauthenticated
        $this->json('put', 'api/v1/event/1')->assertStatus(Response::HTTP_UNAUTHORIZED);

        $eventDataUpdated = ['description' => 'Meeting! (updated)'];

        $this->actingAs($customer)
            ->json('put', 'api/v1/event/1', $eventDataUpdated)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    "id" => $event->getAttribute('id'),
                    "description" => "Meeting! (updated)",
                    "starttime" => $event->getAttribute('starttime'),
                    "endtime" => $event->getAttribute('endtime'),
                    "boardroom_id" => $boardroom->getAttribute('id'),
                    "user_id" => $customer->getAttribute('id')
                ]
            ])
        ;

        $this->actingAs($customerTwo)
            ->json('put', 'api/v1/event/1', $eventDataUpdated)
            ->assertStatus(Response::HTTP_FORBIDDEN)
        ;
    }

    public function testDestroyEvent()
    {
        $customer = User::factory()->create(self::$customer);
        $customerTwo = User::factory()->create(self::$customerTwo);

        $boardroom = Boardroom::factory()->create(['name' => 'Boardroom 1', 'active' => true]);

        Event::factory()->create([
            "description" => "Event has been created",
            "starttime" => 34934035,
            "endtime" => 34934036,
            "boardroom_id" => $boardroom->getAttribute('id'),
            "user_id" => $customer->getAttribute('id')
        ]);

        $this->actingAs($customerTwo)
            ->json('delete', 'api/v1/event/1')
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->actingAs($customer)
            ->json('delete', 'api/v1/event/1')
            ->assertStatus(Response::HTTP_NO_CONTENT);

        Event::factory()->create([
            "description" => "Event has been created",
            "starttime" => 34934035,
            "endtime" => 34934036,
            "boardroom_id" => $boardroom->getAttribute('id'),
            "user_id" => $customer->getAttribute('id')
        ]);

        $admin = User::factory()->create(self::$admin);

        $this->actingAs($admin)
            ->json('delete', 'api/v1/event/2')
            ->assertStatus(Response::HTTP_NO_CONTENT)
        ;
    }
}
