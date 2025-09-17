<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Illuminate\Support\Facades\Input; // ← 未使用なので削除
use App\Http\Requests\HelloRequest;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Person;

class CardGameController extends Controller
{
    public function form() {
        return view('card.form'); // 単純な <input type="file"> フォーム
    }

    public function create(Request $r) {
        $r->validate([
            'image' => 'required|image|max:4096', // 4MB
            'name'  => 'nullable|string|max:30'
        ]);

        // 一時保存
        $tmp = $r->file('image')->store('tmp', 'local');
        $tmpPath = storage_path('app/'.$tmp);

        // （任意）テキストの簡易モデレーション：名前/説明に不適切ワードがないか
        // OpenAI Moderation API（omni-moderation-latest）を利用するのがベター
        // ここでは割愛。※本番は必ず入れてください。:contentReference[oaicite:2]{index=2}

        // 1) ユーザー画像を“TCG用イラスト”へスタイライズ（image edit/variation相当）
        $prompt = "Create a high-quality anime-style trading card portrait from the input photo. "
                . "Center the main subject, vivid lighting, crisp edges, no text, no watermark. "
                . "Fantasy/monster vibe is OK. 3:4 aspect if possible.";

        // 画像生成API呼び出し（multipart）
        $res = Http::withToken(env('OPENAI_API_KEY'))
            ->asMultipart()
            ->post('https://api.openai.com/v1/images/edits', [ // gpt-image-1 の編集/スタイル変換を利用
                ['name' => 'model',  'contents' => env('OPENAI_IMAGE_MODEL', 'gpt-image-1')],
                // マスク無しでも“参照画像付き編集”として利用可。難しければ variations を使う実装に切替OK
                ['name' => 'image[]','contents' => fopen($tmpPath, 'r'), 'filename' => basename($tmpPath)],
                ['name' => 'prompt','contents' => $prompt],
                ['name' => 'size',  'contents' => '1024x1536'],
                // （任意）'background' => 'transparent' が使える場合は透過化も
            ]);

        if (!$res->ok()) {
            return back()->withErrors(['image' => '生成に失敗しました: '.$res->body()]);
        }
        $b64 = data_get($res->json(), 'data.0.b64_json');
        $artPng = base64_decode($b64);
        $id = (string) Str::uuid();
        $artPath = storage_path("app/public/cards/{$id}-art.png");
        @mkdir(dirname($artPath), 0775, true);
        file_put_contents($artPath, $artPng);

        // 2) サーバ側でカード枠に合成＆テキスト描画
        // テンプレ枠: resources/card_templates/tcg_frame.png（公式風にしすぎない）
        // フォント: resources/fonts/NotoSansJP-Regular.ttf
        $canvas = imagecreatefrompng(resource_path('card_templates/tcg_frame.png'));
        $art    = imagecreatefrompng($artPath);

        // アートを枠の窓にリサイズ配置（座標はテンプレに合わせて調整）
        $dstX=80; $dstY=160; $dstW=864; $dstH=1152; // 例
        $out = imagecreatetruecolor(imagesx($canvas), imagesy($canvas));
        imagealphablending($out, true); imagesavealpha($out, true);
        imagecopy($out, $canvas, 0, 0, 0, 0, imagesx($canvas), imagesy($canvas));
        imagecopyresampled($out, $art, $dstX, $dstY, 0, 0, $dstW, $dstH, imagesx($art), imagesy($art));

        // テキスト
        $name = $r->input('name') ?: '新規カード';
        $font = resource_path('fonts/NotoSansJP-Regular.ttf');
        $white = imagecolorallocate($out, 255,255,255);
        imagettftext($out, 36, 0, 100, 120, $white, $font, $name); // カード名
        // ざっくりATK/DEF（サイコロ風）
        $atk = rand(800, 3000); $def = rand(600, 3000);
        imagettftext($out, 28, 0, 700, 1470, $white, $font, "ATK {$atk} / DEF {$def}");

        $finalPath = storage_path("app/public/cards/{$id}.png");
        imagepng($out, $finalPath);
        imagedestroy($out); imagedestroy($canvas); imagedestroy($art);

        return redirect("/card/{$id}");
    }

    public function show($id) {
        $url = asset("storage/cards/{$id}.png");
        return view('card.show', ['url' => $url]);
    }
}