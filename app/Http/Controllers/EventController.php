<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Event;
use App\Models\System;
use App\Models\Type;
use App\Http\Resources\EventResource as EventResource;

use SimpleXLSXGen;

class EventController extends Controller
{
    public function postEvent(Request $request) {
        if ($request->has('event')) {
            try {
                $system = System::where('global_prefix', $request->input('event.system'))->firstOrFail();
            } catch (ModelNotFoundException $e) {
                abort(404, 'System not found.');
            }

            try {
                $type = Type::where('event_code', $request->input('event.type'))->firstOrFail();
            } catch (ModelNotFoundException $e) {
                abort(404, 'Event type not found.');
            }

            $user = $request->input('event.user');
            $zone = $request->input('event.zone');
            $jira_case = $request->input('event.jiraCase');
            $api_used = $request->input('event.apiUsed');
            $compiled_sources = $request->input('event.compiledSources');
            $feature_on = $request->input('event.featureOn');
            $feature_off = $request->input('event.featureOff');
            $start_date = $request->input('event.startDate');
            $end_date = $request->input('event.endDate');
            $created_by = $user;
            $last_modified_by = $user;

            if ($system->find($system->id)->event()
                ->where('user', $user)
                ->where('created_by', $created_by)
                ->where('last_modified_by', $last_modified_by)
                ->where('zone', $zone)
                ->where('type_id', $type->id)
                ->where('jira_case', $jira_case)
                ->where('api_used', $api_used)
                ->where('compiled_sources', $compiled_sources)
                ->where('feature_on', $feature_on)
                ->where('feature_off', $feature_off)
                ->where('start_date', $start_date)
                ->where('end_date', $end_date)
                ->exists()) {
                    abort(403, 'Record already exists.');
            } else {
                $event = new Event();
                $event->user = $user;
                $event->created_by = $created_by;
                $event->last_modified_by = $last_modified_by;
                $event->zone = $zone;
                $event->type_id = $type->id;
                $event->jira_case = $jira_case;
                $event->api_used = $api_used;
                $event->compiled_sources = $compiled_sources;
                $event->feature_on = $feature_on;
                $event->feature_off = $feature_off;
                $event->start_date = $start_date;
                $event->end_date = $end_date;

                if($system->event()->save($event)) {
                    return new EventResource($event);
                } else {
                    abort(507, 'There is a problem saving the data.');
                }
            }
        } else {
            abort(400, 'Event key not found.');
        }
    }

    public function editEvent(Request $request) {
        if ($request->has('event')) {
            try {
                $event = Event::findOrFail($request->input('event._id'));
            } catch (ModelNotFoundException $e) {
                abort(404, 'Event not found.');
            }

            try {
                $system = System::where('global_prefix', $request->input('event.system'))->firstOrFail();
            } catch (ModelNotFoundException $e) {
                abort(404, 'System not found.');
            }

            try {
                $type = Type::where('event_code', $request->input('event.type'))->firstOrFail();
            } catch (ModelNotFoundException $e) {
                abort(404, 'Event type not found.');
            }

            $user = $request->input('event.user');
            $zone = $request->input('event.zone');
            $jira_case = $request->input('event.jiraCase');
            $api_used = $request->input('event.apiUsed');
            $compiled_sources = $request->input('event.compiledSources');
            $feature_on = $request->input('event.featureOn');
            $feature_off = $request->input('event.featureOff');
            $start_date = $request->input('event.startDate');
            $end_date = $request->input('event.endDate');
            $last_modified_by = $user;

            $event->user = $user;
            $event->last_modified_by = $last_modified_by;
            $event->zone = $zone;
            $event->type_id = $type->id;
            $event->jira_case = $jira_case;
            $event->api_used = $api_used;
            $event->compiled_sources = $compiled_sources;
            $event->feature_on = $feature_on;
            $event->feature_off = $feature_off;
            $event->start_date = $start_date;
            $event->end_date = $end_date;

            if($system->event()->save($event)) {
                return new EventResource($event);
            } else {
                abort(507, 'There is a problem saving the data.');
            }
        } else {
            abort(400, 'Event key not found.');
        }
    }

