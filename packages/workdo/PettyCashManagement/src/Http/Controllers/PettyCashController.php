<?php

namespace Workdo\PettyCashManagement\Http\Controllers;

use Workdo\PettyCashManagement\DataTables\PettyCashDataTable;
use Workdo\PettyCashManagement\Entities\PettyCash;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Workdo\PettyCashManagement\Events\CreatePettyCash;
use Workdo\PettyCashManagement\Events\DestroyPettyCash;
use Workdo\PettyCashManagement\Events\UpdatePettyCash;


class PettyCashController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PettyCashDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('pettycash manage')) {
            return $dataTable->render('petty-cash-management::petty_cash.index');
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
        if (Auth::user()->isAbleTo('pettycash create')) {
            return view('petty-cash-management::petty_cash.create');
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
        if (Auth::user()->isAbleTo('pettycash create')) {
            $request->validate([
                'added_amount' => 'required|numeric|min:0',
                'remarks'      => 'nullable|string',
            ]);

            $lastRecord     = PettyCash::latest()->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $openingBalance = $lastRecord ? $lastRecord->closing_balance : 0;
            $closingBalance = $openingBalance + $request->added_amount;
            $pattycash = PettyCash::create([
                'date'            => now(),
                'opening_balance' => $openingBalance,
                'added_amount'    => $request->added_amount,
                'total_balance'   => $closingBalance,
                'closing_balance' => $closingBalance,
                'remarks'         => $request->remarks,
                'created_by'      => creatorId(),
                'workspace'       => getActiveWorkSpace(),
            ]);

            event(new CreatePettyCash($request, $pattycash));

            return redirect()->route('petty-cash.index')->with('success', __('The petty cash has been created successfully.'));
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
        return view('petty-cash-management::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('pettycash edit')) {
            $pettycash = PettyCash::find($id);
            if ($pettycash->created_by == creatorId() && $pettycash->workspace == getActiveWorkSpace()) {
                return view('petty-cash-management::petty_cash.edit',compact('pettycash'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
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
        if (Auth::user()->isAbleTo('pettycash edit')) {
            $request->validate([
                'added_amount' => 'required|numeric|min:0',
                'remarks' => 'nullable|string',
            ]);

            $pettyCash = PettyCash::findOrFail($id);
            $previousAddedAmount      = $pettyCash->added_amount;
            $newAddedAmount           = $request->added_amount;
            $adjustment               = $newAddedAmount - $previousAddedAmount;
            $closing_balance          = $pettyCash->closing_balance += $adjustment;
            if($pettyCash->total_expense) {
                $pettyCash->total_balance = $request->added_amount + $pettyCash->opening_balance;
            } else{
                $pettyCash->total_balance = $closing_balance;
            }
            $pettyCash->date          = $request->date;
            $pettyCash->added_amount  = $newAddedAmount;
            $pettyCash->remarks       = $request->remarks;
            $pettyCash->save();

            event(new UpdatePettyCash($request, $pettyCash));
            return redirect()->route('petty-cash.index')->with('success', __('The petty cash details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('pettycash edit')) {
            $pettyCash = PettyCash::findOrFail($id);
            event(new DestroyPettyCash($pettyCash));
            $pettyCash->delete();
            return redirect()->route('petty-cash.index')->with('success', __('The petty cash has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
