<?php

namespace App\Http\Controllers;


use App\Models\Comment;
use App\Models\Post;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post)
    {
        return view('comments.create', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CommentRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request, Post $post)

    {
        $comment = new Comment($request->all());
        $comment->user_id = $request->user()->id;

        // トランザクション開始
        // DB::beginTransaction();
        try {
            // 登録 情報がすべて揃う
            $post->comments()->save($comment);

            // トランザクション終了(成功)
            DB::commit();
            // } catch (\Exception $e) {
        } catch (\Throwable $th) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($th->getMessage());
        }

        return redirect()
            ->route('posts.show', $post)
            ->with('notice', 'コメントを登録しました');

        // Exception はエラーを表している
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    // public function edit(Comment $comment)
    public function edit(Post $post, Comment $comment)
    {
        //
        return view('comments.edit', compact('post', 'comment'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CommentRequest  $request
     * @param  \App\Models\Post  $post
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    // public function update(CommentRequest $request, Comment $comment)
    public function update(CommentRequest $request, Post $post, Comment $comment)
    {
        //
        if ($request->user()->cannot('update', $comment)) {
            return redirect()->route('posts.show', $post)
                ->withErrors('自分のコメント以外は更新できません');
        }

        $comment->fill($request->all());

        // トランザクション開始
        // DB::beginTransaction(); 一つの処理なので不要。
        try {
            // 更新
            $comment->save();

            // トランザクション終了(成功)
            // DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            // DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('posts.show', $post)
            ->with('notice', 'コメントを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Comment $comment)
    public function destroy(Request $request, Post $post, Comment $comment)
    {
        if ($request->user()->cannot('delete', $comment)) {
            return redirect()->route('posts.show', $post)
                ->withErrors('自分のコメント以外は削除できません');
        }
        // トランザクション開始
        DB::beginTransaction();
        try {
            $comment->delete();

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('posts.show', $post)
            ->with('notice', 'コメントを削除しました');
    }
}
