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
            $data = new GameRecord;
            $data->turn = $request->input('turn'); // 手番
            $data->piece = 0; // 王将
            $data->square = $request->input('move'); // 移動先マス
            $data->save();
        }
        // 先手番の最新手を取得
        $bLast = GameRecord::where('turn', 0)->orderBy('id', 'desc')->first();
        $bKing = (!empty($bLast['square'])) ? $bLast : config('const.start.bKing');
        // 後手番の最新手を取得
        $wLast = GameRecord::where('turn', 1)->orderBy('id', 'desc')->first();
        $wKing = (!empty($wLast['square'])) ? $wLast : config('const.start.wKing');
        return view('shogi/index', compact('bKing', 'wKing'));
    }

    public function select($piece)
    {
        $gameRecord = GameRecord::query()->orderBy('id', 'desc');
        // $pieceには 0:0:5:9 のように手番、駒番、列、行が「:」でくっついた形が入る。
        $king = array();
        $select = explode(":", $piece);
        $king = array(
            'turn' => $select[0],
            'piece' => $select[1],
            'square' => $select[2] . $select[3],
        );
        // 移動可能マス配列としてselectページに渡す
        $ways = array();
        // 王将の移動可能マスを割り出して、$waysに追加する。
        for ($c = $select[3]-1; $c < $select[3]+2 ; $c++) {
            for ($r = $select[2]-1; $r < $select[2]+2 ; $r++) {
                $ways[] = $r . $c;
            }
        }
        $way = array_unique($ways);
        if ($select[0] == 0) {
            // 先手番の処理
            $bKing = $king;
            $wLast = GameRecord::where('turn', 1)->orderBy('id', 'desc')->first();
            $wKing = (!empty($wLast['square'])) ? $wLast : config('const.start.wKing');
            $turn = 0;
        } elseif ($select[0] == 1) {
            // 後手番の処理
            $wKing = $king;
            $bLast = GameRecord::where('turn', 0)->orderBy('id', 'desc')->first();
            $bKing = (!empty($bLast['square'])) ? $bLast : config('const.start.bKing');
            $turn= 1;
        }
        return view('shogi/select', compact('bKing', 'wKing', 'way', 'turn'));
    }

    public function reset()
    {
        if (GameRecord::query()->delete()) {
            echo '削除しました。';
        } else {
            echo '削除に失敗しました。';
        }
        return redirect('shogi');
    }
}
