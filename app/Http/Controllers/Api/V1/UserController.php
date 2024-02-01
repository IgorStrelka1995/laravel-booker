<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="List of all users",
     *     tags={"User"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="email", type="string"),
     *              )),
     *              example={
     *                  "data": {{
     *                      "id": "1",
     *                      "name": "John",
     *                      "email": "john@mail.com"
     *                  },
     *                  {
     *                      "id": "2",
     *                      "name": "Tom",
     *                      "email": "tom@mail.com"
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
        return UserResource::collection(User::paginate());
    }

    /**
     *  @OA\Post(
     *     path="/api/v1/user",
     *     summary="Creating a new user.",
     *     tags={"User"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                      @OA\Property(property="name", type="string"),
     *                      @OA\Property(property="email", type="string"),
     *                      @OA\Property(property="password", type="string"),
     *                 )
     *             },
     *              example= {
     *                      "name": "Peter",
     *                      "email": "peter@mail.com",
     *                      "password": "password"
     *                  }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="name", type="string", example="Peter"),
     *                  @OA\Property(property="email", type="string", example="peter@mail.com"),
     *              ),
     *         )
     *     )
     * )
     *
     * @param StoreUserRequest $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->all();

        $user = User::create($data);

        return UserResource::make($user);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/{user}",
     *     summary="Get an user",
     *     tags={"User"},
     *     security={{"bearerAuth": ""}},
     *
     *      @OA\Parameter(
     *          description="Id of the user",
     *          in="path",
     *          name="user",
     *          required=true,
     *          example=1
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="name", type="string", example="Peter"),
     *                  @OA\Property(property="email", type="string", example="peter@mail.com"),
     *              ),
     *         )
     *     )
     * )
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return UserResource::make($user);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user/{user}",
     *     summary="Update user",
     *     tags={"User"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\Parameter(
     *          description="Id of the user",
     *          in="path",
     *          name="user",
     *          required=true,
     *          example=1
     *     ),
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                      @OA\Property(property="name", type="string", example="Jack"),
     *                      @OA\Property(property="email", type="string", example="jack@mail.com")
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
     *                  @OA\Property(property="name", type="string", example="Jack"),
     *                  @OA\Property(property="email", type="string", example="jack@mail.com"),
     *              ),
     *         )
     *     )
     * )
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->all();

        $user->update($data);

        return UserResource::make($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user/{user}",
     *     summary="Delete the user",
     *     tags={"User"},
     *     security={{"bearerAuth": ""}},
     *
     *     @OA\Parameter(
     *          description="Id of the user",
     *          in="path",
     *          name="user",
     *          required=true,
     *          example=1
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="OK"
     *     )
     * )
     *
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
