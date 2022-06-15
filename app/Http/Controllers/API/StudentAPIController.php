<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateStudentAPIRequest;
use App\Http\Requests\API\UpdateStudentAPIRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\StudentResource;
use Response;

/**
 * Class StudentController
 * @package App\Http\Controllers\API
 */

class StudentAPIController extends AppBaseController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/students",
     *      summary="Get a listing of the Students.",
     *      tags={"Student"},
     *      description="Get all Students",
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
     *                  @SWG\Items(ref="#/definitions/Student")
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
        $query = Student::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }

        $students = $query->get();

         return $this->sendResponse(
             StudentResource::collection($students),
             __('messages.retrieved', ['model' => __('models/students.plural')])
         );
    }

    /**
     * @param CreateStudentAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/students",
     *      summary="Store a newly created Student in storage",
     *      tags={"Student"},
     *      description="Store Student",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Student that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Student")
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
     *                  ref="#/definitions/Student"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateStudentAPIRequest $request)
    {
        $input = $request->all();

        /** @var Student $student */
        $student = Student::create($input);

        return $this->sendResponse(
             new StudentResource($student),
             __('messages.saved', ['model' => __('models/students.singular')])
        );
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/students/{id}",
     *      summary="Display the specified Student",
     *      tags={"Student"},
     *      description="Get Student",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Student",
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
     *                  ref="#/definitions/Student"
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
        /** @var Student $student */
        $student = Student::find($id);

        if (empty($student)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/students.singular')])
            );
        }

        return $this->sendResponse(
            new StudentResource($student),
            __('messages.retrieved', ['model' => __('models/students.singular')])
        );
    }

    /**
     * @param int $id
     * @param UpdateStudentAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/students/{id}",
     *      summary="Update the specified Student in storage",
     *      tags={"Student"},
     *      description="Update Student",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Student",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Student that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Student")
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
     *                  ref="#/definitions/Student"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateStudentAPIRequest $request)
    {
        /** @var Student $student */
        $student = Student::find($id);

        if (empty($student)) {
           return $this->sendError(
               __('messages.not_found', ['model' => __('models/students.singular')])
           );
        }

        $student->fill($request->all());
        $student->save();

        return $this->sendResponse(
             new StudentResource($student),
             __('messages.updated', ['model' => __('models/students.singular')])
        );
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/students/{id}",
     *      summary="Remove the specified Student from storage",
     *      tags={"Student"},
     *      description="Delete Student",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Student",
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
        /** @var Student $student */
        $student = Student::find($id);

        if (empty($student)) {
           return $this->sendError(
                 __('messages.not_found', ['model' => __('models/students.singular')])
           );
        }

        $student->delete();

         return $this->sendResponse(
             $id,
             __('messages.deleted', ['model' => __('models/students.singular')])
         );
    }
}
