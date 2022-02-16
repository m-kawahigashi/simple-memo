<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use App\Models\MemoTag;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ログインしているユーザーの登録メモ一覧を取得
        $memos = Memo::select('memos.*')
                ->where('user_id', '=', Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('updated_at', 'DESC')
                ->get();

        // ログインしているユーザーのタグを取得
        $tags = Tag::where('user_id', Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC')->get();

        // 取得したメモ一覧とタグをビューに返す
        return view('create', compact('memos', 'tags'));
    }

    public function store(Request $request)
    {
        // 入力された新規メモの受け取り
        $posts = $request->all();

        DB::transaction(function() use($posts){
            // 受け取ったデータを登録し、登録したIDを返す
            $memo_id = Memo::insertGetId(['content' => $posts['content'], 'user_id' => Auth::id()]);

            // 新規タグの存在チェック
            $tag_exists = Tag::where('user_id', '=', Auth::id())->where('name', '=', $posts['new_tag'])->exists();

            // 新規タグが存在した場合の処理
            if ( !empty($posts['new_tag']) || $posts['new_tag'] === 0 && !$tag_exists ) {
                $tag_id = Tag::insertGetId(['name' => $posts['new_tag'], 'user_id' => Auth::id()]);
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }

            // 既存タグチェック有無確認
            if( !empty($posts['tags'][0]) ) {
                // 既存タグにチェックが入った場合、メモと紐づけを行う
                foreach($posts['tags'] as $tag){
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
                }
            }
        });

        // ホーム画面にリダイレクト
        return  redirect('home');
    }

    public function edit($id)
    {

        // create画面で編集を行うので、ここでもメモ一覧を取得
        $memos = Memo::select('memos.*')
        ->where('user_id', '=', Auth::id())
        ->whereNull('deleted_at')
        ->orderBy('updated_at', 'DESC')
        ->get();

        // 編集するメモを取得
        $edit_memo = Memo::find($id);

        // 取得したメモ一覧と編集データをビューに返す
        return view('edit', compact('memos','edit_memo'));
    }

    public function update(Request $request)
    {

        // 更新内容を取得
        $update_post = $request->all();

        // 更新処理
        Memo::where('id', $update_post['memo_id'])
            ->update(['content' => $update_post['content']]);

        return redirect( route('home') );

    }

    public function destroy(Request $request)
    {
        // 削除対象を取得
        $delete_memo = $request->all();

        // 削除処理（論理削除）
        Memo::where('id', $delete_memo['memo_id'])
            ->update(['deleted_at' => date('y-m-d H:i:s', time())]);

        return redirect( route('home') );

    }

}
