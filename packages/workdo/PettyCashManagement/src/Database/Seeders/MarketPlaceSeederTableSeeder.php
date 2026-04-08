<?php

namespace Workdo\PettyCashManagement\Database\Seeders;

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
        $module = 'PettyCashManagement';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'PettyCashManagement';
        $data['product_main_description'] = '<p>Handling petty cash doesn’t have to be a tedious task. The Petty Cash Management module in Dash SaaS brings structure and ease to the process. By offering a centralized platform to record and monitor transactions, it helps businesses maintain order and transparency in their day-to-day cash dealings, making petty cash management stress-free and efficient.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Petty Cash Management</h2>';
        $data['dedicated_theme_description'] = '<p>Easily track and manage petty cash expenses with the Petty Cash Management add-on in Workdo Dash</p>';
        $data['dedicated_theme_sections'] = '[
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Clear Request and Approval Workflows",
                                                    "dedicated_theme_section_description": "<p>The module simplifies the way petty cash is requested and approved. Employees can submit their cash requirements, and managers can quickly review and authorize them within the system. This structured approach eliminates confusion, ensures proper allocation of funds, and fosters a clear understanding of how cash is distributed and used.<\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": null,
                                                        "description": null
                                                    }
                                                    }
                                                },
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Understand and Analyze Expenses",
                                                    "dedicated_theme_section_description": "<p>With detailed tracking tools, the module allows businesses to identify spending trends and patterns over time. Whether it’s monitoring departmental usage or pinpointing areas where costs can be reduced, the insights offered by the system help in planning budgets and making informed decisions about cash flow management.<\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": null,
                                                        "description": null
                                                    }
                                                    }
                                                },
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Adaptable to Your Business Operations",
                                                    "dedicated_theme_section_description": "<p>The Petty Cash Management module is designed to fit the unique needs of any business. It allows you to organize expenses into categories, set spending guidelines, and generate comprehensive reports. This level of flexibility ensures the module works the way your business operates, rather than forcing you to adapt to rigid processes.<\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": null,
                                                        "description": null
                                                    }
                                                    }
                                                },
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Promoting Responsibility and Accuracy",
                                                    "dedicated_theme_section_description": "<p>Every transaction within the module is logged with precise details, ensuring there’s no room for errors or mismanagement. By maintaining a clear record of who requested, approved, and used the funds, businesses can promote accountability at every level and reduce the chances of cash misuse or discrepancies.<\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": null,
                                                        "description": null
                                                    }
                                                    }
                                                }
                                            ]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"PettyCashManagement"},{"screenshots":"","screenshots_heading":"PettyCashManagement"},{"screenshots":"","screenshots_heading":"PettyCashManagement"},{"screenshots":"","screenshots_heading":"PettyCashManagement"},{"screenshots":"","screenshots_heading":"PettyCashManagement"}]';
        $data['addon_heading'] = '<h2>Why choose dedicated modules<b> for Your Business?</b></h2>';
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
