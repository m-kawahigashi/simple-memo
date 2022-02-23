<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Memo;
use App\Models\Tag;

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

            // メモを取得
            $memo_model = new Memo();
            $memos = $memo_model->getMyMemo();

            // ログインユーザーのタグ一覧を取得
            $tags = Tag::where('user_id', '=', Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

            $view->with('memos', $memos)->with('tags', $tags);

        });
    }
}
