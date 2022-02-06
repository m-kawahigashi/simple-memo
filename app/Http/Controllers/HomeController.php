<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

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
        return view('create');
    }

    public function store(Request $request)
    {
        // 入力された新規メモの受け取り
        $posts = $request->all();

        // 受け取ったデータをDBに登録
        Memo::insert(['content' => $posts['content'], 'user_id' => \Auth::id()]);

        // ホーム画面にリダイレクト
        return  redirect('home');
    }

}
