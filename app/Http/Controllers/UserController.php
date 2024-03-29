<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticateationException;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {

        $page = $request->input('page');
        $limit = $request->input('limit', 5);
        $orderType = $request->input('sort', 'desc');
        $orderByArr = $request->input('sortBy', 'id');

        $users = User::select('*');

        $users = $this->executeQuery($users, $page, $limit, $orderType); // execute the query

        return response()->json($users, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        $user = User::create($request->all());
        return response()->json($user, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {

        // check if slug is set
        if (!isset($id)) {
            throw new \InvalidArgumentException('Bad argument: Important credential \'id\' is in bad format.', 400);
        }

        $user = User::find($id);
        return response()->json($user, 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id) {

        $article = User::find($id);
        $article->update($request->all());

        return response()->json('updated', 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) {
        $article = User::find($id);
        $article->delete();
        return response()->json('Deleted', 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Request
     */
    public function saveFavNNames(Request $request) {

        if(!auth()) {
            throw new AuthenticateationException();
        }

        $favNames = auth()->user();

        $here = $request['favNames'];

        $stack = [];
        foreach ($here as $hero) {
            $second = $hero['id'];
            array_push($stack, $second);
        }

        $favNames->babyName()->detach();
        $favNames->babyName()->attach($stack);

        return response()->json($favNames, 200);
    }

    public function getFavoriteBabyNames() {

        if(!auth()) {
            throw new AuthenticateationException();
        }

        $favNames = auth()->user();

        if (isset($favNames->babyName)) {
            $favNames['fav_names'] = $favNames->babyName;
        }

        return response()->json($favNames['fav_names'], 200);
    }

}
