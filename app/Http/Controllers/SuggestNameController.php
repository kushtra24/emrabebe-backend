<?php

namespace App\Http\Controllers;

use App\Models\BabyName;
use App\Models\SuggestName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuggestNameController extends Controller
{


    private $rules = [
        'suggestion' => 'required|string',
    ];

    private $newNameRules = [
            'name' => 'required',
            'gender' => 'required',
            'meaning' => 'required',
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {

        $names = DB::table('suggest_names');
        $names = $names->get();

        return response()->json($names,200);
    }

    /**
     * Store suggestion about name change
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeNameChange(Request $request) {

        $this->validate($request, $this->rules);

        $nameExists = BabyName::find($request['nameid']);
        
        $suggestion = $request['suggestion'];

        if (!$nameExists->count() || !isset($suggestion)) {
            // return access denied
            return response()->json('No name provided', 422);
        }

        $suggestChange = new SuggestName;
        $suggestChange->name = $nameExists->name;
        $suggestChange->meaning = $nameExists->meaning;
        $suggestChange->exists = true;
        $suggestChange->suggest_change = $request['suggestion'];

        $suggestChange->save();
        return response()->json('',200);

    }
    /**
     * Store suggestion about new name
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeNewNameSugesstion(Request $request) {

        $this->validate($request, $this->newNameRules);

        $nameExists = BabyName::find($request['suggestion']['name']);

        if ($nameExists) {
            // return access denied
            return response()->json('Name already exists', 422);
        }

        $suggestnew = new SuggestName;
        $suggestnew->name = $request['suggestion']['name'];
        $suggestnew->gender = $request['suggestion']['gender'];
        $suggestnew->origin_id = $request['suggestion']['origin'];
        $suggestnew->meaning = $request['suggestion']['meaning'];
        $suggestnew->exists = false;

        $suggestnew->save();
        return response()->json('',200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        // if name exists return error
        $nameExists = BabyName::where('name', '=', $request['name'] )->exists();
        $suggestChange = $request['suggest_change'];

        if ($nameExists && $suggestChange != true) {
            // return access denied
            return response()->json('Name already exists', 422);
        }

        $suggestChange = new SuggestName;
        $suggestChange->name = $request['name'];
        $suggestChange->meaning = $request['meaning'];
        $suggestChange->gender = $request['gender'];
        $suggestChange->origin_id = $request['origin_id'];
        $suggestChange->suggest_change = (boolean)$request['suggest_change'];

        $suggestChange->save();

//        $article = SuggestName::create($request->all());
        return response()->json('',200);
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
