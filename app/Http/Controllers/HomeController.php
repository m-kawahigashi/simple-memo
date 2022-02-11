<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use Illuminate\Support\Facades\Auth;

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
                ->orderBy('id', 'DESC')
                ->get();

        // 取得したメモ一覧をビューに返す
        return view('create', compact('memos'));
    }

    public function store(Request $request)
    {
        // 入力された新規メモの受け取り
        $posts = $request->all();

        // 受け取ったデータをDBに登録
        Memo::insert(['content' => $posts['content'], 'user_id' => Auth::id()]);

        // ホーム画面にリダイレクト
        return  redirect('home');
    }

    public function edit($id)
    {

        // create画面で編集を行うので、ここでもメモ一覧を取得
        $memos = Memo::select('memos.*')
        ->where('user_id', '=', Auth::id())
        ->whereNull('deleted_at')
        ->orderBy('id', 'DESC')
        ->get();

        // 編集するメモを取得
        $edit_memo = Memo::find($id);

        // 取得したメモ一覧と編集データをビューに返す
        return view('edit', compact('memos','edit_memo'));
    }

}
