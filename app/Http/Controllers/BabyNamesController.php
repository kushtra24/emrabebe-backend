<?php

namespace App\Http\Controllers;

use App\Models\BabyName;
use App\Models\Origin;
use App\Models\SuggestName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BabyNamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexAdmin(Request $request) {
        // $orderByArr = $request->input('sortBy', 'id');
        // $gender = $request->input('gender', null);
        // $origin = $request->input('origin', null);

        $page = $request->input('page');
        $limit = $request->input('limit', 5);
        $orderType = $request->input('sort', 'desc');
        $search = $request->input('search');

        $names = BabyName::select('id','name', 'meaning', 'meaning_de', 'meaning_al');

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
        $orderType = $request->input('sort', 'asc');
        $gender = $request->input('gender', null);
        $char = $request->input('char', null);
        $originIDs = $request->input('origin', null);
        $locale = $request->input('locale', 'en');

        $localeMeaning = 'meaning';
        if($locale == 'en') {
            $localeMeaning = 'meaning';
        } else {
            $localeMeaning = 'meaning_'.$locale;
        }


        $namesQuery = DB::table('baby_names')
                ->join('baby_name_origin', 'baby_names.id', '=', 'baby_name_origin.babyName_id')
                ->select('id', 'name', $localeMeaning.' as meaning', 'origin_id')
                ->whereColumn('baby_names.id', '=', 'baby_name_origin.babyName_id')
                ->where('baby_names.gender_id', '=', $gender);

                if($char || $gender == 0) {
                    $this->filterNamesByChar($namesQuery, $char);
                }
                
                if($originIDs || $gender == 0) {
                    $this->filterNamesByOrigin($namesQuery, $originIDs);
                } else {
                    return response()->json([
                        'message' => 'Origin not found'
                    ], 404);
                }

                $namesQuery = $namesQuery->orderBy('id', $orderType);
                $namesQuery = $namesQuery->get();


        return response()->json($namesQuery, 200);
    }


    /**
     * @param $query
     * @param $originId
     * @return mixed
     */
    public function filterNamesByOrigin(&$query, $originIds) {
        if (!isset($query)) { return $query; }
        if (!is_null($originIds)) {
            
            $OriginsArray = explode(',', $originIds);
            $conditions = array();

            foreach ($OriginsArray as $origin) {
                $conditions[] = "origin_id = " . $origin;
            }
            $conditions_string = implode(' OR ', $conditions);
            $conditions_string = "(" . $conditions_string . ")";
            $query = $query->whereRaw($conditions_string);
        }
        return $query;
    }

    /**
     * @param $query
     * @param $char
     * @return mixed
     */
    public function filterNamesByChar(&$query, $char) {
        if (!isset($query)) { return $query; }
        if (!is_null($char)) {
            $lettersArray = explode(',', $char);
            $conditions = array();
            foreach ($lettersArray as $letter) {
                $conditions[] = "name LIKE '$letter%'";
            }
            $conditions_string = implode(' OR ', $conditions);
            $conditions_string = "(" . $conditions_string . ")";
            $query = $query->whereRaw($conditions_string);
        }
        return $query;
    }

    function convert_csv_to_psv($csv_string) {
        $psv_string = str_replace(',', '|', $csv_string);
        return $psv_string;
    }


    /**
     * @param $query
     * @param $gender
     * @return mixed
     */
    public function filterNamesByGender(&$query, $gender) {
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
        // $babyName->setAttribute('origins', $babyName->origins);
        
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
        $babyName = BabyName::find($id);
        $babyName->delete();
        return response()->json('Deleted', 200);
    }


    /**
     * get
     * @param $query
     * @return array
     */
    public function getNameOrigins(&$query) {

        foreach($query as $babyName) {
            if (isset($babyName)) {
               $babyName->origins;
            }
        }
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

}
