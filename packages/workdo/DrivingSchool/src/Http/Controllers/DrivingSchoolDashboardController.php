<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Workdo\DrivingSchool\Entities\DrivingClass;
use Workdo\DrivingSchool\Entities\DrivingLesson;
use Workdo\DrivingSchool\Entities\DrivingStudent;
use Workdo\DrivingSchool\Entities\DrivingVehicle;

class DrivingSchoolDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct()
    {
        if (module_is_active('GoogleAuthentication')) {
            $this->middleware('2fa');
        }
    }
    public function index()
    {
        $totalStudent = null;

        if (Auth::user()->type == 'driving student') {
            $student = DrivingStudent::where('user_id', Auth::user()->id)->where('workspace', getActiveWorkSpace())->first();

            $totalClass = 0;
            $organizedMonthlyCounts = [];
            $drivingStatusData = [];
            $statusNames = [
                0 => 'Draft',
                1 => 'Start',
                2 => 'Complete',
                3 => 'Cancel',
            ];
            $drivingStatusCounts = 0;
            $events = [];
            $current_month_class = [];

            if($student != null)
            {
                $totalClass = DrivingClass::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->where('student_id', $student->id)
                ->count();

                $drivingStatusCounts = DrivingLesson::select('status', DB::raw('COUNT(*) as count'))
                ->where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->where("student_id", $student->id)
                ->whereIn('status', [0, 1, 2, 3])
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
                $drivingStatusCounts = array_replace(array_fill_keys([0, 1, 2, 3], 0), $drivingStatusCounts);

                $totalCount = array_sum($drivingStatusCounts);
    
                foreach ($drivingStatusCounts as $status => $count) {
                    $percentage = ($totalCount > 0) ? intval(($count / $totalCount) * 100) : 0;
                    $drivingStatusData[$statusNames[$status]] = $percentage;
                }
    
                $remainingPercentage = 100 - array_sum($drivingStatusData);
                arsort($drivingStatusData);
                reset($drivingStatusData);
                $drivingStatusData[key($drivingStatusData)] += $remainingPercentage;
                $driving_draft = $drivingStatusCounts[0] ?? 0;
                $driving_start = $drivingStatusCounts[1] ?? 0;
                $driving_complete = $drivingStatusCounts[2] ?? 0;
                $driving_cancel = $drivingStatusCounts[3] ?? 0;

                $monthlyClassCounts = DrivingClass::selectRaw("COUNT(*) as count, schedule, MONTH(start_date_time) as start_month, MONTH(end_date_time) as end_month")
                ->where('created_by', creatorId())
                ->where('student_id', $student->id)
                ->where('workspace', getActiveWorkSpace())
                ->groupBy('schedule', 'start_month', 'end_month')
                ->get();


                foreach ($monthlyClassCounts as $classCount) {
                    $schedule = $classCount->schedule;
                    $startMonth = $classCount->start_month;
                    $endMonth = $classCount->end_month;
                    $count = $classCount->count;
                    for ($month = $startMonth; $month <= $endMonth; $month++) {
                        $organizedMonthlyCounts[$schedule][$month] = isset($organizedMonthlyCounts[$schedule][$month])
                            ? $organizedMonthlyCounts[$schedule][$month] + $count
                            : $count;
                    }
                }

                $class = DrivingClass::where('student_id', $student->id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace());

                $driving_classes = $class->get();
    
                foreach ($driving_classes as $class) {
                    $events[] = [
                        'title' => $class->name,
                        'start' => date('Y-m-d', strtotime($class->start_date_time)),
                        'end' => date('Y-m-d', strtotime($class->end_date_time)),
                        'className' => 'event-danger'
                    ];
                }
    
                $current_month_class = DrivingClass::whereMonth('start_date_time', date('m'))
                ->whereYear('start_date_time', date('Y'))
                ->where('student_id', $student->id)
                ->where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->orderBy('start_date_time', 'ASC')
                ->get();
            }
            $totalStudent = DrivingStudent::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->where('user_id', Auth::user()->id)
                ->count();

            $colors = [
                0 => '#3498db',
                1 => '#FFA21D',
                2 => '#6FD943',
                3 => '#FF3A6E',
            ];   


            $arrProcessClass = [
                'text-success',
                'text-primary',
                'text-danger',
            ];

            return view('driving-school::dashboard.index', compact(
                'totalStudent',
                'totalClass',
                'organizedMonthlyCounts',
                'drivingStatusData',
                'statusNames',
                'arrProcessClass',
                'drivingStatusCounts',
                'colors',
                'events',
                'current_month_class'
            ));

        } elseif (
            Auth::user()->isAbleTo('drivingschool dashboard manage') ||
            Auth::user()->type == 'company' ||
            Auth::user()->type == 'staff'
        ) {
            $totalStudent = DrivingStudent::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->count();

            $totalTeacher = User::where('created_by', creatorId())
                ->where('type', 'staff')
                ->where('workspace_id', getActiveWorkSpace())
                ->count();

            $totalClass = DrivingClass::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->count();

            $totalVehicle = DrivingVehicle::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->count();

            $colors = [
                0 => '#3498db',
                1 => '#FFA21D',
                2 => '#6FD943',
                3 => '#FF3A6E',
            ];

            $drivingStatusCounts = DrivingLesson::select('status', DB::raw('COUNT(*) as count'))
                ->where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->whereIn('status', [0, 1, 2, 3])
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $drivingStatusCounts = array_replace(array_fill_keys([0, 1, 2, 3], 0), $drivingStatusCounts);

            $totalCount = array_sum($drivingStatusCounts);

            $statusNames = [
                0 => 'Draft',
                1 => 'Start',
                2 => 'Complete',
                3 => 'Cancel',
            ];

            $drivingStatusData = [];
            foreach ($drivingStatusCounts as $status => $count) {
                $percentage = ($totalCount > 0) ? intval(($count / $totalCount) * 100) : 0;
                $drivingStatusData[$statusNames[$status]] = $percentage;
            }

            $remainingPercentage = 100 - array_sum($drivingStatusData);
            arsort($drivingStatusData);
            reset($drivingStatusData);
            $drivingStatusData[key($drivingStatusData)] += $remainingPercentage;
            $driving_draft = $drivingStatusCounts[0] ?? 0;
            $driving_start = $drivingStatusCounts[1] ?? 0;
            $driving_complete = $drivingStatusCounts[2] ?? 0;
            $driving_cancel = $drivingStatusCounts[3] ?? 0;

            $arrProcessClass = [
                'text-success',
                'text-primary',
                'text-danger',
            ];

            $monthlyClassCounts = DrivingClass::selectRaw("COUNT(*) as count, schedule, MONTH(start_date_time) as start_month, MONTH(end_date_time) as end_month")
                ->where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->groupBy('schedule', 'start_month', 'end_month')
                ->get();

            $organizedMonthlyCounts = [];

            foreach ($monthlyClassCounts as $classCount) {
                $schedule = $classCount->schedule;
                $startMonth = $classCount->start_month;
                $endMonth = $classCount->end_month;
                $count = $classCount->count;
                for ($month = $startMonth; $month <= $endMonth; $month++) {
                    $organizedMonthlyCounts[$schedule][$month] = isset($organizedMonthlyCounts[$schedule][$month])
                        ? $organizedMonthlyCounts[$schedule][$month] + $count
                        : $count;
                }
            }

            $class = DrivingClass::where('created_by', creatorId())->where('workspace', getActiveWorkSpace());

            $driving_classes = $class->get();

            $events = [];
            foreach ($driving_classes as $class) {
                $events[] = [
                    'title' => $class->name,
                    'start' => date('Y-m-d', strtotime($class->start_date_time)),
                    'end' => date('Y-m-d', strtotime($class->end_date_time)),
                    'className' => 'event-danger'
                ];
            }

            $current_month_class = DrivingClass::whereMonth('start_date_time', date('m'))
                ->whereYear('start_date_time', date('Y'))
                ->where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->orderBy('start_date_time', 'ASC')
                ->get();

            return view('driving-school::dashboard.index', compact(
                'totalStudent',
                'totalClass',
                'organizedMonthlyCounts',
                'totalVehicle',
                'drivingStatusData',
                'statusNames',
                'totalTeacher',
                'arrProcessClass',
                'drivingStatusCounts',
                'colors',
                'events',
                'current_month_class'
            ));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('driving-school::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('driving-school::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('driving-school::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
