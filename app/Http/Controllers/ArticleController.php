<?php

namespace App\Http\Controllers;

use App\Models\article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleController extends Controller {
    /**
     * validation rules
     * @var string[]
     */
    private $rules = [
        'title' => 'required|string',
        'content' => 'required|string',
        'slug' => 'required|string',
    ];


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {

        Log::info(var_export('here', true));

        $page = $request->input('page', null); // only needed to check if pagination is wanted
        $limit = $request->input('limit', null);
        $search = $request->input('search');
        $category = $request->input('cat', null);
        $orderType = $request->input('order-type', 'desc'); // order type
        $orderByArr = $request->input('order-by', 'created_at, title'); // default order
        $orderByArr = $this->stringToArray($orderByArr); // to array

        $articles = Article::select('*');

        Log::info(var_export('here 2', true));

//        $this->filterArticleByCategory($articles, $category); // filter user type
//        $this->checkArticleSearch($articles, $search); // check for search

        $articles = $this->executeQuery($articles, $page, $limit, $orderByArr, $orderType); // execute the query
        Log::info(var_export('here 3', true));

        return response()->json($articles, 200);
    }


    /**
     * find the searched contract
     * @param $query
     * @param $search
     * @return mixed
     */
    private function checkArticleSearch(&$query, $search) {


        $this->checkIfQueryIsNotSet($query);

        if (!is_null($search)) {
            $searchTerms = $this->stringToArray($search, ' ');

            $query = $query->where(function ($query) use ($searchTerms) {
                for ($i = 0, $max = count($searchTerms); $i < $max; $i++) {
                    $term = str_replace('_', '\_', mb_strtolower('%' . $searchTerms[$i] . '%'));
                    $query->whereRaw("(Lower(title) LIKE ?)", [$term, $term])
                        ->orWhereRaw("(Lower(content) LIKE ?)", [$term, $term]);
                }
            });
        }
    }


    /**
     * check contract type
     * @param $query
     * @param $cat
     * @return mixed
     */
    private function filterArticleByCategory(&$query, $cat) {
        $this->checkIfQueryIsNotSet($query);

        if (!is_null($cat)) {
            //join Article with category_article and get category_article.category_id
            $query = $query->join('category_article', 'articles.id', '=', 'category_article.article_id');
            $query = $query->where('category_id', $cat);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $article
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {

        // create user
        $article = Article::create($request->all());

        // attach categories of article
//        $article->category()->attach($request['cat']);

        // return json response with user
        return response()->json($article, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {

        // check if slug is set
//        if (!isset($slug)) {
//            throw new \InvalidArgumentException('Bad argument: Important credential \'slug\' is in bad format.', 400);
//        }

        // get article from slug
        $article = Article::findOrFail($id);

        // get the category of the post and add it to the article query
//        if (isset($article->category)) {
//            $here = $article->category->pluck('id');
//            $article['cat'] = $here;
//        }

        return response()->json($article, 200);
    }



    public function update(Request $request, $id)
    {
        // check if is admin or author
//        if (!Gate::allows('isAdmin') || !Gate::allows('isAuthor')) {
//            // return access denied
//            throw new \InvalidArgumentException('You do not have enough privileges to delete an article', 400);
//        }

        $article = Article::find($id);

        // get authenticated user my Id
        $AuthenticatedUser = Auth::id();

        // merge the Auth in user on the request
        $request->merge(['user_id' => '1']);

        // get current uploaded photo from DB
        $currentPhoto = $article->photo;
        $requestedPhoto = $request['photo'];
        // check if requested photo is not the same as the photo on the db, is not an empty string and does not contain storage in it
        if ($requestedPhoto != $currentPhoto && $requestedPhoto != '' && !Str::contains($requestedPhoto, 'storage') ) {
            $this->updatePhoto($request, $requestedPhoto, 'article', $currentPhoto);
        } else {
            $request->merge(['photo' => $currentPhoto]);
        }

        $article->update($request->all());

        // attach categories of article
        $article->category()->detach();
        $article->category()->attach($request['cat']);

        return response()->json('updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($slug)
    {
        // check if is admin or author
        if (!Gate::allows('isAdmin') || !Gate::allows('isAuthor')) {
            // return access denied
            throw new \InvalidArgumentException('You do not have enough privileges to delete an article', 400);
        }

        // get the user
        $article = Article::where('slug', $slug)->firstOrFail();

        // gelete user
        $article->delete();

        // delete the old photo from the storage
        // delete the old photo from the storage
        $this->deleteStoragePhoto('article', $article->photo);
        // attach categories of article
        $article->category()->detach();

        return response()->json('Deleted', 200);
    }
}

