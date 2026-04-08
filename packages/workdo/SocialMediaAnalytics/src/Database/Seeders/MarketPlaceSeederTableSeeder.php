<?php

namespace Workdo\SocialMediaAnalytics\Database\Seeders;

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
        $module = 'SocialMediaAnalytics';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'SocialMediaAnalytics';
        $data['product_main_description'] = '<p>The Social Media Analytics Add-On helps you bring your YouTube, Instagram, and Facebook data into one central dashboard. Once activated, simply input the required credentials for each platform to begin tracking vital performance metrics. Designed to give you clear, actionable data, this Add-On makes it easy to measure engagement, reach, and growth across channels—without jumping between tools.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'All-in-One Social Media Insights';
        $data['dedicated_theme_description'] = '<p>Track YouTube, Instagram, and Facebook performance in one dashboard with clear metrics and visuals to guide smarter content decisions.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Dive Deep Into YouTube Performance","dedicated_theme_section_description":"<p>Understand what drives your video success with detailed YouTube analytics. Get access to vital stats like subscribers, likes, dislikes, and views—alongside visual breakdowns that highlight top-performing videos and audience trends. From chart-based summaries to gender and country-wise distribution, the Add-On empowers you to turn views into value.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Maximize Your Instagram Engagement","dedicated_theme_section_description":"<p>Get a comprehensive snapshot of your Instagram performance with engagement-driven insights. From follower growth to story interactions, this dashboard gives you everything needed to understand your audience better. Easily spot patterns in your most engaging posts and refine your social strategy with data-backed clarity.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Unlock Powerful Facebook Metrics","dedicated_theme_section_description":"<p>Track how your Facebook page is performing in detail—from fan count to reactions, messages, and content types. Use powerful visual charts to review weekly and 28-day trends in clicks and impressions. Get a closer look at what’s driving audience action and shape your content around what your fans love most.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"SocialMediaAnalytics"},{"screenshots":"","screenshots_heading":"SocialMediaAnalytics"},{"screenshots":"","screenshots_heading":"SocialMediaAnalytics"},{"screenshots":"","screenshots_heading":"SocialMediaAnalytics"}]';
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
