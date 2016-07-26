<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateholidayRequest;
use App\Http\Requests\UpdateholidayRequest;
use App\Repositories\holidayRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class holidayController extends InfyOmBaseController
{
    /** @var  holidayRepository */
    private $holidayRepository;

    public function __construct(holidayRepository $holidayRepo)
    {
        $this->holidayRepository = $holidayRepo;
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the holiday.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->holidayRepository->pushCriteria(new RequestCriteria($request));
        $holidays = $this->holidayRepository->all();

        return view('holidays.index')
            ->with('holidays', $holidays);
    }

    /**
     * Show the form for creating a new holiday.
     *
     * @return Response
     */
    public function create()
    {
        return view('holidays.create');
    }

    /**
     * Store a newly created holiday in storage.
     *
     * @param CreateholidayRequest $request
     *
     * @return Response
     */
    public function store(CreateholidayRequest $request)
    {
        $input = $request->all();

        $holiday = $this->holidayRepository->create($input);

        Flash::success('holiday saved successfully.');

        return redirect(route('holidays.index'));
    }

    /**
     * Display the specified holiday.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $holiday = $this->holidayRepository->findWithoutFail($id);

        if (empty($holiday)) {
            Flash::error('holiday not found');

            return redirect(route('holidays.index'));
        }

        return view('holidays.show')->with('holiday', $holiday);
    }

    /**
     * Show the form for editing the specified holiday.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $holiday = $this->holidayRepository->findWithoutFail($id);

        if (empty($holiday)) {
            Flash::error('holiday not found');

            return redirect(route('holidays.index'));
        }

        return view('holidays.edit')->with('holiday', $holiday);
    }

    /**
     * Update the specified holiday in storage.
     *
     * @param  int              $id
     * @param UpdateholidayRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateholidayRequest $request)
    {
        $holiday = $this->holidayRepository->findWithoutFail($id);

        if (empty($holiday)) {
            Flash::error('holiday not found');

            return redirect(route('holidays.index'));
        }

        $holiday = $this->holidayRepository->update($request->all(), $id);

        Flash::success('holiday updated successfully.');

        return redirect(route('holidays.index'));
    }

    /**
     * Remove the specified holiday from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $holiday = $this->holidayRepository->findWithoutFail($id);

        if (empty($holiday)) {
            Flash::error('holiday not found');

            return redirect(route('holidays.index'));
        }

        $this->holidayRepository->delete($id);

        Flash::success('holiday deleted successfully.');

        return redirect(route('holidays.index'));
    }
}
