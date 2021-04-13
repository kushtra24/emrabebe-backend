<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {

        $page = $request->input('page', null); // only needed to check if pagination is wanted

        $users = User::select('*');

        Log::info(var_export('here', true));
        $users = $this->executeQuery($users, $page); // execute the query
        Log::info(var_export('here 2', true));

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
        $article = User::firstOrFail($id);
        $article->delete();
        return response()->json('Deleted', 200);
    }

}
