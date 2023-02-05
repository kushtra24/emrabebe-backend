<?php

namespace App\Http\Controllers;

use App\Models\BabyName;
use App\Models\Origin;
use App\Models\SuggestName;
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
    public function indexAdmin(Request $request) {

        $page = $request->input('page');
        $limit = $request->input('limit', 5);
        $orderType = $request->input('sort', 'desc');
        $orderByArr = $request->input('sortBy', 'id');
        $gender = $request->input('gender', null);
        $search = $request->input('search');
//        $origin = $request->input('origin', null);

        $names = BabyName::select('id','name', 'description', 'meaning', 'created_at');

        if(isset($search)) {
            $this->returnSearch($names, $search);
        }

        $names = $this->executeQuery($names, $page, $limit, $orderType);

//        $this->getNameOrigins($names);

        return response()->json($names, 200);
    }


    /**
     * @param $query
     * @param $search
     * @return mixed
     */
    public function returnSearch(&$query, $search) {

        if (!isset($query)) {
            return $query;
        }

        if (!is_null($search)) {
            $searchTerms = $this->stringToArray($search, ' ');
//            $query->leftJoin($bigPictureTable . '.customer', $localDb . '.contracts.customer_id', '=', $bigPictureTable .'.customer.id');
            $query = $query->where(function ($query) use ($searchTerms) {
                for ($i = 0, $max = count($searchTerms); $i < $max; $i++) {
                    $term = str_replace('_', '\_', mb_strtolower('%' . $searchTerms[$i] . '%'));
                    $query->whereRaw("(Lower(name) LIKE ?)", [$term, $term]);
                }
            });
//            $this->orderByArr = 'end_date';
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {

        $page = $request->input('page');
        $limit = $request->input('limit', 5);
        $orderType = $request->input('sort', 'asc');
        $orderByArr = $request->input('sortBy', 'id');
        $gender = $request->input('gender', null);
        $char = $request->input('char', null);
        $origin = $request->input('origin', null);

        $names = BabyName::select('id','name', 'description', 'meaning', 'created_at');

        $this->filterByGender($names, $gender);
        $this->filterByChar($names, $char);
        if($origin) {
            $this->filterByOrigin($names, $origin);
        }

        $names = $this->executeQuery($names, null, null, $orderType);

        $this->getNameOrigins($names);


        return response()->json($names, 200);
    }


    /**
     * @param $query
     * @param $originId
     * @return mixed
     */
    public function filterByOrigin(&$query, $originId) {
        if (!isset($query)) { return $query; }
        if (!is_null($originId)) {
            $query->whereHas('origins',function($query) use($originId){
                $query->where('origin_id', $originId);
            });
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
        $babyName = new BabyName;
        $babyName->name = $request['name'];
        $babyName->description = $request['description'];
        $babyName->gender_id = $request['gender_id'];
        $babyName->meaning = $request['meaning'];
        $babyName->created_at = $request['created_at'];

        $babyName->save();

        // attach categories of article
        $babyName->origins()->attach($request['origin_id']);

        return response()->json($babyName, 200);
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

        if (isset($babyName->origins)) {
            $babyName['origins'] = $babyName->origins;
        }
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
        $babyName = BabyName::find($id);

        $babyName->update($request->all());
        // attach categories of article
        $babyName->origins()->detach();
        $babyName->origins()->attach($request['origin_id']);

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


    /**
     * get contract information from other models depending on the id
     * @param $query
     * @return array
     */
    public function getNameOrigins(&$query) {

        foreach($query as $article) {
            if (isset($article)) {
               $article->origins;
            }
        }
         //get the get data from Person depending on the id on the contract
//        if (isset($query) && is_countable($query)) {
//            foreach ($query as &$entity) {
//                if (!isset($entity)) { continue; }
//
//                if (isset($entity->origins)) {
//                    $originObjects = $entity->origins;
//                    $competition_all = [];
//                    foreach($originObjects as $object) {
//                        array_push($competition_all, $object->name);
//                    }
//                    return $entity->push((object)$competition_all);
//                }
//            }
//        }
    } //end getDataForIds

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Request
     */
    public function getSingleName(Request $request) {
        if (!isset($request)) {
            return $request;
        }

        $search = $request->input('search');
        $babyName = BabyName::where('name', $search)->first();

        if ($babyName == null) {
            return response()->json($babyName, 404);
        }
        if (isset($babyName->origins)) {
            $babyName['origins'] = $babyName->origins;
        }

        return response()->json($babyName, 200);
    }

    /**
     * increment favored on a name for statistics reasons
     * @param Request $request
     */
    public function incrementFavoredName(Request $request) {
        BabyName::where('id', $request['id'])->increment('favored');
    }

    private function belongsToMany($class)
    {
    }

}
