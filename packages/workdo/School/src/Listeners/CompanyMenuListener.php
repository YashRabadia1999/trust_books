<?php

namespace Workdo\School\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'School';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('School Dashboard'),
            'icon' => '',
            'name' => 'school',
            'parent' => 'dashboard',
            'order' => 180,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school.dashboard',
            'module' => $module,
            'permission' => 'school_dashboard manage'
        ]);
        // $menu->add([
        //     'category' => 'Education',
        //     'title' => __('School & Institute'),
        //     'icon' => 'ti ti-notebook',
        //     'name' => 'schoolmanagement',
        //     'parent' => null,
        //     'order' => 655,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => '',
        //     'module' => $module,
        //     'permission' => 'school_management manage'
        // ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Admission'),
            'icon' => '',
            'name' => 'admission',
            'parent' => 'schoolmanagement',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'admission.index',
            'module' => $module,
            'permission' => 'school_admission manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Class'),
            'icon' => '',
            'name' => 'class',
            'parent' => 'schoolmanagement',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'school_class manage'
        ]);
        
        $menu->add([
            'category'   => 'Education',
            'title'      => __('Academic Year'),
            'icon'       => 'ti ti-calendar',
            'name'       => 'academic-year',
            'parent'     => 'class',   
            'order'      => 30,                   
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'school.academic-year.index',
            'module'     => $module,
            'permission' => [
                'school_academic_year manage',
                'school_academic_year create',
                'school_academic_year edit',
                'school_academic_year delete'
                ]
        ]);

        $menu->add([
            'category'   => 'Education',
            'title'      => __('Term'),
            'icon'       => 'ti ti-calendar-event',
            'name'       => 'term',
            'parent'     => 'class', 
            'order'      => 35,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'school.term.index',
            'module'     => $module,
            'permission' => [
                'school_term manage',
                'school_term create',
                'school_term edit',
                'school_term delete'
                ]
        ]);

        $menu->add([
            'category'   => 'Education',
            'title'      => __('Assignment'),
            'icon'       => 'ti ti-assignment',
            'name'       => 'assignment',
            'parent'     => 'class', 
            'order'      => 40,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'school.assignment.index',
            'module'     => $module,
            'permission' => [
                'school_assignment manage',
                'school_assignment create',
                'school_assignment edit',
                'school_assignment delete'
                ]
        ]);

       
       
        $menu->add([
                    'category' => 'Education',
                    'title' => __('Exam'),
                    'icon' => 'fa fa-file-text', 
                    'name' => 'exam',
                    'parent' => 'class',   
                    'order' => 45,
                    'ignore_if' => [],
                    'depend_on' => [],
                    'route' => 'school.exam.index', 
                    'module' => $module,
                    'permission'=>['school_exam manage',
                                'school_exam create',
                                'school_exam edit',
                                'school_exam delete'
                                ]
                ]);


        $menu->add([
            'category' => 'Education',
            'title' => __('Exam Settings'),
            'icon' => '',
            'name' => 'exam-settings',
            'parent' => 'class',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school.exam.settings.index',
            'module' => $module,
            'permission' => 'school_exam manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Class'),
            'icon' => '',
            'name' => 'classes',
            'parent' => 'class',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'classroom.index',
            'module' => $module,
            'permission' => 'school_classroom manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Subject'),
            'icon' => '',
            'name' => 'subject',
            'parent' => 'class',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'subject.index',
            'module' => $module,
            'permission' => 'school_subject manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Class Timetable'),
            'icon' => '',
            'name' => 'timetable',
            'parent' => 'class',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'timetable.index',
            'module' => $module,
            'permission' => 'school_timetable manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Teacher Timetable'),
            'icon' => '',
            'name' => 'teacher-timetable',
            'parent' => 'class',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'teacher-timetable.index',
            'module' => $module,
            'permission' => 'school_teachertimetable manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Students'),
            'icon' => '',
            'name' => 'student',
            'parent' => 'schoolmanagement',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-student.index',
            'module' => $module,
            'permission' => 'school_student manage'
        ]);
        if (!in_array('Hrm', $event->menu->modules)) {
            $menu->add([
            'category' => 'Education',
                'title' => __('Teacher'),
                'icon' => '',
                'name' => 'teacher',
                'parent' => 'schoolmanagement',
                'order' => 25,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'schoolemployee.index',
                'module' => $module,
                'permission' => 'school_employee manage'
            ]);
        }
        $menu->add([
            'category' => 'Education',
            'title' => __('Parents'),
            'icon' => '',
            'name' => 'parent',
            'parent' => 'schoolmanagement',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-parent.index',
            'module' => $module,
            'permission' => 'school_parent manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Fee Management'),
            'icon' => '',
            'name' => 'fees-management',
            'parent' => 'schoolmanagement',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'school_fee manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Fees'),
            'icon' => '',
            'name' => 'fees',
            'parent' => 'fees-management',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-fees.index',
            'module' => $module,
            'permission' => 'school_fee manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Fee Structure'),
            'icon' => '',
            'name' => 'fee-structure',
            'parent' => 'fees-management',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-fee-structure.index',
            'module' => $module,
            'permission' => 'school_fees_structure manage'
        ]);
         $menu->add([
            'category' => 'Education',
            'title' => __('Fee Setup'),
            'icon' => '',
            'name' => 'fee-setup',
            'parent' => 'fees-management',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-fee-setup.index',
            'module' => $module,
            'permission' => 'school_fee_setup manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Home Work'),
            'icon' => '',
            'name' => 'homework',
            'parent' => 'schoolmanagement',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'school_homework manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Home Work'),
            'icon' => '',
            'name' => 'schoolhomework',
            'parent' => 'homework',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-homework.index',
            'module' => $module,
            'permission' => 'school_homework manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('View Home-Work'),
            'icon' => '',
            'name' => 'viewhomework',
            'parent' => 'homework',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'viewhomework',
            'module' => $module,
            'permission' => 'school_viewhomework manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Library'),
            'icon' => '',
            'name' => 'library',
            'parent' => 'schoolmanagement',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'library_books manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Books'),
            'icon' => '',
            'name' => 'books',
            'parent' => 'library',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'library-books.index',
            'module' => $module,
            'permission' => 'library_books manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Book Issues'),
            'icon' => '',
            'name' => 'book-issues',
            'parent' => 'library',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'library-books-issue.index',
            'module' => $module,
            'permission' => 'library_books_issue manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Transport'),
            'icon' => '',
            'name' => 'transport',
            'parent' => 'schoolmanagement',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'school_bus manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Buses'),
            'icon' => '',
            'name' => 'buses',
            'parent' => 'transport',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-bus.index',
            'module' => $module,
            'permission' => 'school_bus manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Routes'),
            'icon' => '',
            'name' => 'routes',
            'parent' => 'transport',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-transport-routes.index',
            'module' => $module,
            'permission' => 'school_transport_route manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Transport Fees'),
            'icon' => '',
            'name' => 'transport-fees',
            'parent' => 'transport',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-transport-fees.index',
            'module' => $module,
            'permission' => 'school_transport_fees manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Alumni'),
            'icon' => '',
            'name' => 'alumni',
            'parent' => 'schoolmanagement',
            'order' => 55,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-alumini.index',
            'module' => $module,
            'permission' => 'school_alumni manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Events'),
            'icon' => '',
            'name' => 'events',
            'parent' => 'schoolmanagement',
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-event.index',
            'module' => $module,
            'permission' => 'school_event manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Hostel Management'),
            'icon' => '',
            'name' => 'hostel-management',
            'parent' => 'schoolmanagement',
            'order' => 65,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'school_hostel manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Hostels'),
            'icon' => '',
            'name' => 'hostel',
            'parent' => 'hostel-management',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-hostel.index',
            'module' => $module,
            'permission' => 'school_hostel manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Rooms'),
            'icon' => '',
            'name' => 'rooms',
            'parent' => 'hostel-management',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-room.index',
            'module' => $module,
            'permission' => 'school_room manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Hostel Students'),
            'icon' => '',
            'name' => 'hostel-students',
            'parent' => 'hostel-management',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'hostel-student.index',
            'module' => $module,
            'permission' => 'school_hostel_student manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Online Assessment'),
            'icon' => '',
            'name' => 'online-assessment',
            'parent' => 'schoolmanagement',
            'order' => 70,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'school_assessment manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Assessments'),
            'icon' => '',
            'name' => 'assessment',
            'parent' => 'online-assessment',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-assessment.index',
            'module' => $module,
            'permission' => 'school_assessment manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Assessment Results'),
            'icon' => '',
            'name' => 'assessment-result',
            'parent' => 'online-assessment',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-assessment-result.index',
            'module' => $module,
            'permission' => 'school_assessment_result manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Noticeboard'),
            'icon' => '',
            'name' => 'moticeboard',
            'parent' => 'schoolmanagement',
            'order' => 75,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-notice.index',
            'module' => $module,
            'permission' => 'school_notice manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Health Records'),
            'icon' => '',
            'name' => 'health-recoreds',
            'parent' => 'schoolmanagement',
            'order' => 80,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-health-record.index',
            'module' => $module,
            'permission' => 'school_health_record manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Meetings'),
            'icon' => '',
            'name' => 'meetings',
            'parent' => 'schoolmanagement',
            'order' => 85,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-meeting.index',
            'module' => $module,
            'permission' => 'school_meeting manage'
        ]);
        if (!in_array('Hrm', $event->menu->modules)) {
            $menu->add([
            'category' => 'Education',
                'title' => __('System Setup'),
                'icon' => '',
                'name' => 'system-setup',
                'parent' => 'schoolmanagement',
                'order' => 90,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'schoolbranches.index',
                'module' => $module,
                'permission' => 'school_branch manage'
            ]);
        }
        $menu->add([
            'category' => 'Education',
            'title' => __('Attendance'),
            'icon' => '',
            'name' => 'schoolattendance',
            'parent' => 'schoolmanagement',
            'order' => 95,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'school_attendance manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Mark Attendance'),
            'icon' => '',
            'name' => 'markattendance',
            'parent' => 'schoolattendance',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'school-attendance.index',
            'module' => $module,
            'permission' => 'school_attendance manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Bulk Attendance'),
            'icon' => '',
            'name' => 'schoolbulkattendance',
            'parent' => 'schoolattendance',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'student.bulkattendance',
            'module' => $module,
            'permission' => 'school_bulkattendance manage'
        ]);
    }
}
