<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Resources\EventResource as EventResource;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use SimpleXLSXGen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

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
        Log::error($request);
        if ($request->isMethod('post')) {
            $event = new Event();
            if ($request->has('params')) {
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
        } else if ($request->isMethod('put')) {
            if ($request->has('params')) {
                Log::error('papasok dpat dito sa params');
                if(Event::where('_id',$request->input('params._id'))->exists()) {
                    Log::error('papasok dpat dito sa exists');
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
        Log::error($request);
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
