<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rule;
use App\Http\Resources\RuleResource as RuleResource;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = Rule::all();
        return RuleResource:: collection($rules);
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
        $itemCount = count($request->input('*.id'));
        for ($i = 0; $i < $itemCount; $i++) {
            if ($request->input('*.id')[$i] == NULL) {
                if ($request->input('*.statement')[$i] == '*') {

                } else {
                    $newRule = new Rule();
                    $newRule->statement = $request->input('*.statement')[$i];
                    $newRule->save();
                }
            } else {
                if ($request->input('*.statement')[$i] == '*') {
                    $deleteRule = Rule::where('id', $request->input('*.id')[$i])->first();
                    $deleteRule->delete();
                } else {
                    $updateRule = Rule::where('id', $request->input('*.id')[$i])->first();
                    $updateRule->statement = $request->input('*.statement')[$i];
                    $updateRule->save();
                }
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
