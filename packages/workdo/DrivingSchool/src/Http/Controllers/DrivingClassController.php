<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\DrivingSchool\Entities\DrivingClass;
use Illuminate\Support\Facades\Auth;
use Workdo\DrivingSchool\Entities\DrivingStudent;
use Workdo\DrivingSchool\Entities\DrivingVehicle;
use Illuminate\Support\Facades\Validator;
use Workdo\DrivingSchool\Events\CreateDrivingClass;
use Illuminate\Support\Carbon;
use Workdo\DrivingSchool\Entities\DrivingLesson;
use Illuminate\Support\Facades\Crypt;
use Workdo\DrivingSchool\DataTables\DrivingClassDatatable;
use Workdo\DrivingSchool\Events\UpdateDrivingClass;
use Workdo\DrivingSchool\Events\DestoryDrivingClass;

class DrivingClassController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DrivingClassDatatable $dataTable)
    {
        if(Auth::user()->isAbleTo('drivingclass manage')){
            return $dataTable->render('driving-school::class.index');
        }
        else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('drivingclass create')) {
            $student            = DrivingStudent::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            $users              = User::where('created_by', creatorId())->where('type', 'driving student')->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $vehicle            = DrivingVehicle::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('driving-school::class.create', compact('student', 'users', 'vehicle'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('drivingclass create')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'start_date_time' => 'required|date',
                'end_date_time' => 'required|date|after_or_equal:start_date_time',
                'vehicle_id' => 'required',
                'teacher_id' => 'required',
                'student_id' => 'required|array|min:1',
                'location' => 'required|string',
                'fees' => 'required',
                'schedule' => 'required|string|in:daily,weekly,monthly',
                'weeklyDays' => 'required_if:schedule,weekly|array|min:1',
                'monthlyDate' => 'required_if:schedule,monthly|date',
            ]);

            if ($validator->fails()) {
                return redirect()->route('driving-class.index')->with('error', $validator->errors()->first());
            }

            $class = new DrivingClass;
            $class->driving_class_id = $this->classNumber();
            $class->fill($request->except(['weeklyDays', 'additionalDates', 'monthlyDate']));
            if ($class->schedule === 'weekly') {

                $class->weekly_days = json_encode($request->input('weeklyDays'));
            } elseif ($class->schedule === 'monthly') {

                $monthlyDates = [
                    'main_date' => $request->input('monthlyDate'),
                    'additional_dates' => $request->input('additionalDates')
                ];

                $class->monthly_date = json_encode($monthlyDates);
            }
            $class->student_id = implode(',', $request->student_id);
            $class->workspace = getActiveWorkSpace();
            $class->created_by = Auth::user()->id;
            $class->save();
            event(new CreateDrivingClass($request, $class));

            $lessonStartDate = Carbon::parse($class->start_date_time);
            $lessonEndDate = Carbon::parse($class->end_date_time);
            $selectedDays = $request->input('weeklyDays', []);
            $additionalDates = $request->input('additionalDates', []);
            $additionalDates[] = $request->input('monthlyDate');
            $lessonCount = 1;

            if ($class->schedule == 'weekly') {
                $date = $lessonStartDate->copy();
                while ($date->lessThanOrEqualTo($lessonEndDate)) {
                    $currentDayOfWeek = $date->dayOfWeekIso;
                    if (in_array($currentDayOfWeek, $selectedDays)) {
                        DrivingLesson::createDrivingLesson($class, $date, $lessonCount);
                        $lessonCount++;
                    }
                    $date->addDay();
                }
            } elseif ($class->schedule == 'monthly') {
                foreach ($additionalDates as $additionalDate) {
                    $lessonDate = Carbon::parse($additionalDate);
                    if ($lessonDate->between($lessonStartDate, $lessonEndDate)) {
                        DrivingLesson::createDrivingLesson($class, $lessonDate, $lessonCount);
                        $lessonCount++;
                    }
                }
            } else {
                $date = $lessonStartDate->copy();
                while ($date->lessThanOrEqualTo($lessonEndDate)) {
                    DrivingLesson::createDrivingLesson($class, $date, $lessonCount);
                    $lessonCount++;
                    $date->addDay();
                }
            }

            return redirect()->back()->with('success', __('The class has been created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function classNumber()
    {
        $latest = DrivingClass::where('workspace', getActiveWorkSpace())->latest()->first();
        if (!$latest) {
            return 1;
        }
        return $latest->driving_class_id + 1;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (Auth::user()->isAbleTo('drivingclass show')) {
            try {
                $id = Crypt::decrypt($id);
                $class = DrivingClass::where('id', $id)
                    ->where('workspace', getActiveWorkSpace())
                    ->firstOrFail();

                $lessonCount = $class->lessons()->count();

                $lessons = DrivingLesson::where('class_id', $class->id)
                    ->where('workspace', getActiveWorkSpace())
                    ->get();

                return view('driving-school::class.show', compact('class', 'lessonCount', 'lessons'));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Class not found.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('drivingclass edit')) {
            try {
                $student = DrivingStudent::where('created_by', creatorId())
                    ->where('workspace', getActiveWorkSpace())
                    ->pluck('name', 'id');

                $users = User::where('created_by', creatorId())
                    ->where('type', 'driving student')
                    ->where('workspace_id', getActiveWorkSpace())
                    ->pluck('name', 'id');

                $class = DrivingClass::where('id', $id)
                    ->where('workspace', getActiveWorkSpace())
                    ->firstOrFail();

                $vehicle = DrivingVehicle::where('created_by', creatorId())
                    ->where('workspace', getActiveWorkSpace())
                    ->pluck('name', 'id');

                $student_id = explode(',', $class->student_id);
                $weekly_days = json_decode($class->weekly_days);
                $monthly_date = json_decode($class->monthly_date, true);
                $additional_dates = $monthly_date['additional_dates'] ?? null;
                $main_date = $monthly_date['main_date'] ?? null;

                return view('driving-school::class.edit', compact('student', 'users', 'class', 'vehicle', 'student_id', 'main_date', 'additional_dates', 'weekly_days'));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Class not found.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('drivingclass edit')) {

            $rules = [
                'name' => 'required',
                'start_date_time' => 'required|date',
                'end_date_time' => 'required|date|after_or_equal:start_date_time',
                'vehicle_id' => 'required',
                'teacher_id' => 'required',
                'student_id' => 'required',
                'location' => 'required|string',
                'fees' => 'required',
                'schedule' => 'required|string|in:daily,weekly,monthly',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->route('driving-class.index')->with('error', $messages->first());
            }

            $class = DrivingClass::find($id);
            $class->name                   = !empty($request->name) ? $request->name : null;
            $class->start_date_time        = !empty($request->start_date_time) ? $request->start_date_time : null;
            $class->end_date_time          = !empty($request->end_date_time) ? $request->end_date_time : null;
            $class->vehicle_id             = !empty($request->vehicle_id) ? $request->vehicle_id : null;
            $class->student_id             = !empty($request->student_id) ? implode(',', $request->student_id) : null;
            $class->teacher_id             = !empty($request->teacher_id) ? $request->teacher_id : null;
            $class->location               = !empty($request->location) ? $request->location : null;
            $class->fees                   = !empty($request->fees) ? $request->fees : null;
            $class->schedule               = !empty($request->schedule) ? $request->schedule : null;

            // Handle student field
            $class->student_id = !empty($request->student_id) ? implode(',', $request->student_id) : null;

            if ($class->schedule === 'weekly') {
                $class->weekly_days = json_encode($request->weeklyDays);
            } elseif ($class->schedule === 'monthly') {
                $monthlyDates = [
                    'main_date' => $request->input('monthlyDate'),
                    'additional_dates' => $request->input('additionalDates')
                ];
                $class->monthly_date = json_encode($monthlyDates);
            }
            $class->save();

            $oldlesson = DrivingLesson::where('class_id', $class->id)->delete();

            if ($class->schedule === 'weekly') {

                $class->weekly_days = json_encode($request->input('weeklyDays'));
            } elseif ($class->schedule === 'monthly') {

                $monthlyDates = [
                    'main_date' => $request->input('monthlyDate'),
                    'additional_dates' => $request->input('additionalDates')
                ];

                $class->monthly_date = json_encode($monthlyDates);
            }
            $class->workspace              = getActiveWorkSpace();
            $class->created_by             = Auth::user()->id;
            $class->save();

            $lessonStartDate = Carbon::parse($class->start_date_time);
            $lessonEndDate = Carbon::parse($class->end_date_time);
            $selectedDays = $request->input('weeklyDays', []);

            $additionalDate = $request->input('additionalDates', []);
            $additionalDate[] = $request->input('monthlyDate');
            $lessonCount = 1;

            if ($class->schedule == 'weekly') {
                $date = $lessonStartDate->copy();
                while ($date->lessThanOrEqualTo($lessonEndDate)) {
                    $currentDayOfWeek = $date->dayOfWeekIso;
                    if (in_array($currentDayOfWeek, $selectedDays)) {
                        DrivingLesson::createDrivingLesson($class, $date, $lessonCount);
                        $lessonCount++;
                    }
                    $date->addDay();
                }
            } elseif ($class->schedule == 'monthly') {
                foreach ($additionalDate as $additionalDates) {
                    $lessonDate = Carbon::parse($additionalDates);
                    if ($lessonDate->between($lessonStartDate, $lessonEndDate)) {
                        DrivingLesson::createDrivingLesson($class, $lessonDate, $lessonCount);
                        $lessonCount++;
                    }
                }
            } else {
                $date = $lessonStartDate->copy();
                while ($date->lessThanOrEqualTo($lessonEndDate)) {
                    DrivingLesson::createDrivingLesson($class, $date, $lessonCount);
                    $lessonCount++;
                    $date->addDay();
                }
            }
            event(new UpdateDrivingClass($request, $class));

            DrivingLesson::updateLessons($class, $request);

            return redirect()->back()->with('success', __('The class details are updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $class = DrivingClass::where('id', $id)->first();
        if (Auth::user()->isAbleTo('drivingclass delete')) {
            if ($class->workspace == getActiveWorkSpace()) {

                event(new DestoryDrivingClass($class));

                $class->lessons()->delete();
                $class->delete();
                return redirect()->route('driving-class.index')->with('success', __('The class has been deleted'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
