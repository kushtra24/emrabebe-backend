<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function index(Request $request) {


        $page = $request->input('page', null); // only needed to check if pagination is wanted

        $users = User::select('*');

//        $this->filterArticleByCategory($users, $category); // filter user type
//        $this->checkArticleSearch($users, $search); // check for search

        Log::info(var_export('here', true));
        $users = $this->executeQuery($users, $page); // execute the query
        Log::info(var_export('here 2', true));

        return response()->json($users, 200);
    }

    public function show($id) {

        // check if slug is set
        if (!isset($id)) {
            throw new \InvalidArgumentException('Bad argument: Important credential \'id\' is in bad format.', 400);
        }

        $user = User::find($id);

        return response()->json($user, 200);
    }

}
