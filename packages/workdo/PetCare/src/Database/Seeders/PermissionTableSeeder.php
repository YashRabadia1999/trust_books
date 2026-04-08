<?php

namespace Workdo\PetCare\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
     public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'PetCare';

        $permissions  = [
            'petcare manage',
            'petcare dashboard manage',

            'pet_appointments manage',
            'pet_appointments create',
            'pet_appointments edit',
            'pet_appointments delete',
            'pet_appointments show',
            'pet_appointments status update',

            'pet_services manage',
            'pet_services create',
            'pet_services edit',
            'pet_services delete',
            'pet_services add features & process',

            'pet_vaccines manage',
            'pet_vaccines create',
            'pet_vaccines edit',
            'pet_vaccines delete',

            'pet_grooming_packages manage',
            'pet_grooming_packages create',
            'pet_grooming_packages edit',
            'pet_grooming_packages delete',
            'pet_grooming_packages show',

            'billing_payments manage',
            'billing_payments create',
            'billing_payments show',
            
            'pet_adoption manage',
            'pet_adoption create',
            'pet_adoption edit',
            'pet_adoption delete',
            'pet_adoption show',

            'pet_adoption_request manage',
            'pet_adoption_request create',
            'pet_adoption_request edit',
            'pet_adoption_request delete',
            'pet_adoption_request show',
            'pet_adoption_request status update',

            'adoption_request_payments manage',
            'adoption_request_payments create',
            'adoption_request_payments show',
            
            'petcare_review manage',
            'petcare_review create',
            'petcare_review edit',
            'petcare_review delete',
            'petcare_review action',

            'service_review manage',
            'service_review create',
            'service_review edit',
            'service_review delete',
            'service_review action',

            'petcare_faq manage',
            'petcare_faq create',
            'petcare_faq edit',
            'petcare_faq delete',
            'petcare_faq add question & answer',

            'petcare_contacts manage',
            'petcare_contacts create',
            'petcare_contacts edit',
            'petcare_contacts delete',

            'petcare_social_links manage',
            'petcare_social_links create',
            'petcare_social_links edit',
            'petcare_social_links delete',
            
            'petcare_brand_setting manage',
            'petcare_banner_setting manage',
            'petcare_packages_page_setting manage',
            'petcare_services_page_setting manage',
            'petcare_additional_setting manage',
            'petcare_about_us manage',
            'petcare_contact_us manage',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permissions as $key => $value)
        {
            $check = Permission::where('name',$value)->where('module',$module)->exists();
            if($check == false)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
