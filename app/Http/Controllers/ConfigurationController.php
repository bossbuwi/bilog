<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Http\Resources\ConfigurationResource as ConfigurationResource;
use Illuminate\Support\Facades\Log;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $config = Configuration::all();
        return ConfigurationResource:: collection($config);
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
        $itemCount = count($request->input('*.name'));
        for ($i = 0; $i < $itemCount; $i++) {
            if (Configuration::where('name', $request->input('*.name')[$i])->exists()) {
                $config = Configuration::where('name', $request->input('*.name')[$i])->first();
                if (strlen($request->input('*.value')[$i]) === $config->length) {
                    if ($config->valid_values == 'numeric') {
                        if (ctype_digit($request->input('*.value')[$i])) {
                            $config->value = $request->input('*.value')[$i];
                            $config->last_modified_by = $request->input('*.lastModifiedBy')[$i];
                            $config->save();
                        } else {
                            $config->value = $config->default_value;
                            $config->last_modified_by = $request->input('*.lastModifiedBy')[$i];
                            $config->save();
                        }
                    } else if ($config->valid_values == 'alpha') {
                        if (ctype_alpha($request->input('*.value')[$i])) {
                            $config->value = $request->input('*.value')[$i];
                            $config->last_modified_by = $request->input('*.lastModifiedBy')[$i];
                            $config->save();
                        } else {
                            $config->value = $config->default_value;
                            $config->last_modified_by = $request->input('*.lastModifiedBy')[$i];
                            $config->save();
                        }
                    } else if ($config->valid_values == 'alphanumeric') {
                        if (ctype_alnum($request->input('*.value')[$i])) {
                            $config->value = $request->input('*.value')[$i];
                            $config->last_modified_by = $request->input('*.lastModifiedBy')[$i];
                            $config->save();
                        } else {
                            $config->value = $config->default_value;
                            $config->last_modified_by = $request->input('*.lastModifiedBy')[$i];
                            $config->save();
                        }
                    } else {
                        $validValues = strtok($config->valid_values, ',');
                        $validValuesArr = array();
                        while ($validValues !== false) {
                            array_push($validValuesArr, $validValues);
                            $validValues = strtok(',');
                        }
                        $key = array_search($request->input('*.value')[$i], $validValuesArr);
                        if ($key === false) {
                            $config->value = $config->default_value;
                            $config->last_modified_by = $request->input('*.lastModifiedBy')[$i];
                            $config->save();
                        } else {
                            $config->value = $request->input('*.value')[$i];
                            $config->last_modified_by = $request->input('*.lastModifiedBy')[$i];
                            $config->save();
                        }
                    }
                } else {
                    $config->value = $config->default_value;
                    $config->save();
                }
            } else {
                return 'false';
            }
        }
        return 'true';
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
