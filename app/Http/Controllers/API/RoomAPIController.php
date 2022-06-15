<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRoomAPIRequest;
use App\Http\Requests\API\UpdateRoomAPIRequest;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\RoomResource;
use Response;

/**
 * Class RoomController
 * @package App\Http\Controllers\API
 */

class RoomAPIController extends AppBaseController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/rooms",
     *      summary="Get a listing of the Rooms.",
     *      tags={"Room"},
     *      description="Get all Rooms",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Room")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $query = Room::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }

        $rooms = $query->get();

         return $this->sendResponse(
             RoomResource::collection($rooms),
             __('messages.retrieved', ['model' => __('models/rooms.plural')])
         );
    }

    /**
     * @param CreateRoomAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/rooms",
     *      summary="Store a newly created Room in storage",
     *      tags={"Room"},
     *      description="Store Room",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Room that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Room")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Room"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateRoomAPIRequest $request)
    {
        $input = $request->all();

        /** @var Room $room */
        $room = Room::create($input);

        return $this->sendResponse(
             new RoomResource($room),
             __('messages.saved', ['model' => __('models/rooms.singular')])
        );
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/rooms/{id}",
     *      summary="Display the specified Room",
     *      tags={"Room"},
     *      description="Get Room",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Room",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Room"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Room $room */
        $room = Room::find($id);

        if (empty($room)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/rooms.singular')])
            );
        }

        return $this->sendResponse(
            new RoomResource($room),
            __('messages.retrieved', ['model' => __('models/rooms.singular')])
        );
    }

    /**
     * @param int $id
     * @param UpdateRoomAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/rooms/{id}",
     *      summary="Update the specified Room in storage",
     *      tags={"Room"},
     *      description="Update Room",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Room",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Room that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Room")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Room"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateRoomAPIRequest $request)
    {
        /** @var Room $room */
        $room = Room::find($id);

        if (empty($room)) {
           return $this->sendError(
               __('messages.not_found', ['model' => __('models/rooms.singular')])
           );
        }

        $room->fill($request->all());
        $room->save();

        return $this->sendResponse(
             new RoomResource($room),
             __('messages.updated', ['model' => __('models/rooms.singular')])
        );
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/rooms/{id}",
     *      summary="Remove the specified Room from storage",
     *      tags={"Room"},
     *      description="Delete Room",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Room",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Room $room */
        $room = Room::find($id);

        if (empty($room)) {
           return $this->sendError(
                 __('messages.not_found', ['model' => __('models/rooms.singular')])
           );
        }

        $room->delete();

         return $this->sendResponse(
             $id,
             __('messages.deleted', ['model' => __('models/rooms.singular')])
         );
    }
}
