<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Resources\EventResource as EventResource;

use Illuminate\Support\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all();
        return EventResource:: collection($events);
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
        $event = new Event();
        // $event->_id = $request->input('_id');
        $event->user = $request->input('user');
        $event->system = $request->input('system');
        $event->zone = $request->input('zone');
        $event->type = $request->input('type');
        $event->jira_case = $request->input('jiraCase');
        $event->api_used = $request->input('apiUsed');
        $event->compiled_sources = $request->input('compiledSources');
        $event->feature_on = $request->input('featureOn');
        $event->feature_off = $request->input('featureOff');
        $event->start_date = $request->input('startDate');
        $event->end_date = $request->input('endDate');

        if (Event::where('user', $request->input('user'))
            ->where('system', $request->input('system'))
            ->where('zone', $request->input('zone'))
            ->where('type', $request->input('type'))
            ->where('jira_case', $request->input('jiraCase'))
            ->where('api_used', $request->input('apiUsed'))
            ->where('compiled_sources', $request->input('compiledSources'))
            ->where('feature_on', $request->input('featureOn'))
            ->where('feature_off', $request->input('featureOff'))
            ->where('start_date', $request->input('startDate'))
            ->where('end_date', $request->input('endDate'))
            ->exists()) {
            return null;
        } else {
            if ($event->save()) {
                return new EventResource($event);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function getEventOnDate(Request $request)
    {
        $reqDate = $request->query('selectedDay');
        $cReqDate = Carbon::createFromFormat('Y-m-d', $reqDate);
        $fReqDate = $cReqDate->format('Y-m-d');

        $events = Event::where('start_date', '<=', $fReqDate)->where('end_date', '>=', $fReqDate)->get();
        return EventResource:: collection($events);
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
