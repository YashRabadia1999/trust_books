<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\RouteDataTable;
use Workdo\School\Entities\SchoolBus;
use Workdo\School\Entities\SchoolTransportRoute;
use Workdo\School\Events\CreateSchoolRoute;
use Workdo\School\Events\DestroySchoolRoute;
use Workdo\School\Events\UpdateSchoolRoute;

class SchoolTransportRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(RouteDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_transport_route manage')) {
            return $dataTable->render('school::route.index');
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
        if (Auth::user()->isAbleTo('school_transport_route create')) {
            $buses     = SchoolBus::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('bus_number','id');

            return view('school::route.create' , compact('buses'));
        } else {
            return redirect()->back()->with('e rror', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('school_transport_route create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'route_name'     => 'required',
                    'start_location' => 'required',
                    'end_location'   => 'required',
                    'bus_id'         => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $route                  = new SchoolTransportRoute();
            $route->route_name      = $request->route_name;
            $route->start_location  = $request->start_location;
            $route->end_location    = $request->end_location;
            $route->bus_id          = $request->bus_id;
            $route->created_by      = creatorId();
            $route->workspace       = getActiveWorkSpace();
            $route->save();

            event(new CreateSchoolRoute($request, $route));

            return redirect()->back()->with('success', __('The route has been created successfully.'));
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
        return view('school::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('school_transport_route edit')) {
            $id        = Crypt::decrypt($id);
            $route     = SchoolTransportRoute::find($id);
            $buses     = SchoolBus::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('bus_number','id');

            return view('school::route.edit', compact('route','buses'));
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
        if (Auth::user()->isAbleTo('school_transport_route edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'route_name'     => 'required',
                    'start_location' => 'required',
                    'end_location'   => 'required',
                    'bus_id'         => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $route                  = SchoolTransportRoute::find($id);
            $route->route_name      = $request->route_name;
            $route->start_location  = $request->start_location;
            $route->end_location    = $request->end_location;
            $route->bus_id          = $request->bus_id;
            $route->update();
            event(new UpdateSchoolRoute($request, $route));

            return redirect()->back()->with('success', __('The route details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_transport_route delete')) {
            $route = SchoolTransportRoute::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolRoute($route));
            $route->delete();
            return redirect()->back()->with('success', __('The route has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
