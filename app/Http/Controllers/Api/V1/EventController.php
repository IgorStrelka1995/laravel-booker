<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\V1\EventCollection;
use App\Http\Resources\V1\EventResource;
use App\Models\Event;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/event",
     *     summary="List of all events",
     *     tags={"Event"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="starttime", type="integer"),
     *                  @OA\Property(property="endtime", type="integer"),
     *                  @OA\Property(property="boardroom_id", type="integer"),
     *                  @OA\Property(property="user_id", type="integer"),
     *              )),
     *              example={
     *                  "data": {{
     *                      "id": 1,
     *                      "description": "Quibusdam sed rerum consectetur consequuntur nulla.",
     *                      "starttime": 34930246,
     *                      "endtime": 34932046,
     *                      "boardroom_id": 1,
     *                      "user_id": 1
     *                  },
     *                  {
     *                      "id": 2,
     *                      "description": "Quibusdam sed rerum consectetur consequuntur nulla.",
     *                      "starttime": 34940246,
     *                      "endtime": 34942146,
     *                      "boardroom_id": 1,
     *                      "user_id": 1
     *                  }}
     *              }
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $events = QueryBuilder::for(Event::class)
            ->allowedIncludes('events')
            ->paginate()
        ;

        return EventResource::collection($events);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/event",
     *     summary="Creating a new event.",
     *     tags={"Event"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                      @OA\Property(property="description", type="string"),
     *                      @OA\Property(property="starttime", type="integer"),
     *                      @OA\Property(property="endtime", type="integer"),
     *                      @OA\Property(property="boardroom_id", type="integer"),
     *                      @OA\Property(property="user_id", type="integer"),
     *                 )
     *             },
     *              example={
     *                  "description": "New meeting!",
     *                  "starttime": 34934035,
     *                  "endtime": 34934036,
     *                  "boardroom_id": 1,
     *                  "user_id": 1
     *              }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="description", type="string", example="New description 33"),
     *                  @OA\Property(property="starttime", type="integer", example=34934035),
     *                  @OA\Property(property="endtime", type="integer", example=34934036),
     *                  @OA\Property(property="boardroom_id", type="integer", example=1),
     *                  @OA\Property(property="user_id", type="integer", example=1),
     *              )
     *         )
     *     )
     * )
     *
     * @param StoreEventRequest $request
     * @return EventResource
     */
    public function store(StoreEventRequest $request)
    {
        return new EventResource(Event::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/event/{event}",
     *     summary="Get an event",
     *     tags={"Event"},
     *     security={{"bearerAuth": ""}},
     *
     *      @OA\Parameter(
     *          description="Id of the event",
     *          in="path",
     *          name="event",
     *          required=true,
     *          example=1
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="description", type="string", example="New description 33"),
     *                  @OA\Property(property="starttime", type="integer", example=34934035),
     *                  @OA\Property(property="endtime", type="integer", example=34934036),
     *                  @OA\Property(property="boardroom_id", type="integer", example=1),
     *                  @OA\Property(property="user_id", type="integer", example=1),
     *              )
     *         )
     *     )
     * )
     *
     * @param Event $event
     * @return EventResource
     */
    public function show(Event $event)
    {
        return new EventResource($event);
    }

    /**
     *  @OA\Put(
     *     path="/api/v1/event/{event}",
     *     summary="Update the event",
     *     tags={"Event"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\Parameter(
     *          description="Id of the event",
     *          in="path",
     *          name="event",
     *          required=true,
     *          example=1
     *     ),
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                      @OA\Property(property="description", type="string"),
     *                      @OA\Property(property="starttime", type="integer"),
     *                      @OA\Property(property="endtime", type="integer"),
     *                      @OA\Property(property="boardroom_id", type="integer"),
     *                      @OA\Property(property="user_id", type="integer"),
     *                 )
     *             },
     *              example={
     *                  "description": "New meeting! (updated)",
     *                  "starttime": 34934035,
     *                  "endtime": 34934036,
     *                  "boardroom_id": 1,
     *                  "user_id": 1
     *              }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="description", type="string", example="New meeting! (updated)"),
     *                  @OA\Property(property="starttime", type="integer", example=34934035),
     *                  @OA\Property(property="endtime", type="integer", example=34934036),
     *                  @OA\Property(property="boardroom_id", type="integer", example=1),
     *                  @OA\Property(property="user_id", type="integer", example=1),
     *              )
     *         )
     *     )
     * )
     *
     * @param UpdateEventRequest $request
     * @param Event $event
     * @return EventResource
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->all());

        return new EventResource($event);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/event/{event}",
     *     summary="Delete the event",
     *     tags={"Event"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\Parameter(
     *          description="Id of the event",
     *          in="path",
     *          name="event",
     *          required=true,
     *          example=1
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="OK"
     *     )
     * )
     *
     * @param Event $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
