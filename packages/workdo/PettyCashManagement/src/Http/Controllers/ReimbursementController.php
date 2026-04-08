<?php

namespace Workdo\PettyCashManagement\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Workdo\PettyCashManagement\Entities\Reimbursement;
use Workdo\PettyCashManagement\Entities\PettyCashCategorie;
use Workdo\PettyCashManagement\DataTables\ReimbursementDataTable;
use Workdo\PettyCashManagement\Entities\PettyCashExpense;
use Workdo\PettyCashManagement\Entities\PettyCash;
use Workdo\PettyCashManagement\Events\CreateReimbursement;
use Workdo\PettyCashManagement\Events\DestroyPettyCashRimbursement;
use Workdo\PettyCashManagement\Events\UpdateReimbursement;


class ReimbursementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ReimbursementDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('reimbursement manage')) {
            return $dataTable->render('petty-cash-management::reimbursement.index');
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
        if (Auth::user()->isAbleTo('reimbursement create')) {
            if(in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $user = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
            } else {
                $user = User::where('id',Auth::user()->id)->where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->first();
            }
            $categories = PettyCashCategorie::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('name', 'id');
            return view('petty-cash-management::reimbursement.create',compact('user','categories'));
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
        if (Auth::user()->isAbleTo('reimbursement create')) {
            $request->validate([
                'user_id' => 'required',
                'category_id' => 'required',
                'amount' => 'required',
                'remarks' => 'required',
                'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $reimbursement = new Reimbursement();
            $reimbursement->user_id      = $request->user_id;
            $reimbursement->category_id  = $request->category_id;
            $reimbursement->amount       = $request->amount;
            $reimbursement->status       = 'pending';
            $reimbursement->description  = $request->remarks;
            $reimbursement->request_date = now();
            $reimbursement->workspace    = getActiveWorkSpace();
            $reimbursement->created_by   = creatorId();
            if ($request->hasFile('receipt')) {
                $filenameWithExt = $request->file('receipt')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('receipt')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $uplaod          = upload_file($request, 'receipt', $fileNameToStore, 'reimbursement');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
                $reimbursement->receipt_path = $url;
            }

            $reimbursement->save();
            event(new CreateReimbursement($request, $reimbursement));
            return redirect()->route('reimbursement.index')->with('success', __('The reimbursement request has been created successfully.'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $reimbursement = Reimbursement::find($id);
        return view('petty-cash-management::reimbursement.show', compact('reimbursement'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('reimbursement edit')) {
            $reimbursement = Reimbursement::find($id);
            if(in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $user = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
            } else {
                $user = User::where('id',Auth::user()->id)->where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->first();
            }
            $categories = PettyCashCategorie::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('name', 'id');
            return view('petty-cash-management::reimbursement.edit',compact('reimbursement','user','categories'));
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
        if (Auth::user()->isAbleTo('reimbursement edit')) {
            $request->validate([
                'user_id'     => 'required|exists:users,id',
                'category_id' => 'required|exists:petty_cash_categories,id',
                'amount'      => 'required|numeric|min:0',
            ]);

            $reimbursement              = Reimbursement::findOrFail($id);
            $reimbursement->user_id     = $request->user_id;
            $reimbursement->category_id = $request->category_id;
            $reimbursement->amount      = $request->amount;
            $reimbursement->description = $request->remarks;

            if($request->hasFile('receipt')){
                $filenameWithExt            = $request->file('receipt')->getClientOriginalName();
                $filename                   = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension                  = $request->file('receipt')->getClientOriginalExtension();
                $fileNameToStore            = $filename . '_' . time() . '.' . $extension;
                $uplaod                     = upload_file($request, 'receipt', $fileNameToStore, 'reimbursement');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
                $reimbursement->receipt_path = $url;
            }
            $reimbursement->save();
            event(new UpdateReimbursement($request, $reimbursement));

            return redirect()->route('reimbursement.index')->with('success', __('The reimbursement request details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function description($id)
    {
        $reimbursement = Reimbursement::find($id);
        return view('petty-cash-management::reimbursement.desription',compact('reimbursement'));
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('reimbursement delete')) {
            $reimbursement = Reimbursement::find($id);
            event(new DestroyPettyCashRimbursement($reimbursement));
            $reimbursement->delete();
            return redirect()->route('reimbursement.index')->with('success', __('The reimbursement request has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function approve(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('reimbursement approve')) {
            $reimbursement = Reimbursement::find($id);

            if ($request->status == 'Reject') {
                $reimbursement->update([
                    'status'      => 'rejected',
                    'approved_date' => now(),
                    'approved_by' => Auth::id(),
                ]);
                return redirect()->route('reimbursement.index')->with('success', 'Reimbursement request rejected successfully!');
            } else {

                $pattyCash = PettyCash::latest()->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
                if ($pattyCash) {
                    $closing_balance = $pattyCash->closing_balance - $reimbursement->amount;
                    if($closing_balance < 0 || $reimbursement->requested_amount > $pattyCash->closing_balance){
                        return redirect()->route('reimbursement.index')->with('error', 'Insufficient balance in petty cash!');
                    } else {
                        $pattyCash->update([
                            'closing_balance' => $closing_balance,
                            'total_expense'   => $pattyCash->total_expense + $reimbursement->amount,
                        ]);
                        $reimbursement->update([
                            'status'      => 'approved',
                            'approved_date' => now(),
                            'approved_by' => Auth::id(),
                        ]);
                    }
                } else {
                    $closing_balance = 0;
                }

                $expense              = new PettyCashExpense();
                $expense->request_id  = $reimbursement->id;
                $expense->type        = 'reimbursement';
                $expense->amount      = $reimbursement->amount;
                $expense->remarks     = $reimbursement->description;
                $expense->status      = 'approved';
                $expense->approved_at = now();
                $expense->approved_by = Auth::id();
                $expense->workspace   = getActiveWorkSpace();
                $expense->created_by  = creatorId();
                $expense->save();
                return redirect()->route('reimbursement.index')->with('success', __('The reimbursement request has been approved successfully.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
