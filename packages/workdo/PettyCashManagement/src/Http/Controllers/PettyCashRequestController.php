<?php

namespace Workdo\PettyCashManagement\Http\Controllers;

use App\Models\User;
use Workdo\Hrm\Entities\Employee;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Workdo\PettyCashManagement\Entities\PettyCashRequest;
use Workdo\PettyCashManagement\Entities\PettyCashCategorie;
use Workdo\PettyCashManagement\DataTables\PettyCashRequestDataTable;
use Workdo\PettyCashManagement\Entities\PettyCashExpense;
use Workdo\PettyCashManagement\Entities\PettyCash;
use Workdo\PettyCashManagement\Events\CreatePettyCashRequest;
use Workdo\PettyCashManagement\Events\DestroyPettyCashRequest;
use Workdo\PettyCashManagement\Events\UpdatePettyCashRequest;

class PettyCashRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PettyCashRequestDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('request manage')) {
            return $dataTable->render('petty-cash-management::petty_cash_request.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('request create')) {
            if(in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $user = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
            } else {
                $user = User::where('id',Auth::user()->id)->where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->first();
            }
            $categories = PettyCashCategorie::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('name', 'id');
            return view('petty-cash-management::petty_cash_request.create',compact('user','categories'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('request create')) {
            $request->validate([
                'user_id'          => 'required|exists:users,id',
                'requested_amount' => 'required|numeric|min:0',
                'category_id'      => 'required|exists:petty_cash_categories,id',
            ]);

            $pettyCashRequest = PettyCashRequest::create([
                'user_id'          => $request->user_id,
                'categorie_id'     => $request->category_id,
                'requested_amount' => $request->requested_amount,
                'status'           => 'pending',
                'remarks'          => $request->remarks,
                'created_by'       => creatorId(),
                'workspace'        => getActiveWorkSpace(),
            ]);

            event(new CreatePettyCashRequest($request, $pettyCashRequest));
            return redirect()->route('petty-cash-request.index')->with('success', __('The petty cash request has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $pettyCashRequest = PettyCashRequest::find($id);
        return view('petty-cash-management::petty_cash_request.show',compact('pettyCashRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('request edit')) {
            $pettyCashRequest = PettyCashRequest::find($id);
            $categories = PettyCashCategorie::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('name', 'id');
            if(in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $user = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
            } else {
                $user = User::where('id',Auth::user()->id)->where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->first();
            }
            return view('petty-cash-management::petty_cash_request.edit',compact('pettyCashRequest','user','categories'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        if (Auth::user()->isAbleTo('request edit')) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'category_id' => 'required|exists:petty_cash_categories,id',
                'requested_amount' => 'required|numeric|min:0',
            ]);

            $pettyCashRequest = PettyCashRequest::findOrFail($id);
            $pettyCashRequest->update([
                'user_id'          => $request->user_id,
                'categorie_id'     => $request->category_id,
                'requested_amount' => $request->requested_amount,
                'purpose'          => $request->purpose,
                'status'           => 'pending',
                'remarks'          => $request->remarks,
                'created_by'       => creatorId(),
                'workspace'        => getActiveWorkSpace(),
            ]);
            event(new UpdatePettyCashRequest($request, $pettyCashRequest));
            return redirect()->route('petty-cash-request.index')->with('success', __('The Petty cash request details are updated successfully.'));
        } else {
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
        if (Auth::user()->isAbleTo('request delete')) {
            $pettyCashRequest = PettyCashRequest::findOrFail($id);
            event(new DestroyPettyCashRequest($pettyCashRequest));

            $pettyCashRequest->delete();
            return redirect()->route('petty-cash-request.index')->with('success', __('The petty cash request has been deleted.'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function description($id)
    {
        $pettyCashRequest = PettyCashRequest::find($id);
        return view('petty-cash-management::petty_cash_request.desription',compact('pettyCashRequest'));
    }

    public function approve(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('request approve')) {
            $pettyCashRequest = PettyCashRequest::findOrFail($request->id);

            if($request->status == 'Reject'){
                $pettyCashRequest->update([
                    'status'      => 'rejected',
                    'approved_at' => now(),
                    'approved_by' => Auth::id(),
                ]);
                return redirect()->route('petty-cash-request.index')->with('success', 'Petty cash request rejected successfully.');
            } else {
                $pattyCash = PettyCash::latest()->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
                if ($pattyCash) {
                    $closing_balance = $pattyCash->closing_balance - $pettyCashRequest->requested_amount;
                    if ($closing_balance < 0 || $pettyCashRequest->requested_amount > $pattyCash->closing_balance) {
                        return redirect()->route('petty-cash-request.index')->with('error', 'Insufficient balance in petty cash!');
                    } else {
                        $pettyCashRequest->update([
                            'status'      => 'approved',
                            'approved_at' => now(),
                            'approved_by' => Auth::id(),
                        ]);

                        $pattyCash->update([
                            'closing_balance' => $closing_balance,
                            'total_expense'   => $pattyCash->total_expense + $pettyCashRequest->requested_amount,
                        ]);
                    }
                } else {
                    $closing_balance = 0;
                    return redirect()->route('petty-cash-request.index')->with('error', __('No petty cash record found!'));
                }

                $expense               = new PettyCashExpense();
                $expense->request_id   = $request->id;
                $expense->type         = 'pettycash';
                $expense->amount       = $pettyCashRequest->requested_amount;
                $expense->remarks      = $pettyCashRequest->remarks;
                $expense->status       = 'approved';
                $expense->approved_at  = now();
                $expense->approved_by  = Auth::id();
                $expense->workspace    = getActiveWorkSpace();
                $expense->created_by   = creatorId();
                $expense->save();
                return redirect()->route('petty-cash-request.index')->with('success', __('The Petty cash request has been approved successfully.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
