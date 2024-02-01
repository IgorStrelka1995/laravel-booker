<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoardroomRequest;
use App\Http\Requests\UpdateBoardroomRequest;
use App\Http\Resources\V1\BoardroomCollection;
use App\Http\Resources\V1\BoardroomResource;
use App\Models\Boardroom;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class BoardroomController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Boardroom::class, 'boardroom');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/boardroom",
     *     summary="List of all boardrooms",
     *     tags={"Boardroom"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="events",
     *         required=false,
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="name", type="string", example="Boardroom 1"),
     *                  @OA\Property(property="active", type="integer", example="1"),
     *              )),
     *         )
     *     )
     * )
     */
    public function index()
    {
        $boardrooms = QueryBuilder::for(Boardroom::class)
            ->allowedIncludes('events')
            ->scopes('active')
            ->get()
        ;

        return BoardroomResource::collection($boardrooms);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/boardroom",
     *     summary="Creating a new boardroom.",
     *     tags={"Boardroom"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                      @OA\Property(property="name", type="string"),
     *                      @OA\Property(property="active", type="boolean")
     *                 )
     *             },
     *              example=
     *                  {
     *                      "name": "Boardroom 1",
     *                      "active": true
     *                  }
     *
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="name", type="string", example="Boardroom 1"),
     *                  @OA\Property(property="active", type="boolean", example=true),
     *              ),
     *         )
     *     )
     * )
     *
     * @param StoreBoardroomRequest $request
     * @return BoardroomResource
     */
    public function store(StoreBoardroomRequest $request)
    {
        $data = $request->all();

        $boardroom = Boardroom::create($data);

        return BoardroomResource::make($boardroom);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/boardroom/{boardroom}",
     *     summary="Update boardroom",
     *     tags={"Boardroom"},
     *     security={{"bearerAuth": ""}},

     *     @OA\Parameter(
     *          description="Id of the boardroom",
     *          in="path",
     *          name="boardroom",
     *          required=true,
     *          example=1
     *     ),
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                      @OA\Property(property="name", type="string", example="Boardroom 2"),
     *                      @OA\Property(property="active", type="boolean", example=false)
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="name", type="string", example="Boardroom 2"),
     *                  @OA\Property(property="active", type="boolean", example=false),
     *              ),
     *         )
     *     )
     * )
     *
     * @param UpdateBoardroomRequest $request
     * @param Boardroom $boardroom
     * @return BoardroomResource
     */
    public function update(UpdateBoardroomRequest $request, Boardroom $boardroom)
    {
        $data = $request->all();

        $boardroom->update($data);

        return BoardroomResource::make($boardroom);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/boardroom/{boardroom}",
     *     summary="Delete the boardroom",
     *     tags={"Boardroom"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\Parameter(
     *          description="Id of the boardroom",
     *          in="path",
     *          name="boardroom",
     *          required=true,
     *          example=1
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="OK"
     *     )
     * )
     *
     * @param Boardroom $boardroom
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(Boardroom $boardroom)
    {
        $boardroom->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
