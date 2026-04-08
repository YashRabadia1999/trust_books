<?php

namespace Workdo\PettyCashManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\PettyCashManagement\Entities\PettyCashCategorie;
use Workdo\PettyCashManagement\DataTables\PettyCashCategoriesDataTable;
use Workdo\PettyCashManagement\Events\CreatePettyCashCategorie;
use Workdo\PettyCashManagement\Events\DestroyPettyCashCategory;
use Workdo\PettyCashManagement\Events\UpdatePettyCashCategorie;


class PettyCashCategoriesController extends Controller
{
    public function index(PettyCashCategoriesDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('categories manage')) {
            return $dataTable->render('petty-cash-management::cash_categories.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('categories create')) {
            return view('petty-cash-management::cash_categories.create');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('categories create')) {
            $request->validate([
                'name' => 'required|string|max:255|unique:petty_cash_categories',
            ]);
            $category = PettyCashCategorie::create([
                'name'       => $request->name,
                'created_by' => creatorId(),
                'workspace'  => getActiveWorkSpace(),
            ]);

            event(new CreatePettyCashCategorie($request, $category));
            return redirect()->route('cash_categories.index')->with('success', __('The category has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {
        return view('petty-cash-management::show');
    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('categories edit')) {
            $categorie = PettyCashCategorie::find($id);
            return view('petty-cash-management::cash_categories.edit', compact('categorie'));
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
        if (Auth::user()->isAbleTo('categories edit')) {
            $request->validate([
                'name' => 'required|string|max:255|unique:petty_cash_categories,name,' . $id,
            ]);
            $category = PettyCashCategorie::findOrFail($id);
            $category->update(['name' => $request->name]);

            event(new UpdatePettyCashCategorie($request, $category));
            return redirect()->route('cash_categories.index')->with('success', __('The category details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('categories delete')) {
            $category = PettyCashCategorie::findOrFail($id);
            event(new DestroyPettyCashCategory($category));
            $category->delete();
            return redirect()->route('cash_categories.index')->with('success', __('The category has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
