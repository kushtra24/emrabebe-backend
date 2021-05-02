<?php

namespace App\Http\Controllers;

use App\Models\BabyName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BabyNamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {


        $page = $request->input('page');
        $limit = $request->input('limit', 5);
        $orderType = $request->input('sort', 'desc');
        $orderByArr = $request->input('sortBy', 'id');
        $gender = $request->input('gender', null);
        $char = $request->input('char', null);
        $origin = $request->input('origin', null);

        $names = BabyName::select('*');

        $this->filterByGender($names, $gender);
        $this->filterByChar($names, $char);
        $this->filterByOrigin($names, $origin);

        $names = $this->executeQuery($names, $page, $limit, $orderType);

        return response()->json($names, 200);
    }


    /**
     * @param $query
     * @param $origin
     * @return mixed
     */
    public function filterByOrigin(&$query, $origin) {
        if (!isset($query)) { return $query; }
        if (!is_null($origin)) {
            $query = $query->where('origin_id', $origin);
        }
    }

    /**
     * @param $query
     * @param $char
     * @return mixed
     */
    public function filterByChar(&$query, $char) {
        if (!isset($query)) { return $query; }
        if (!is_null($char)) {
            $query = $query->where('name', 'LIKE', $char.'%');
        }
    }


    /**
     * @param $query
     * @param $gender
     * @return mixed
     */
    public function filterByGender(&$query, $gender) {
        if (!isset($query)) { return $query; }
        if (!is_null($gender)) {
           $query = $query->where('gender_id', $gender);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $article = BabyName::create($request->all());
        return response()->json($article, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $babyName = BabyName::findOrFail($id);
        return response()->json($babyName, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $article = BabyName::find($id);
        $article->update($request->all());

        return response()->json('updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $article = BabyName::find($id);
        $article->delete();
        return response()->json('Deleted', 200);
    }
}
