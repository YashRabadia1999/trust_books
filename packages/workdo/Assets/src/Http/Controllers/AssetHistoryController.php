<?php

namespace Workdo\Assets\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\Assets\Entities\Asset;
use Workdo\Assets\Entities\AssetHistory;
use Workdo\Assets\DataTables\HistoryDataTable;

class AssetHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(HistoryDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('assets history manage'))
        {
            return $dataTable->render('assets::history.index');
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

}
