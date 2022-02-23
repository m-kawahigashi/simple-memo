<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Memo;
use App\Models\Tag;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 以下は、全ての処理が呼ばれる前に呼ばれるメソッド

        //文字コードの影響(mysql5.6なのが原因)があり、php artisan migrateするとエラー出るので以下を追記　2022/1/31
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // メモ呼び出しを共通化
        view()->composer('*', function($view){

            // 画面からきたクエリパラメータ取得
            $query_tag = Request::query('tag');
            // dd($query_tag);値取れてる

            // クエリパラメータがある場合
            if( !empty($query_tag) ){
                // クエリパラメータに紐づくメモ一覧のみを取得
                $memos = Memo::select('memos.*')
                    ->Leftjoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                    ->where('memo_tags.tag_id', '=', $query_tag)
                    ->where('user_id', '=', Auth::id())
                    ->whereNull('deleted_at')
                    ->orderBy('updated_at', 'DESC')
                    ->get();
            } else {
                // ない場合
                // ログインしているユーザーの全ての登録メモ一覧を取得
                $memos = Memo::select('memos.*')
                    ->where('user_id', '=', Auth::id())
                    ->whereNull('deleted_at')
                    ->orderBy('updated_at', 'DESC')
                    ->get();
            }
            

        // ログインユーザーのタグ一覧を取得
        $tags = Tag::where('user_id', '=', Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

            $view->with('memos', $memos)
                ->with('tags', $tags);

        });
    }
}
