<?php

namespace Workdo\BulkSMS\Database\Seeders;

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
        $module = 'BulkSMS';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'BulkSMS';
        $data['product_main_description'] = '<p>The Bulk SMS Add-On is a simple yet powerful communication tool that allows you to send instant text messages directly from within your system. Designed to improve internal and external communication, it enables businesses to quickly reach employees, clients, or entire contact groups without the need for external platforms.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Configure Your SMS Gateway with Ease';
        $data['dedicated_theme_description'] = '<p>The Bulk SMS Add-On makes it incredibly easy to connect your system with your SMS service. With just a few details like username and password, you`re ready to start sending messages directly from your platform.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Build and Manage Your Contact Base Effortlessly","dedicated_theme_section_description":"<p>Keeping your contact list up to date is crucial for effective communication, and this add-on makes it simple. Add individuals with full details or import entire lists in a few clicks. Whether you`re targeting a small group or a broad audience, your contact data stays organized, accessible, and ready whenever you need to send a message.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Create Targeted Groups for Smarter Messaging","dedicated_theme_section_description":"<p>Group messaging has never been easier. You can create and manage contact groups based on your communication needs, perfect for departments, client segments, or events. Instead of sending messages one by one, group messaging helps you reach the right audience quickly and consistently, saving time and boosting productivity.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Send Powerful Messages That Get Delivered","dedicated_theme_section_description":"<p>From urgent alerts to regular updates, the Bulk SMS Add-On helps you get your message across instantly. Send to one person or hundreds with the same ease. Messages are sent reliably, tracked automatically, and organized neatly for future reference. It`s fast, effective, and built to keep your communication on point.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"BulkSMS"},{"screenshots":"","screenshots_heading":"BulkSMS"},{"screenshots":"","screenshots_heading":"BulkSMS"},{"screenshots":"","screenshots_heading":"BulkSMS"}]';
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
