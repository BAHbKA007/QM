<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Comment;
use Illuminate\Http\Request;
use Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $kommentare = DB::select('  SELECT
                                        kundes.name AS kunde,
                                        comment,
                                        created.name AS created_name,
                                        comments.created_at,
                                        comments.id,
                                        erledigt,
                                        edited.name AS edited_name,
                                        comments.updated_at
                                    FROM
                                        comments
                                    JOIN kundes ON comments.kunde_id = kundes.id
                                    LEFT JOIN users created ON
                                        created.id = comments.user_id
                                    LEFT JOIN users edited ON
                                        edited.id = comments.user_aenderung_id
                                    WHERE
                                        zaehlung_id = ?',[$id]);

        return view('zaehlung.comments')->with('var', [
            'kommentare' => $kommentare
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function erledigen(Request $request)
    {
        $comment =  Comment::find($request->id);
        
        if ($comment->erledigt == 0) {
            $comment->erledigt = 1;
            $comment->user_aenderung_id = Auth::user()->id;
        } else {
            $comment->erledigt = 0;
            $comment->user_aenderung_id = Auth::user()->id;
        }

        $comment->save();

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // wenn Kommentar leer abgeschickt wurde (requered bring bei IOS nichts)
        if ( strlen($request->comment) == 0 ) {
            return back()->with('status', ['error' => 'Kommentar darf nicht leer sein!']);
        }
        
        $comment_find = DB::select('SELECT id, comment FROM comments WHERE kunde_id = ? AND zaehlung_id = ?',[$request->kunde_id,$request->zaehlung_id,]);
        
        if (count($comment_find) != 0) {

            $comment = Comment::find($comment_find[0]->id);
            $comment->comment = $request->comment;
            $comment->erledigt = 1;
            $comment->save();

        } else {

            $comment = new Comment;
            $comment->zaehlung_id = $request->zaehlung_id;
            $comment->kunde_id = $request->kunde_id;
            $comment->comment = $request->comment;
            $comment->user_id = Auth::user()->id;
            $comment->save();

        }

        return redirect("/zaehlung/$request->zaehlung_id/kunde/$request->kunde_id")->with('status', ['success' => 'Kommentar erfolgreich']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
