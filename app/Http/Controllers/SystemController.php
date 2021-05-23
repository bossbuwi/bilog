<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\System;
use App\Http\Resources\SystemResource as SystemResource;
use App\Http\Resources\EventResource as EventResource;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $systems = System::all();
        return SystemResource:: collection($systems);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $itemCount = count($request->input('*.globalPrefix'));
        for ($i = 0; $i < $itemCount; $i++) {
            if ($request->input('*.id')[$i] == NULL) {
                if (System::where('global_prefix',$request->input('*.globalPrefix')[$i])
                    ->where('zone1_prefix',$request->input('*.zone1Prefix')[$i])
                    ->where('zone2_prefix',$request->input('*.zone2Prefix')[$i])->exists()) {
                    return false;
                } else {
                    $newSystem = new System();
                    $newSystem->global_prefix = $request->input('*.globalPrefix')[$i];
                    $newSystem->machine = $request->input('*.machine')[$i];
                    $newSystem->zone1_prefix = $request->input('*.zone1Prefix')[$i];
                    $newSystem->zone1_name = $request->input('*.zone1Name')[$i];
                    $newSystem->zone2_prefix = $request->input('*.zone2Prefix')[$i];
                    $newSystem->zone2_name = $request->input('*.zone2Name')[$i];
                    $newSystem->login = $request->input('*.loginNames')[$i];
                    $newSystem->sysadmin = $request->input('*.systemAdmin')[$i];
                    $newSystem->url = $request->input('*.systemUrl')[$i];
                    $newSystem->save();
                }
            } else {
                $updateSystem = System::where('id', $request->input('*.id')[$i])->first();
                $updateSystem->global_prefix = $request->input('*.globalPrefix')[$i];
                $updateSystem->machine = $request->input('*.machine')[$i];
                $updateSystem->zone1_prefix = $request->input('*.zone1Prefix')[$i];
                $updateSystem->zone1_name = $request->input('*.zone1Name')[$i];
                $updateSystem->zone2_prefix = $request->input('*.zone2Prefix')[$i];
                $updateSystem->zone2_name = $request->input('*.zone2Name')[$i];
                $updateSystem->login = $request->input('*.loginNames')[$i];
                $updateSystem->sysadmin = $request->input('*.systemAdmin')[$i];
                $updateSystem->url = $request->input('*.systemUrl')[$i];
                $updateSystem->save();
            }
        }

        return true;
    }

    public function systemVersion(Request $request)
    {
        try {
            $system = System::where('global_prefix', $request->input('globalPrefix'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            abort(404, 'System not found.');
        }

        $event = System::find($system->id)->event()->systemUpgrade('last')->firstOrFail();
        return new EventResource($event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
