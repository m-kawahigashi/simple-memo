<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class Memo extends Model
{
    use HasFactory;

    public function getMyMemo() {
            // 画面からきたクエリパラメータ取得
            $query_tag = Request::query('tag');

        // -------ベースクエリ-------------

            // ログインしているユーザーの全ての登録メモ一覧を取得
            $memo_query = Memo::query()->select('memos.*')
            ->where('user_id', '=', Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC');

        // ---------ここまで-------------

            // クエリパラメータがある場合は以下クエリを追加して処理
            if( !empty($query_tag) ){
                // クエリパラメータに紐づくメモ一覧のみを取得
                $memo_query->Leftjoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                            ->where('memo_tags.tag_id', '=', $query_tag);
            }
            // 配列で取得
            $memos = $memo_query->get();

        return $memos;
    }
}