    public function generateReport(Request $request) {
        //check if the request has a non null system key
        if ($request->has('system') && $request->query('system') !== NULL) {
            //save the request parameters
            $system = $request->query('system');
            $zone = $request->query('zone');
            $type = $request->query('type');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $curSysVer = $request->query('curSysVer');
            $requestReport = $request->query('requestReport');

            //All Systems
            if ($system === 'All') {
                if ($requestReport == 'true') {
                    $systems = System::all();
                    $systemCount = count($systems);
                    $xlsx = new SimpleXLSXGen();
                    for ($i = 0; $i < $systemCount; $i++) {
                        $events = System::find($i+1)->event();
                        if ($curSysVer == 'true') {
                            try {
                                $systemUpgrade = System::find($i+1)->event()->systemupgrade('last')->firstOrFail();
                                $events->where('start_date', '>=', $systemUpgrade->end_date);
                            } catch (ModelNotFoundException $e) {
                                $systemUpgrade = NULL;
                            }
                        } else if ($curSysVer == 'false') {
                            if ($startDate !== NULL)
                            $events->where('start_date', '>=', $startDate);

                            if ($endDate !== NULL)
                            $events->where('end_date', '<=', $endDate);
                        }

                        if ($type !== 'All' && $type != NULL) {
                            try {
                                $dbType = Type::where('event_code', $type)->firstOrFail();
                                $events->where('type_id', $dbType->id);
                            } catch (ModelNotFoundException $e) {
                                abort(404, 'Event type not found.');
                            }
                        }

                        $events = $events->get();

                        if ($curSysVer == 'true') {
                            if ($systemUpgrade !== NULL) {
                                $events->prepend($systemUpgrade);
                            }
                        }

                        $columns = Schema::getColumnListing('events');
                        $data = [
                            $columns
                        ];

                        foreach ($events as $item) {
                            $eSystem = System::find($item->system_id);
                            $eType = Type::find($item->type_id);
                            $itemAttr = $item->attributesToArray();
                            $itemAttr['system_id'] = $eSystem->global_prefix;
                            $itemAttr['type_id'] = $eType->event_code;
                            array_push($data, $itemAttr);
                        }
                        $xlsx = $xlsx->addSheet($data, $systems[$i]->global_prefix.' System Report');
                    }
                    $xlsx->downloadAs('report.xlsx');
                } else if ($requestReport == 'false') {
                    $events = Event::where('_id', '>=', 0);

                    if ($type !== 'All' && $type != NULL) {
                        try {
                            $dbType = Type::where('event_code', $type)->firstOrFail();
                            $events->where('type_id', $dbType->id);
                        } catch (ModelNotFoundException $e) {
                            abort(404, 'Event type not found.');
                        }
                    }

                    if ($zone !== NULL && $zone !== 'All')
                    abort(400, 'Zone query is not supported if system = All.');

                    if ($curSysVer !== NULL && $curSysVer !== 'false')
                    abort(400, 'Query by latest system version is not supported if system = All.');

                    if ($startDate !== NULL && $endDate !== NULL) {
                        if ($startDate <= $endDate) {
                            $events->where('start_date', '>=', $startDate);
                            $events->where('end_date', '<=', $endDate);
                        } else {
                            abort(400, 'Start date is later than end date.');
                        }
                    } else {
                        if ($startDate !== NULL)
                        $events->where('start_date', '>=', $startDate);

                        if ($endDate !== NULL)
                        $events->where('end_date', '<=', $endDate);
                    }

                    $events = $events->get();

                    return EventResource::collection($events);
                }
            } else {
                //specific systems
                //find a system based on the system prefix
                //if no such system exists, return a 404 error
                try {
                    $dbSystem = System::where('global_prefix', $system)->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    abort(404, 'System not found.');
                }
                //if system exists, start building a query
                //get all events from that system
                $events = System::find($dbSystem->id)->event();

                //set the inclusive dates
                //if events from latest system version is requested
                if ($curSysVer == 'true') {
                    //try to get the latest system upgrade event first
                    try {
                        $systemUpgrade = System::find($dbSystem->id)->event()
                        ->systemupgrade('last')->firstOrFail();
                        //get events starting from the last system upgrade's end date
                        $events->where('start_date', '>=', $systemUpgrade->end_date);
                    } catch (ModelNotFoundException $e) {
                        $systemUpgrade = NULL;
                    }
                } else if ($curSysVer == 'false'){
                    if ($startDate !== NULL && $endDate !== NULL) {
                        if ($startDate <= $endDate) {
                            $events->where('start_date', '>=', $startDate);
                            $events->where('end_date', '<=', $endDate);
                        } else {
                            abort(400, 'Start date is later than end date.');
                        }
                    } else {
                        if ($startDate !== NULL)
                        $events->where('start_date', '>=', $startDate);

                        if ($endDate !== NULL)
                        $events->where('end_date', '<=', $endDate);
                    }
                }

                if ($zone !== 'All' && $zone !== NULL)
                $events->where('zone', 'like', '%'.$zone.'%');

                if ($type !== 'All' && $type != NULL) {
                    try {
                        $dbType = Type::where('event_code', $type)->firstOrFail();
                        $events->where('type_id', $dbType->id);
                    } catch (ModelNotFoundException $e) {
                        abort(404, 'Event type not found.');
                    }
                }

                $events = $events->get();

                if ($curSysVer == 'true') {
                    if ($systemUpgrade !== NULL) {
                        $events->prepend($systemUpgrade);
                    }
                }

                if ($requestReport == 'true') {
                    $columns = Schema::getColumnListing('events');
                    $data = [
                        $columns
                    ];

                    foreach ($events as $item) {
                        $eSystem = System::findOrFail($item->system_id);
                        $itemAttr = $item->attributesToArray();
                        $itemAttr['system_id'] = $eSystem->global_prefix;
                        array_push($data, $itemAttr);
                    }
                    $xlsx = new SimpleXLSXGen();
                    $xlsx = $xlsx->fromArray($data, 'System Report');
                    $xlsx->downloadAs('report.xlsx');
                } else if ($requestReport == 'false') {
                    return EventResource::collection($events);
                }
            }
        } else {
            abort(400, 'System key not found.');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $events = Event::all();
        // return EventResource:: collection($events);
        $events = Event::all();
        return EventResource::collection($events);
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
     * Deprecated. Will be deleted once the put method
     * has been coded.
     */
    public function store(Request $request)
    {
        Log::error($request);
        if ($request->isMethod('post')) {
            $event = new Event();
            if ($request->has('params')) {
                $event->user = $request->input('params.user');
                $system = System::where('global_prefix', $request->input('params.system'))->first();
                $event->system_id = $system->id;
                $event->zone = $request->input('params.zone');
                $event->type = $request->input('params.type');
                $event->jira_case = $request->input('params.jiraCase');
                $event->api_used = $request->input('params.apiUsed');
                $event->compiled_sources = $request->input('params.compiledSources');
                $event->feature_on = $request->input('params.featureOn');
                $event->feature_off = $request->input('params.featureOff');
                $event->start_date = $request->input('params.startDate');
                $event->end_date = $request->input('params.endDate');
            } else {
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
            }

            if (Event::where('user', $request->input('user'))
                // ->where('system_id', System::where('global_prefix', $request->input('system')->first()->id)
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
        } else if ($request->isMethod('put')) {
            if ($request->has('params')) {
                if(Event::where('_id',$request->input('params._id'))->exists()) {
                    $event = Event::where('_id',$request->input('params._id'))->first();
                    $event->user = $request->input('params.user');
                    $event->system = $request->input('params.system');
                    $event->zone = $request->input('params.zone');
                    $event->type = $request->input('params.type');
                    $event->jira_case = $request->input('params.jiraCase');
                    $event->api_used = $request->input('params.apiUsed');
                    $event->compiled_sources = $request->input('params.compiledSources');
                    $event->feature_on = $request->input('params.featureOn');
                    $event->feature_off = $request->input('params.featureOff');
                    $event->start_date = $request->input('params.startDate');
                    $event->end_date = $request->input('params.endDate');
                    if ($event->save()) {
                        return new EventResource($event);
                    }
                } else {
                    return null;
                }
            } else {
                if(Event::where($request->input('_id'))->exists()) {
                    $event = Event::where('_id',$request->input('_id'))->first();
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
                    if ($event->save()) {
                        return new EventResource($event);
                    }
                } else {
                    return null;
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->has('_id')) {
            if (Event::where('_id', $request->_id)->exists()) {
                $event = Event::where('_id', $request->_id)->first();
                return new EventResource($event);
            } else {
                return null;
            }
        } else if ($request->has('selectedDay')) {
            $reqDate = $request->query('selectedDay');
            $cReqDate = Carbon::createFromFormat('Y-m-d', $reqDate);
            $fReqDate = $cReqDate->format('Y-m-d');

            $events = Event::where('start_date', '<=', $fReqDate)->where('end_date', '>=', $fReqDate)->get();
            return EventResource:: collection($events);
        }
    }

    /**
     *
     */
    public function generateEventReport(Request $request)
    {
        //get the events specified by the query and save them in an Eloquent collection
        //this part is where the database logic should be
        $eventsReq = Event::where('_id', '>', 0);
        if ($request->system !== 'All') {
            $eventsReq->where('system', $request->system);
        }
        if ($request->cursysver == 'true') {
            if (Event::where('type', 'SYSUP')->exists()) {
                $sysup = Event::where('type','SYSUP')
                ->orderBy('end_date', 'DESC')->first();
                $eventsReq->where('start_date','>=', $sysup->end_date);
                $eventsReq->where('type','<>','SYSUP');
                if ($request->zone !== 'All') {
                    $eventsReq->where('zone', $request->zone);
                }
                if ($request->type !== 'All') {
                    $eventsReq->where('type', $request->type);
                }
                $eventsReq = $eventsReq->get();
                $eventsReq->prepend($sysup);
            } else {
                if ($request->zone !== 'All') {
                    $eventsReq->where('zone', 'LIKE', $request->zone);
                }
                if ($request->type !== 'All') {
                    $eventsReq->where('type', $request->type);
                }
                $eventsReq = $eventsReq->get();
            }
        } else if ($request->cursysver == 'false') {
            if ($request->zone !== 'All') {
                $eventsReq->where('zone', 'LIKE', $request->zone);
            }
            if ($request->type !== 'All') {
                $eventsReq->where('type', $request->type);
            }
            if ($request->startDate !== NULL) {
                $eventsReq->where('start_date', '>=', $request->startDate);
            }
            if ($request->endDate !== NULL) {
                $eventsReq->where('end_date', '<=',$request->endDate);
            }
            $eventsReq->orderBy('start_date', 'DESC');
            $eventsReq = $eventsReq->get();
        }
        //check for a request for report generation
        if ($request->requestReport == 'true') {
            //write all column names of the events table in an array
            $columns = Schema::getColumnListing('events');
            //create an array that will hold the data to be written in excel
            //put the array containing the column names in the data array
            $data = [
                    $columns
                ];
            //loop through the collection
            foreach ($eventsReq as $item) {
                //convert each item in the Eloquent collection to an array
                $itemAttr = $item->attributesToArray();
                //push each item into the data array created previously
                array_push($data, $itemAttr);
            }
            //set the path and the filename for the excel file to be created
            $filePath = storage_path().'/datatypes.xlsx';
            //call SimpleXLSXGen, create a file from the data array and save it on the provided path
            //comment this out for now to prevent compiling an excel file
            SimpleXLSXGen::fromArray($data)->saveAs($filePath);
            //create a variable that will hold the header containing the file type for the response
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];
            //return the response as a file
            return response()->download($filePath, "Report.xlsx", $headers);
        } else {
            //return the events as json
            return EventResource::collection($eventsReq);
        }
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
