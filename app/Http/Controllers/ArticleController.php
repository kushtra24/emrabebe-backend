<?php

namespace App\Http\Controllers;

use App\Models\article;
use Illuminate\Http\Request;
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

        $page = $request->input('page');
        $limit = $request->input('limit', null);
        $category_id = $request->input('cat_id');
        $orderType = $request->input('sort', 'desc');
        $orderByArr = $request->input('sortBy', 'id');
        $lang = $request->input('lang', 'en');

//        $articles = new Article;
        $articles = Article::select('*');

        if(isset($lang)) {
            $this->filterArticleByLanguage($articles, $lang);
        }

        if(isset($category_id)) {
            $this->filterArticleByCategory($articles, $category_id);
//            $orderByArr = 'title';
        }

//        $this->checkArticleSearch($articles, $search); // check for search

        $articles = $this->executeQuery($articles, $page, $limit, $orderType, $orderByArr); // execute the query


        foreach($articles as $article) {
            if (isset($article)) {
                $article['categories'] = $article->category;
            }
        }

        return response()->json($articles,200);
    }


    public function randomArticles() {

        $articles = Article::all()->random(4);

        return response()->json($articles,200);
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
     * @param $category_id
     * @return mixed
     */
    private function filterArticleByCategory(&$query, $category_id) {

        $this->checkIfQueryIsNotSet($query);

        if (!is_null($category_id)) {
            $query = $query->whereIn('articles.id', Article::select('article_id')->from('article_category')->where('category_id', $category_id ));
        }
    }


    private function filterArticleByLanguage(&$query, $lang) {
        $this->checkIfQueryIsNotSet($query);

        if(!is_null($lang)) {
//            $array = explode(' ', $lang);
            #arreyVal = array_values($array)[0]
            $query = $query->where('language', $lang);
        }
    }

//SELECT * FROM articles
//WHERE id IN
//(SELECT articleID FROM catart WHERE categoryID = 1)
//ORDER BY id;



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {

        // validate the request
        $this->validate($request, $this->rules);

        // get the slug from the request
        $slug = $request['slug'];

        // check if slug already exists if so add and Id to the slug
        if(Article::where('slug', '=', $slug)->exists()) {
            // get how many articles are posted
            $numberOfArticles = Article::count();
            // merge the number of articles +1 to the slug
            $request->merge(['slug' => ($slug . "-" . ($numberOfArticles + 1))]);
        }

        // get the photo from the request
        $requestedPhoto = $request['photo'];
        // check if requested photo is not an empty string and does not contain storage in it
        if ( $requestedPhoto != '' && !Str::contains($requestedPhoto, 'storage') ) {
            $this->updatePhoto($request, $requestedPhoto, 'article');
        }

        // merge the Auth in user on the request
        $request->merge(['user_id' => auth()->user()->id]);

        // create user
        $article = Article::create($request->all());

        // attach categories of article
        $article->category()->attach($request['category_id']);

        // return json response with user
        return response()->json($article, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug) {

        // check if slug is set
        if (!isset($slug)) {
            throw new \InvalidArgumentException('Bad argument: Important credential \'slug\' is in bad format.', 400);
        }

        $article = Article::where('slug', $slug)->first();

        // get the category of the post and add it to the article query
        if (isset($article->category)) {
            $here = $article->category->pluck('id');
            $article['category_id'] = $here;
        }

        return response()->json($article, 200);
    }



    public function update(Request $request, $id)
    {
        // check if is admin or author
        if (!$id) {
            // return access denied
            throw new \InvalidArgumentException('No Article Id provided', 403);
        }

        $article = Article::find($id);

        // get the slug from the request
        $slug = $request['slug'];

        // check if slug already exists if so add and Id to the slug
//        if(Article::where('slug', '=', $slug)->exists()) {
        if(Article::whereSlug('slug')->exists()) {
            // get how many articles are posted
            $numberOfArticles = $request['id'];
            // merge the number of articles +1 to the slug
            $request->merge(['slug' => ($slug . "-" . ($numberOfArticles))]);
        }

//         get current uploaded photo from DB
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
        $article->category()->attach($request['category_id']);

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

        // get the user
        $article = Article::where('slug', $slug)->first();

        // gelete user
        $article->delete();

        // delete the old photo from the storage
        // delete the old photo from the storage
//        $this->deleteStoragePhoto('article', $article->photo);
//        // attach categories of article
        $article->category()->detach();

        return response()->json('Deleted', 200);
    }

}

