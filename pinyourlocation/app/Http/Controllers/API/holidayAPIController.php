<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateholidayAPIRequest;
use App\Http\Requests\API\UpdateholidayAPIRequest;
use App\Models\holiday;
use App\Repositories\holidayRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class holidayController
 * @package App\Http\Controllers\API
 */

class holidayAPIController extends InfyOmBaseController
{
    /** @var  holidayRepository */
    private $holidayRepository;

    public function __construct(holidayRepository $holidayRepo)
    {
        $this->holidayRepository = $holidayRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/holidays",
     *      summary="Get a listing of the holidays.",
     *      tags={"holiday"},
     *      description="Get all holidays",
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
     *                  @SWG\Items(ref="#/definitions/holiday")
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
        $this->holidayRepository->pushCriteria(new RequestCriteria($request));
        $this->holidayRepository->pushCriteria(new LimitOffsetCriteria($request));
        $holidays = $this->holidayRepository->all();

        return $this->sendResponse($holidays->toArray(), 'holidays retrieved successfully');
    }

    /**
     * @param CreateholidayAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/holidays",
     *      summary="Store a newly created holiday in storage",
     *      tags={"holiday"},
     *      description="Store holiday",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="holiday that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/holiday")
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
     *                  ref="#/definitions/holiday"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateholidayAPIRequest $request)
    {
        $input = $request->all();

        $holidays = $this->holidayRepository->create($input);

        return $this->sendResponse($holidays->toArray(), 'holiday saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/holidays/{id}",
     *      summary="Display the specified holiday",
     *      tags={"holiday"},
     *      description="Get holiday",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of holiday",
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
     *                  ref="#/definitions/holiday"
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
        /** @var holiday $holiday */
        $holiday = $this->holidayRepository->find($id);

        if (empty($holiday)) {
            return Response::json(ResponseUtil::makeError('holiday not found'), 404);
        }

        return $this->sendResponse($holiday->toArray(), 'holiday retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateholidayAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/holidays/{id}",
     *      summary="Update the specified holiday in storage",
     *      tags={"holiday"},
     *      description="Update holiday",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of holiday",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="holiday that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/holiday")
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
     *                  ref="#/definitions/holiday"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateholidayAPIRequest $request)
    {
        $input = $request->all();

        /** @var holiday $holiday */
        $holiday = $this->holidayRepository->find($id);

        if (empty($holiday)) {
            return Response::json(ResponseUtil::makeError('holiday not found'), 404);
        }

        $holiday = $this->holidayRepository->update($input, $id);

        return $this->sendResponse($holiday->toArray(), 'holiday updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/holidays/{id}",
     *      summary="Remove the specified holiday from storage",
     *      tags={"holiday"},
     *      description="Delete holiday",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of holiday",
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
        /** @var holiday $holiday */
        $holiday = $this->holidayRepository->find($id);

        if (empty($holiday)) {
            return Response::json(ResponseUtil::makeError('holiday not found'), 404);
        }

        $holiday->delete();

        return $this->sendResponse($id, 'holiday deleted successfully');
    }
}
