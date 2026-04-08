<?php

namespace Workdo\School\Database\Seeders;

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
        $module = 'School';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'School Management';
        $data['product_main_description'] = '<p>Dash SaaS introduces a robust School Management Module designed to simplify and enhance the administrative processes within educational institutions. This comprehensive module encompasses various facets, providing a seamless experience for administrators, teachers, students, and parents alike.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Efficient School Management with Dash SaaS Module';
        $data['dedicated_theme_description'] = '<p>Unlock efficiency in education with Dash SaaS. Manage admissions, enable personalized logins, oversee classes, and streamline user administration – all in one place!</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Admission Page: Effortless Student Record Management","dedicated_theme_section_description":"<p>The Admission Page serves as the gateway to student record management. Administrators can easily navigate through a list of admissions, each accompanied by a convenient button for converting admissions into student profiles. This streamlined feature ensures a smooth transition from admission to student record management, optimizing the overall administrative workflow.</p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"User Logins: Personalized Access for Every Role","dedicated_theme_section_description":"<p>Dash SaaS recognizes the diverse roles within an educational setting, introducing three distinct logins - Student Login, Teacher Login, and Parent Login. Each login type is tailored to provide a personalized experience, granting relevant access and information to students, teachers, and parents. This ensures that users can seamlessly engage with the platform based on their specific roles and responsibilities.</p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Class Management: Comprehensive Academic Oversight","dedicated_theme_section_description":"<p>The Class Management section empowers administrators with a comprehensive view of academic structures. From the number of classes to subjects, class timetables, and teacher timetables, this section serves as a centralized hub for overseeing various academic aspects. The flexibility to edit or delete entries provides administrators with precise control over the academic framework, allowing for real-time adjustments to meet evolving needs.</p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"School"},{"screenshots":"","screenshots_heading":"School"},{"screenshots":"","screenshots_heading":"School"},{"screenshots":"","screenshots_heading":"School"}]';
        $data['addon_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', $module)->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => $module

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
