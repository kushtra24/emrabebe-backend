<?php

namespace App\Http\Controllers;

use App\Models\BabyName;
use App\Models\SuggestName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuggestNameController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $names = SuggestName::select('id', 'name', 'gender', 'origin', 'meaning', 'approved');

        $names = $this->executeQuery($names);

        return response()->json($names, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        // if name exists return error
        if (BabyName::where('name', '=', $request['name'] )->exists()) {
            // return access denied
//            throw new \DuplicateKeyException('This name already exists', 380);
            return response()->json('Name already exists', 422);
        }

        $article = SuggestName::create($request->all());
        return response()->json($article, 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approved(Request $request, $id) {

        if (BabyName::where('name', '=', $request['name'] )->exists()) {
            // return access denied
            return response()->json('Name already exists', 422);
        }

        SuggestName::where( 'id', '=', $request['id'])->update(['approved' => 1]);

        $name = BabyName::create($request->all());

        return response()->json($name, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id) {
        $article = SuggestName::find($id);
        $article->update($request->all());
        return response()->json('updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SuggestName  $suggestName
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) {
        Log::info(var_export($id, true));

        $article = SuggestName::find($id);
        $article->delete();
        return response()->json($id, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $suggestName = SuggestName::findOrFail($id);
        return response()->json($suggestName, 200);
    }



}
