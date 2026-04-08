<?php

namespace Workdo\School\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\School\Entities\SchoolParent;
use Workdo\School\Entities\SchoolStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Workdo\School\Events\CreateSchoolParent;
use Workdo\School\Events\DestorySchoolParent;
use Workdo\School\Events\UpdateSchoolParent;
use Workdo\School\DataTables\ParentDataTable;

class SchoolParentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ParentDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_parent manage')) {

            return $dataTable->render('school::parent.index');
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('school_parent create')) {
            $realtion  = SchoolParent::$relation;
            $client = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'client')->get()->pluck('name', 'id');
            $student = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'student')->get()->pluck('name', 'id');

            if(module_is_active('CustomField')){
                $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'School')->where('sub_module','Parent')->get();
            }else{
                $customFields = null;
            }
            return view('school::parent.create', compact('realtion','customFields','client','student'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    function parentNumber()
    {
        $latest = SchoolParent::where('workspace', getActiveWorkSpace())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->parent_id + 1;
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('school_parent create')) {
            $canUse =  PlanCheck('User', \Auth::user()->id);
            if ($canUse == false) {
                return redirect()->back()->with('error', 'You have maxed out the total number of customer allowed on your current plan');
            }
            $rules = [
                'name' => 'required',
                'gender' => 'required',
                'date_of_birth' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if (empty($request->user_id)) {
                $rules = [
                    'email' => [
                        'required',
                    ],
                ];
                $validator = \Validator::make($request->all(), $rules);
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->route('school-parent.index')->with('error', $messages->first());
            }

            if ($request->input('contact')) {
                $validator = \Validator::make(
                     $request->all(), ['contact' => 'required|regex:/^\+\d{1,3}\d{9,13}$/',]
                );
                if($validator->fails()){
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $roles = Role::where('name', 'parent')->where('guard_name', 'web')->where('created_by', creatorId())->first();
            if (empty($roles)) {
                return redirect()->back()->with('error', __('Parent Role Not found !'));
            }
            if (!empty($request->user_id)) {
                $user = User::find($request->user_id);
                if (empty($user)) {
                    return redirect()->back()->with('error', __('Something went wrong please try again.'));
                }
                if ($user->name != $request->name) {
                    $user->name = $request->name;
                    $user->save();
                }
                if ($user->mobile_no != $request->contact) {
                    $user->mobile_no = $request->contact;
                    $user->save();
                }
            } else {
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'mobile_no' => $request['contact'],
                        'password' => Hash::make($request['password']),
                        'email_verified_at' => date('Y-m-d h:i:s'),
                        'type' => $roles->name,
                        'lang' => 'en',
                        'workspace_id' => getActiveWorkSpace(),
                        'active_workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ]
                );
                $user->save();
                $user->addRole($roles);
            }
            $parent                = new SchoolParent();
            $parent->client        = $request->client;
            $parent->user_id       = $user->id;
            $parent->parent_id     = $this->parentNumber();
            $parent->name          = !empty($user->name) ? $user->name : null;
            $parent->student       = !empty($request->student) ? $request->student : null;
            $parent->gender        = !empty($request->gender) ? $request->gender : null;
            $parent->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
            $parent->relation      = !empty($request->relation) ? $request->relation : null;
            $parent->address       = !empty($request->address) ? $request->address : null;
            $parent->state         = !empty($request->state) ? $request->state : null;
            $parent->city          = !empty($request->city) ? $request->city : null;
            $parent->zip_code      = !empty($request->zip_code) ? $request->zip_code : null;
            $parent->contact       = !empty($request->contact) ? $request->contact : null;
            $parent->email         = !empty($request->email) ? $request->email : null;
            $parent->password      = !empty($request->password) ? $request->password : null;
            if ($request->hasFile('parent_image')) {
                $fileName = time() . "_" . $request->parent_image->getClientOriginalName();
                $path = upload_file($request, 'parent_image', $fileName, 'Parent');
                $parent->parent_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $parent->workspace     = getActiveWorkSpace();
            $parent->created_by    = \Auth::user()->id;
            $parent->save();
            if(module_is_active('CustomField'))
            {
                \Workdo\CustomField\Entities\CustomField::saveData($parent, $request->customField);
            }
            event(new CreateSchoolParent($request,$parent));

            return redirect()->route('school-parent.index')->with('success', __('The parent has been created successfully.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

   /**
 * Show the specified parent resource.
 * @param int $id
 * @return Renderable
 */
public function show($id)
{
    if (Auth::user()->isAbleTo('school_parent show')) {
        // Find Parent by ID
        $parent = SchoolParent::where('id', $id)
            ->where('workspace', getActiveWorkSpace())
            ->first();

        if (!$parent) {
            return redirect()->back()->with('error', __('Parent not found.'));
        }

        // Get User from parent->user_id
        $user = User::where('id', $parent->user_id)
            ->where('workspace_id', getActiveWorkSpace())
            ->first();
      
        // Custom Fields (if module active)
        if (module_is_active('CustomField')) {
            $parent->customField = \Workdo\CustomField\Entities\CustomField::getData($parent, 'School', 'Parent');
            $customFields = \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())
                ->where('module', 'School')
                ->where('sub_module', 'Parent')
                ->get();
        } else {
            $customFields = null;
        }

        // Fetch related data if available (replace with real queries when modules are ready)
        $invoices = [];
        $revenues = [];
        $projects = [];
        $statements = [];

        // Example hooks: if you later have invoices table keyed by parent->user_id
        // $invoices = Invoice::where('parent_user_id', $parent->user_id)->latest()->limit(20)->get()->toArray();

        return view('school::parent.show', compact('parent', 'user', 'customFields', 'invoices', 'revenues', 'projects', 'statements'));
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('school_parent edit')) {
            $ids = decrypt($id);
            $parent   = SchoolParent::where('id',$ids)->where('workspace',getActiveWorkSpace())->first();
            if($parent){
                $user      = User::where('id',$parent->user_id)->where('workspace_id',getActiveWorkSpace())->first();
            }else{
                    $user = '';
            }
            $realtion  = SchoolParent::$relation;
            $client = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'client')->get()->pluck('name', 'id');
            $student = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'student')->get()->pluck('name', 'id');

            if(!empty($parent)){
                if(module_is_active('CustomField')){
                    $parent->customField = \Workdo\CustomField\Entities\CustomField::getData($parent, 'School','Parent');
                    $customFields             = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'School')->where('sub_module','Parent')->get();
                }else{
                    $customFields = null;
                }
                return view('school::parent.edit',compact('parent','user','realtion','customFields' , 'client','student'));
            }

            return view('school::parent.edit',compact('parent','user','realtion','client','student'));

        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('school_parent edit')) {
            $rules = [
                'name' => 'required',
                'gender' => 'required',
                'date_of_birth' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->input('contact')) {
                $validator = \Validator::make(
                     $request->all(), ['contact' => 'required|regex:/^\+\d{1,3}\d{9,13}$/',]
                );
                if($validator->fails()){
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $user = User::where('id',$request->user_id)->first();
            if(empty($user))
            {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if($user->name != $request->name)
            {
                $user->name = $request->name;
                $user->save();
            }
            if($user->mobile_no != $request->contact)
            {
                $user->mobile_no = $request->contact;
                $user->save();
            }
            $parent = SchoolParent::find($id);
            $parent->client        = !empty($request->client) ? $request->client : null;
            $parent->name          = !empty($user->name) ? $user->name : null;
            $parent->student       = !empty($request->student) ? $request->student : null;
            $parent->gender        = !empty($request->gender) ? $request->gender : null;
            $parent->date_of_birth = !empty($request->date_of_birth) ? $request->date_of_birth : null;
            $parent->relation      = !empty($request->relation) ? $request->relation : null;
            $parent->address       = !empty($request->address) ? $request->address : null;
            $parent->state         = !empty($request->state) ? $request->state : null;
            $parent->city          = !empty($request->city) ? $request->city : null;
            $parent->zip_code      = !empty($request->zip_code) ? $request->zip_code : null;
            $parent->contact       = !empty($request->contact) ? $request->contact : null;
            $parent->email         = !empty($request->email) ? $request->email : null;
            $parent->password      = !empty($request->password) ? $request->password : null;
            if ($request->hasFile('parent_image')) {
                $fileName = time() . "_" . $request->parent_image->getClientOriginalName();
                $path = upload_file($request, 'parent_image', $fileName, 'Parent');
                $parent->parent_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $parent->update();
            if(module_is_active('CustomField'))
            {
                \Workdo\CustomField\Entities\CustomField::saveData($parent, $request->customField);
            }
            event(new UpdateSchoolParent($request,$parent));

            return redirect()->route('school-parent.index')->with('success', __('The parent details are updated successfully.'));

        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $parent = SchoolParent::where('user_id',$id)->where('workspace',getActiveWorkSpace())->first();
        if (Auth::user()->isAbleTo('school_parent delete')) {
            if(module_is_active('CustomField'))
            {
                $customFields = \Workdo\CustomField\Entities\CustomField::where('module','School')->where('sub_module','Parent')->get();
                foreach($customFields as $customField)
                {
                    $value = \Workdo\CustomField\Entities\CustomFieldValue::where('record_id', '=', $parent->id)->where('field_id',$customField->id)->first();
                    if(!empty($value)){
                        $value->delete();
                    }
                }
            }
            event(new  DestorySchoolParent($parent));
            if(!empty($parent))
            {
                $parent->delete();
            }

            return redirect()->route('school-parent.index')->with('success', __('The parent has been deleted.'));

        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
