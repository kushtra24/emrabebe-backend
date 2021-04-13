<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $messages = Message::all();

        return response()->json($messages, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $messages = Message::create($request->all());
        return response()->json($messages, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $messages
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);
        return response()->json($message, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $messages
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $article = Message::find($id);
        $article->update($request->all());

        return response()->json('updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $messages
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $article = Message::firstOrFail($id);
        $article->delete();
        return response()->json('Deleted', 200);
    }
}
