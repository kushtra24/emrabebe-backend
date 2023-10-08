<?php

namespace App\Http\Controllers;

use App\Models\Origin;
use Illuminate\Http\Request;

class OriginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $locale = $request->input('locale');
        
        $localeName = 'name';

        if($locale == 'en') {
            $localeName = 'name';
            $localeId = 'WHEN id = 17 THEN 0 WHEN id = 26 THEN 1 ELSE 2 END';
        } else if($locale == 'al'){
            $localeName = 'name_'.$locale;
            $localeId = 'WHEN id = 16 THEN 0 WHEN id = 108 THEN 1 WHEN id = 109 THEN 2 ELSE 3 END';
        } else {
            $localeName = 'name_'.$locale;
            $localeId = 'WHEN id = 65 THEN 0 WHEN id = 109 THEN 1 ELSE 2 END';
        }

        $origin = Origin::select('id', "$localeName AS name")->orderByRaw("CASE {$localeId}")->orderBy('name')->get();

        return response()->json($origin, 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Origin  $origin
     * @return \Illuminate\Http\Response
     */
    public function show(Origin $origin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Origin  $origin
     * @return \Illuminate\Http\Response
     */
    public function edit(Origin $origin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Origin  $origin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Origin $origin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Origin  $origin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Origin $origin)
    {
        //
    }
}
