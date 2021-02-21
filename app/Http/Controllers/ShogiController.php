<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShogiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            // POSTでのアクセス時の処理
            $MyKing = $request->input('move');
        } else {
            // GETでのアクセス時の処理
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
}
