<?php

namespace Workdo\PetCare\Database\Seeders;

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
        $module = 'PetCare';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'PetCare';
        $data['product_main_description'] = '<p>This Pet Care Add-On gives you everything you need to run your business smoothly. Easily manage appointments, adoptions, services, and payments all in one place. Keep track of customers, pets, and packages with smart tools and simple dashboards. Customize your website, handle inquiries, and get real-time insights to grow your services with confidence.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Simplify Pet Care Management with WorkDo Dash!';
        $data['dedicated_theme_description'] = '<p>From grooming appointments to adoptions and service tracking. Stay organized with centralized dashboards, manage customers and pets seamlessly, and streamline payments and inquiries—all within one powerful, easy-to-use system.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Build & Manage Pet Care Packages Seamlessly","dedicated_theme_section_description": "Maintain a complete database of available pet vaccines with pricing and detailed descriptions, allowing easy management of vaccine inventory to ensure pet owners have access to essential health services. Design attractive grooming packages by combining multiple services and vaccines into comprehensive offerings, with automatic calculation of package totals while allowing manual adjustments for special pricing. Visual package icons and detailed descriptions help customers understand value propositions, while the flexible package builder accommodates various service combinations for creating seasonal offers and premium packages. Quick search functionality and export options help veterinarians and staff access vaccine information efficiently during consultations and treatment planning."},{"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Pet Adoption & Adoption Requests Made Effortless","dedicated_theme_section_description": "Complete pet adoption system manages available pets with detailed profiles including health status, classification tags, and multiple images, tracking adoption availability and managing adoption requests efficiently. The system handles adoption amounts, pet characteristics, and availability status, making it easy for potential adopters to find suitable pets while maintaining organized records. Manage adoption requests with complete applicant information and status tracking, processing requests from initial submission through approval and completion stages. Integration with payment processing ensures smooth adoption fee collection, while detailed request tracking helps staff manage the adoption process efficiently while maintaining communication with potential adopters."},{"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Complete Appointment Management","dedicated_theme_section_description": "Streamline appointment booking with comprehensive customer and pet information collection. The appointment system captures owner details, pet information, and service requirements in a single integrated form. Staff assignment features enable proper workload distribution and scheduling management. Appointment status tracking includes pending, approved, completed, and rejected states with automated billing integration. The system generates unique appointment numbers and provides detailed appointment history for customer service excellence."},{"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Integrated Payment Processing","dedicated_theme_section_description": "Manage all financial transactions through a comprehensive billing and payment system. Process payments for approved appointments with multiple payment method support, including cash, bank transfers, and checks. The system tracks payment status, due amounts, and generates detailed payment summaries. Payment record management includes receipt uploads, reference tracking, and comprehensive payment history. Automated payment calculations ensure accurate billing based on selected services and packages."},{"dedicated_theme_section_image": "","dedicated_theme_section_heading": "Complete Frontend Website Management","dedicated_theme_section_description": "Handle customer inquiries and communications through an integrated contact management system that tracks message status from new inquiries through resolution, categorizing communications appropriately while managing visitor submissions from the website. Powerful configuration system allows complete customization of website appearance and functionality, managing brand elements, banner settings, review systems, and payment policies. Configure contact information, social media links, and FAQ sections with granular control over what content appears on the website, ensuring consistent branding and user experience across all pages while maintaining a comprehensive customer communication history."}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"PetCare"},{"screenshots":"","screenshots_heading":"PetCare"},{"screenshots":"","screenshots_heading":"PetCare"},{"screenshots":"","screenshots_heading":"PetCare"},{"screenshots":"","screenshots_heading":"PetCare"},{"screenshots":"","screenshots_heading":"PetCare"}]';
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
