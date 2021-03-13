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
            if (!empty($request->input('move'))) {
                $data = new GameRecord;
                $data->turn = $request->input('turn'); // 手番
                $data->piece = $request->input('piece'); // 駒
                $data->square = $request->input('move'); // 移動先マス
                $data->save();
            }
        }
        // 先手番の最新手を取得
        $bKing = $this->getLastRecord(0, 0);
        $bPawn = $this->getPawn(0);
        // 後手番の最新手を取得
        $wKing = $this->getLastRecord(1, 0);
        $wPawn = $this->getPawn(1);
        $nextTurn = $this->getNextTurn();
        return view('shogi/index', compact('bKing', 'wKing', 'nextTurn', 'bPawn', 'wPawn'));
    }

    public function select($piece)
    {
        // $pieceには 0:0:5:9 のように手番、駒番、列、行が「:」でくっついた形が入る。
        $selectPiece = array();
        $select = explode(":", $piece);
        $selectPiece = array(
            'turn' => $select[0],
            'piece' => $select[1], // 0 => 王, 1~9 => 歩
            'square' => $select[2] . $select[3],
        );
        if ($select[0] == 0) {
            // 先手番の処理
            $bKing = $this->getLastRecord(0, 0, $selectPiece);
            $wKing = $this->getLastRecord(1, 0, $selectPiece);
            $bPawn = $this->getPawn(0);
            $wPawn = $this->getPawn(1);
        } elseif ($select[0] == 1) {
            // 後手番の処理
            $wKing = $this->getLastRecord(1, 0, $selectPiece);
            $bKing = $this->getLastRecord(0, 0, $selectPiece);
            $bPawn = $this->getPawn(0);
            $wPawn = $this->getPawn(1);
        }
        // 移動可能マス配列としてselectページに渡す
        $ways = array();
        // 王将の移動可能マスを割り出して、$waysに追加する。
        if ((int)$selectPiece['piece'] == 0) {
            for ($c = $select[3]-1; $c < $select[3]+2 ; $c++) {
                for ($r = $select[2]-1; $r < $select[2]+2 ; $r++) {
                    // 移動不可能マスはスキップする
                    if ($selectPiece['square'] == $r . $c) {
                        continue;
                    } elseif ($select[0] == 0) {
                        if ($r . $c == $bKing['square'] || $r . $c == $bPawn[0][$r]['square']) {
                            continue;
                        }
                    } elseif ($select[0] == 1) {
                        if ($r . $c == $wKing['square'] || $r . $c == $wPawn[1][$r]['square']) {
                            continue;
                        }
                    }
                    $ways[] = $r . $c;
                }
            }
        } elseif ((int)$selectPiece['piece'] >= 1 && (int)$selectPiece['piece'] <= 9) {
            if ($selectPiece['turn'] == 0) {
                $ways[] = $select[2] . ((int)$select[3])-1;
            } elseif ($selectPiece['turn'] == 1) {
                $ways[] = $select[2] . ((int)$select[3])+1;
            }
        }
        $way = array_unique($ways);
        return view('shogi/select', compact('bKing', 'wKing', 'way', 'bPawn', 'wPawn', 'selectPiece'));
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

    public function getNextTurn()
    {
        $lastData = GameRecord::orderBy('id', 'desc')->first();
        if (!isset($lastData['turn']) || ($lastData['turn'] == 1) ) {
            $nextTurn = array(
                'turn' => 0,
                'name' => '▲先手',
                'black' => 'selected',
                'white' => 'disabled',
            );
        } else {
            $nextTurn = array(
                'turn' => 1,
                'name' => '△後手',
                'black' => 'disabled',
                'white' => 'selected',
            );
        }
        return $nextTurn;
    }

    public function getPawn($turn)
    {
        // 歩を取得
        $pawnArray = array();
        for ($i=1; $i<10; $i++) {
            $lastPawn = GameRecord::where('turn', $turn)->where('piece', $i)->orderBy('id', 'desc')->first();
            $pawn = (!empty($lastPawn['square'])) ? $lastPawn : config('const.start.' . $turn . '.' . $i);
            $pawnArray[$turn][$i] = $pawn;
        }
        return $pawnArray;
    }

    public function getLastRecord($turn, $pieceId, $selectPiece = null)
    {
        // 駒の位置を取得
        if (!empty($selectPiece) && $selectPiece['turn'] == $turn && $selectPiece['piece'] == $pieceId) {
            $record = $selectPiece;
        } else {
            $lastRecord = GameRecord::where('turn', $turn)->where('piece', $pieceId)->orderBy('id', 'desc')->first();
            $record = (!empty($lastRecord['square'])) ? $lastRecord : config('const.start.' . $turn . '.' . $pieceId);
        }

        return $record;
    }
}
