<?php

namespace Workdo\DrivingSchool\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Workdo\LandingPage\Entities\MarketplacePageSetting;


class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $module = 'DrivingSchool';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'DrivingSchool';
        $data['product_main_description'] = '<p>Facilitate seamless communication between students, instructors, and administrative staff with our collaborative tools. Our platform provides features for scheduling, messaging, and collaboration, ensuring clear and effective communication throughout the learning process. With Dash SaaS, you can foster a supportive learning environment and enhance the overall student experience at your driving school.
        </p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Driving School Management Simplified';
        $data['dedicated_theme_description'] = '<p>Effortlessly manage students, vehicles, classes, lessons, and invoices in one streamlined platform within Dash SaaS, enhancing efficiency and organization for your driving school operations.
        </p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Effortless Student Management","dedicated_theme_section_description": "Keep track of student records, schedules, and progress effortlessly within Dash SaaS. From enrollment to graduation, our platform allows you to maintain comprehensive student profiles, track attendance, and monitor performance throughout their learning journey. With centralized data management, you can streamline administrative tasks and focus on providing quality instruction to your students."
        },
        {"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Streamlined Vehicle Tracking","dedicated_theme_section_description": "Manage your fleet of vehicles seamlessly with our integrated tracking system. Track vehicle availability, maintenance schedules, and usage logs to ensure optimal performance and safety standards. Real-time updates enable you to effectively allocate resources and minimize downtime, keeping your driving school operations running smoothly."},
        {"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Efficient Class Management","dedicated_theme_section_description": "Organize and schedule classes efficiently using our intuitive tools. Create class schedules, assign instructors, and manage class rosters effortlessly. Our platform streamlines the scheduling process, ensuring that classes run smoothly according to your preferred curriculum. With centralized class management, you can optimize resource allocation and maximize instructional time."},
        {"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Personalized Lesson Planning","dedicated_theme_section_description": "Create customized lesson plans tailored to individual student needs with ease. Our platform provides tools for lesson scheduling, progress tracking, and feedback management, allowing instructors to deliver personalized instruction that maximizes student learning outcomes. With Dash SaaS, you can adapt lessons to individual learning styles, resulting in a more engaging and effective learning experience for your students."},
        {"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Seamless Invoicing and Billing","dedicated_theme_section_description": "Simplify the billing process with our integrated invoicing system. Generate invoices, track payments, and manage billing cycles seamlessly. Customizable invoice templates ensure accuracy and professionalism in your financial transactions. With Dash SaaS, you can streamline your billing processes and ensure timely payments, helping you maintain a healthy cash flow for your driving school."}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"DrivingSchool"},{"screenshots":"","screenshots_heading":"DrivingSchool"},{"screenshots":"","screenshots_heading":"DrivingSchool"},{"screenshots":"","screenshots_heading":"DrivingSchool"},{"screenshots":"","screenshots_heading":"DrivingSchool"}]';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach ($data as $key => $value) {
            if (!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', $module)->exists()) {
                MarketplacePageSetting::updateOrCreate(
                    [
                        'name' => $key,
                        'module' => $module

                    ],
                    [
                        'value' => $value
                    ]
                );
            }
        }
    }
}
