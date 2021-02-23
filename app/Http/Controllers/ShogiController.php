<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameRecord;

class ShogiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            // POSTでのアクセス時の処理
            $MyKing = $request->input('move');
            $data = new GameRecord;
            $data->turn = 0;
            $data->piece = 0;
            $data->square = $MyKing;
            if ($data->save()) {
                $bKing = GameRecord::query();
                // $bKing->whereColumn(['turn', 0], ['piece', 0]);
                if ($bKing->orderBy('id', 'desc')->first()) {
                    echo $bKing->first();
                }
                var_dump('保存しました。');
            } else {
                var_dump('保存に失敗しました。');
            }
        } else {
            // GETでのアクセス時の処理
            $bKing = GameRecord::query();
            // $bKing->whereColumn(['turn', 0], ['piece', 0]);
            echo $bKing->orderBy('id', 'desc')->first();
            $MyKing = '59';
        }
        return view('shogi/index', compact('MyKing'));
    }

    public function select($piece)
    {
        // $pieceには King:5:9 のように駒名、列、行が「:」でくっついた形が入る。
        $select = explode(":", $piece);
        $pieceName = $select[0];
        $row = $select[1];
        $column = $select[2];
        $MyKing = $row . $column;
        $ways = array();
        // 王将の移動可能マスを割り出して、$waysに追加する。
        for ($c = $column-1; $c < $column+2 ; $c++) {
            for ($r = $row-1; $r < $row+2 ; $r++) {
                $ways[] = $r . $c;
            }
        }
        // 重複削除
        $way = array_unique($ways);
        // 移動可能マスを $way という配列に入れてselectページに渡す
        return view('shogi/select', compact('MyKing', 'way'));
    }

    public function reset()
    {
        if (GameRecord::query()->delete()) {
            echo '削除しました。';
        } else {
            echo '削除に失敗しました。';
        }
    }
}
