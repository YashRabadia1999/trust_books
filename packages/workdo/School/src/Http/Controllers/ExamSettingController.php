<?php

namespace Workdo\School\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Workdo\School\Entities\ExamSetting;

class ExamSettingController extends Controller
{
    /**
     * Show the settings form
     */
    public function index()
    {
        $setting = ExamSetting::first();
        return view('school::exam.settings', compact('setting'));
    }

    /**
     * Save or update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'assignment_percentage' => 'required|numeric|min:0|max:100',
            'exam_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $setting = ExamSetting::first();

        if(!$setting){
            $setting = new ExamSetting();
        }

        $setting->assignment_percentage = $request->assignment_percentage;
        $setting->exam_percentage = $request->exam_percentage;
        $setting->save();

        return redirect()->back()->with('success', 'Exam settings updated successfully.');
    }
}
