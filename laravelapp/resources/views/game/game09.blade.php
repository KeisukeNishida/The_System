<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, user-scalable=no">
    <title>English Learning Shooting Game</title>
    <style>
    /* 画面の実サイズを正しく取る */
html, body { height: 100%; }

body{
  margin: 0;
  padding: 0;
  background: #000;
  color: #fff;
  font-family: 'Arial', sans-serif;

  /* ← ここをflexにしない（transformで中央に置くため） */
  display: block;

  min-height: 100dvh;             /* モバイルのアドレスバー対策 */
  overflow: hidden;
  overscroll-behavior: none;
  -webkit-text-size-adjust: 100%;
  touch-action: manipulation;
}

/* ゲームは論理解像度 400x800 固定。表示はJSで拡縮＆中央寄せ */
.game-container{
  background-image: url("images/ookamichan-bg.png"); /* ← 相対パスに統一 */
  position: fixed;          /* 画面に固定 */
  left: 0; top: 0;
  width: 400px;
  height: 800px;
  transform-origin: top left;
}

/* キャンバスは入れ物いっぱいに */
/* キャンバスは透明にしておく */
#gameCanvas{ background: transparent !important; }




        /* ===== UI ===== */
        .game-ui {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 18px;
        z-index: 10;
        }
        .life-display { color: #ff4444; }
        .score-display { color: #44ff44; margin-top: 5px; }

        .instructions {
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 12px;
        color: #aaa;
        text-align: center;
        width: 90%;
        z-index: 15;
        }

        .answer-btn{
        background: rgba(255,255,255,0.2);
        color: #fff;
        border: 2px solid #fff;
        border-radius: 50%;
        width: 60px; height: 60px;
        font-size: 24px; font-weight: bold;
        cursor: pointer; user-select: none;
        display: flex; align-items: center; justify-content: center;
        }
        .answer-btn:active{
        background: rgba(255,255,255,0.4);
        transform: scale(0.95);
        }

        /* ゲームオーバーはオーバーレイ */
        .game-over{
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        background: rgba(0,0,0,0.8);
        padding: 20px;
        border-radius: 10px;
        display: none;
        z-index: 60;
        }
        .restart-btn{
        background: #4444ff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        margin-top: 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        }
        .restart-btn:hover{ background: #6666ff; }

        /* ===== クリア固定メッセージ（モーダルの代わり） ===== */
        .game-clear-banner{
        position: absolute;
        top: 56px;               /* ライフ/スコアの下あたり */
        left: 50%;
        transform: translateX(-50%);
        z-index: 40;
        font-size: 22px;
        font-weight: 900;
        letter-spacing: .5px;
        color: #fff;             /* 明るい文字色 */
        text-shadow:
            0 0 6px #fff,
            0 0 16px #fffa90,
            0 0 28px #ffb300;      /* ふわっと光る */
        padding: 4px 10px;
        border-radius: 10px;
        pointer-events: none;    /* タップ操作を邪魔しない */
        display: none;           /* クリア時に表示 */
        }
        .game-clear-banner .small{
        font-size: 14px;
        font-weight: 700;
        opacity: .9;
        }
        /* クリア用ボード（固定表示・明るい文字） */
        .game-clear-board{
        position: absolute;
        left: 50%;
        top: 64px;                 /* ライフ/スコアの下あたりに固定 */
        transform: translateX(-50%);
        width: min(92%, 360px);
        padding: 16px 18px 18px;
        border-radius: 14px;
        background: rgba(18,18,22,0.92);             /* しっかり暗い下地で背景に負けない */
        color: #fff;                                  /* 明るい文字色 */
        border: 1px solid rgba(255,255,255,0.18);
        box-shadow: 0 10px 30px rgba(0,0,0,.55), inset 0 0 32px rgba(255,255,255,.06);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        text-align: center;
        z-index: 50;                                  /* キャンバスやボタンより前面 */
        display: none;                                /* クリア時に表示 */
        pointer-events: auto;
        }
        .game-clear-board h2{
        margin: 0 0 8px;
        font-size: 20px;
        font-weight: 900;
        letter-spacing: .4px;
        color: #fff;
        text-shadow: 0 0 6px #fff, 0 0 16px #ffe27a;  /* ほんのり発光 */
        }
        .game-clear-board p{
        margin: 0 0 12px;
        font-size: 16px;
        color: #fffbcc;
        }
        .game-clear-board .restart-btn{
        width: 100%;
        min-height: 44px;
        font-size: 16px;
        border-radius: 10px;
        }
        .life-display{
        font-size: 22px;        /* 絵文字サイズ */
        letter-spacing: 2px;    /* 絵文字の間隔 */
        z-index: 12;
        text-shadow: 0 1px 2px rgba(0,0,0,.35);
        }
        /* 左側：1〜4（小さめ・2x2） */
        .touch-controls-left{
        position:absolute;
        left:12px;
        bottom:calc(env(safe-area-inset-bottom, 0px) + 12px);
        display:grid;
        grid-template-columns: repeat(2, 44px);
        grid-auto-rows:44px;
        gap:10px;
        z-index:30;
        touch-action:none;
        user-select:none;
        }
        .touch-controls-left .answer-btn{
        width:44px; height:44px;
        border-radius:50%;
        background:rgba(255,255,255,0.18);
        border:2px solid #fff;
        color:#fff; font-weight:700; font-size:18px;
        display:flex; align-items:center; justify-content:center;
        backdrop-filter: blur(2px);
        }
        .touch-controls-left .answer-btn:active{ transform:scale(0.95); }

        /* 右側：D-Pad（3x3の配置） */
        .touch-controls-right.dpad{
        position:absolute;
        right:12px;
        bottom:calc(env(safe-area-inset-bottom, 0px) + 12px);
        display:grid;
        grid-template-areas:
            ".    up    ."
            "left  .  right"
            ".   down   .";
        grid-template-columns: 48px 48px 48px;
        grid-template-rows:    48px 48px 48px;
        gap:10px;
        z-index:30;
        touch-action:none;
        user-select:none;
        }
        .arrow-btn{
        width:48px; height:48px;
        border-radius:50%;
        background:rgba(255,255,255,0.18);
        border:2px solid #fff;
        color:#fff; font-size:20px; font-weight:700;
        display:flex; align-items:center; justify-content:center;
        backdrop-filter: blur(2px);
        }
        .arrow-btn[data-active="1"]{ background:rgba(255,255,255,0.35); }

        .btn-up   { grid-area: up; }
        .btn-down { grid-area: down; }
        .btn-left { grid-area: left; }
        .btn-right{ grid-area: right; }

        /* PC等では消したい場合（任意） */
        @media (hover:hover) and (min-width: 768px){
        .touch-controls-left, .touch-controls-right{ display:none; }
        }
        /* ▼ まとめて下部に配置するコンテナ */
        .hud-bottom{
        position:absolute;
        left:50%;
        bottom:calc(env(safe-area-inset-bottom,0px) + 10px);
        transform:translateX(-50%);
        width:min(92%, 380px);
        z-index:30;
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:8px;            /* 説明とボタンの間隔 */
        }

        /* 旧 .instructions の absolute を打ち消し */
        .hud-bottom .instructions{
        position:static !important;
        left:auto !important;
        bottom:auto !important;
        transform:none !important;
        width:100%;
        margin:0;
        font-size:12px;
        color:#aaa;
        text-align:center;
        }

        /* 説明の下に左右で並べる行 */
        .controls-row{
        width:100%;
        display:flex;
        align-items:center;
        justify-content:space-between;
        }

        /* 1,2,3,4 は1行 */
        .answers-inline{
        display:flex;
        gap:8px;           /* ボタン間隔（必要なら調整） */
        }
        .answers-inline .answer-btn{
        width:44px; height:44px;
        border-radius:50%;
        background:rgba(255,255,255,0.18);
        border:2px solid #fff;
        color:#fff; font-weight:700; font-size:18px;
        display:flex; align-items:center; justify-content:center;
        }
        .answers-inline .answer-btn:active{
        transform:scale(0.95);
        background:rgba(255,255,255,0.35);
        }

        /* 矢印（D-Pad）— 間隔を小さく */
        .dpad{
        display:grid;
        grid-template-areas:
            ". up ."
            "left . right"
            ". down .";
        grid-template-columns: 40px 40px 40px;
        grid-template-rows:    40px 40px 40px;
        gap:6px;               /* ← 間隔小さめ */
        }
        .arrow-btn{
        width:40px; height:40px;
        border-radius:50%;
        background:rgba(255,255,255,0.18);
        border:2px solid #fff;
        color:#fff; font-size:18px; font-weight:700;
        display:flex; align-items:center; justify-content:center;
        }
        .arrow-btn[data-active="1"]{ background:rgba(255,255,255,0.35); }
        .btn-up   { grid-area: up; }
        .btn-down { grid-area: down; }
        .btn-left { grid-area: left; }
        .btn-right{ grid-area: right; }
        .hud-bottom{ z-index:30; }
        .answers-inline .answer-btn{ touch-action: manipulation; }

      /* === 最終強制上書き：1-4を2×2、矢印も拡大 === */

/* === 最終上書き：1-4 を 2×2、DPad を右に横並び === */

/* サイズはここで調整（端末幅で自動縮小も下に用意） */
:root{
  --answer-size: 80px;  /* 1〜4ボタン */
  --arrow-size:  72px;  /* DPadボタン */
}

/* HUDコンテナを少し広め＆中央寄せ */
.hud-bottom{
  width: min(96%, 520px) !important;
  margin: 0 auto !important;
}

/* 左(1-4)・右(DPad)を横2列のグリッドにする */
.controls-row{
  display: grid !important;
  grid-template-columns: auto auto !important; /* 左右に2カラム */
  align-items: start !important;
  justify-content: center !important;
  column-gap: 16px !important;
  row-gap: 8px !important; /* もし狭すぎて縦並びになった場合の余白 */
  flex-wrap: unset !important; /* 以前の wrap 指定を無効化 */
}

/* 1-4 を 2×2（1,2 / 3,4）に固定 */
.answers-inline{
  display: grid !important;
  grid-template-columns: repeat(2, 1fr) !important;
  grid-auto-rows: 1fr !important;
  gap: 12px !important;
}
.answers-inline .answer-btn{
  width:  var(--answer-size) !important;
  height: var(--answer-size) !important;
  font-size: calc(var(--answer-size) * 0.35) !important;
  border-width: 3px !important;
}

/* DPad（3×3配置のまま、サイズだけ統一） */
.dpad{
  display: grid !important;
  grid-template-areas:
    ". up ."
    "left . right"
    ". down ." !important;
  grid-template-columns: var(--arrow-size) var(--arrow-size) var(--arrow-size) !important;
  grid-template-rows:    var(--arrow-size) var(--arrow-size) var(--arrow-size) !important;
  gap: 12px !important;
}
.dpad .arrow-btn{
  width:  var(--arrow-size) !important;
  height: var(--arrow-size) !important;
  font-size: calc(var(--arrow-size) * 0.43) !important;
  border-width: 3px !important;
}

/* 画面が狭い端末では自動で少し縮めて「横並び」を維持 */
@media (max-width: 480px){
  :root{ --answer-size: 64px; --arrow-size: 56px; }
}
@media (max-width: 380px){
  :root{ --answer-size: 56px; --arrow-size: 48px; }
}

/* （任意）超狭い端末だけは縦積みにフォールバック
@media (max-width: 320px){
  .controls-row{ grid-template-columns: 1fr !important; }
}
*/
/* === 最終上書き（はみ出し防止版）：左1-4を2×2、右DPadを横並び === */

/* 400px 論理幅に収まるよう固定サイズに調整 */
.hud-bottom{
  width: min(92%, 360px) !important;  /* ← 最大360pxに抑える（400px以内） */
  margin: 0 auto !important;
}

/* 左右を2カラムで並べる（合計幅が360px以内になるサイズを採用） */
.controls-row{
  display: grid !important;
  grid-template-columns: auto auto !important; /* 内容幅に合わせる */
  align-items: start !important;
  justify-content: center !important;
  column-gap: 12px !important;
  row-gap: 8px !important;
  flex-wrap: unset !important; /* 旧wrapの影響を無効化 */
}

/* 1-4（2×2） */
.answers-inline{
  display: grid !important;
  grid-template-columns: repeat(2, 1fr) !important;
  grid-auto-rows: 1fr !important;
  gap: 10px !important;
  width: 130px !important; /* 60 + 60 + 10 = 130 */
}
.answers-inline .answer-btn{
  box-sizing: border-box !important; /* 枠線ぶんでも幅が増えないように */
  width: 60px !important;
  height: 60px !important;
  font-size: 22px !important;
  border-width: 3px !important;
}

/* DPad（3×3） */
.dpad{
  display: grid !important;
  grid-template-areas:
    ". up ."
    "left . right"
    ". down ." !important;
  grid-template-columns: 64px 64px 64px !important;
  grid-template-rows:    64px 64px 64px !important;
  gap: 10px !important;
  width: 212px !important; /* 64*3 + 10*2 = 212 */
}
.dpad .arrow-btn{
  box-sizing: border-box !important;
  width: 64px !important;
  height: 64px !important;
  font-size: 26px !important;
  border-width: 3px !important;
}

/* さらに狭い端末（超小型）用の安全マージン */
@media (max-width: 360px){
  .answers-inline{ width: 118px !important; } /* 54+54+10 */
  .answers-inline .answer-btn{ width:54px !important; height:54px !important; font-size:20px !important; }
  .dpad{ grid-template-columns:56px 56px 56px !important; grid-template-rows:56px 56px 56px !important; width: 188px !important; }
  .dpad .arrow-btn{ width:56px !important; height:56px !important; font-size:22px !important; }
  .hud-bottom{ width: min(96%, 320px) !important; }
}

/* 画面の最下部にHUDを固定（ゲームのスケール/中央寄せに影響されない） */
.hud-bottom{
  position: fixed !important;
  left: 50% !important;
  transform: translateX(-50%) !important;
  bottom: calc(env(safe-area-inset-bottom, 0px) + 0px) !important; /* ぴったり下 */
  margin: 0 !important;
  width: min(92vw, 360px) !important;  /* 400の論理幅に収まるサイズ */
  z-index: 999 !important;
}

/* はみ出し防止（前回案のサイズを明示で再指定） */
.controls-row{
  display: grid !important;
  grid-template-columns: 130px 212px !important; /* 左(1-4) + 右(DPad) */
  column-gap: 12px !important;
  row-gap: 8px !important;
  align-items: start !important;
  justify-content: center !important;
}
.answers-inline{ width:130px !important; display:grid !important; grid-template-columns:repeat(2,1fr) !important; gap:10px !important; }
.answers-inline .answer-btn{ box-sizing:border-box !important; width:60px !important; height:60px !important; font-size:22px !important; border-width:3px !important; }

.dpad{
  display:grid !important;
  grid-template-areas: ". up ." "left . right" ". down ." !important;
  grid-template-columns:64px 64px 64px !important;
  grid-template-rows:64px 64px 64px !important;
  gap:10px !important;
  width:212px !important;
}
.dpad .arrow-btn{ box-sizing:border-box !important; width:64px !important; height:64px !important; font-size:26px !important; border-width:3px !important; }

/* 超小型端末用の縮小 */
@media (max-width: 360px){
  .answers-inline{ width:118px !important; }
  .answers-inline .answer-btn{ width:54px !important; height:54px !important; font-size:20px !important; }
  .dpad{ grid-template-columns:56px 56px 56px !important; grid-template-rows:56px 56px 56px !important; width:188px !important; }
  .dpad .arrow-btn{ width:56px !important; height:56px !important; font-size:22px !important; }
  .hud-bottom{ width:min(96vw, 320px) !important; }
}

/* === 下部HUDを"画面下・フル幅"固定にして左右目一杯へ === */
.hud-bottom{
  position: absolute;
  left: 0;
  right: 0;
  bottom: max(6px, calc(env(safe-area-inset-bottom,0px) + 6px));
  transform: none;                 /* 中央寄せ解除 */
  width: 100%;                     /* フル幅 */
  padding: 0 8px;                  /* 端のゆとり（必要なら調整） */
  box-sizing: border-box;
  z-index: 30;
  display: flex;
  flex-direction: column;
  align-items: stretch;            /* 行を左右いっぱいに */
  gap: 8px;
}

/* 行は左右に張り付くレイアウト */
.controls-row{
  width: 100%;
  display: grid;
  grid-template-columns: 1fr auto; /* 左 = 1-4ボタン / 右 = D-Pad */
  align-items: center;
}

/* 右のD-Padを右端へ */
.dpad{ justify-self: end; }

/* iPhone 12 mini など横幅が小さい端末向けのサイズ微調整 */
@media (max-width: 430px), (hover: none) and (pointer: coarse){
  /* 1〜4ボタン（2x2） */
  .answers-inline{
    display: grid;
    grid-template-columns: repeat(2, 68px);
    grid-auto-rows: 68px;
    gap: 10px;
  }
  .answers-inline .answer-btn{
    width: 68px; height: 68px; font-size: 26px; border-width: 3px;
  }

  /* D-Pad（3x3） */
  .dpad{
    grid-template-columns: 64px 64px 64px;
    grid-template-rows:    64px 64px 64px;
    gap: 10px;
  }
  .dpad .arrow-btn{
    width: 64px; height: 64px; font-size: 24px; border-width: 3px;
  }

  /* HUDは常にフル幅 */
  .hud-bottom{ width: 100% !important; padding: 0 8px !important; }
}



    </style>
</head>
<body>
  <div class="game-container">
    <!-- キャンバスは一番下に敷く -->
    <canvas id="gameCanvas" width="400" height="800"></canvas>

    <!-- スコア/ライフ -->
    <div class="game-ui">
      <div class="life-display" id="lifeDisplay" aria-label="ライフ"></div>
      <div class="score-display">⭐ Score: <span id="scoreCount">0</span></div>
    </div>

    <!-- 下部HUD：説明 + 1〜4 + D-Pad -->
    <div class="hud-bottom">
      <div class="instructions">数字キー1-4で正しい答えを選んで攻撃！</div>
      <div class="controls-row">
        <!-- 左：1,2,3,4（1行） -->
        <div class="answers-inline" id="answerControls" aria-label="攻撃ボタン">
          <button class="answer-btn" data-answer="1" type="button">1</button>
          <button class="answer-btn" data-answer="2" type="button">2</button>
          <button class="answer-btn" data-answer="3" type="button">3</button>
          <button class="answer-btn" data-answer="4" type="button">4</button>
        </div>
        <!-- 右：矢印（間隔小さめ） -->
        <div class="dpad" id="moveControls" aria-label="方向パッド">
          <button class="arrow-btn btn-up"    data-key="ArrowUp">↑</button>
          <button class="arrow-btn btn-left"  data-key="ArrowLeft">←</button>
          <button class="arrow-btn btn-right" data-key="ArrowRight">→</button>
          <button class="arrow-btn btn-down"  data-key="ArrowDown">↓</button>
        </div>
      </div>
    </div>

    <!-- ゲームオーバー -->
    <div class="game-over" id="gameOver">
      <h2>ゲームオーバー</h2>
      <p>最終スコア: <span id="finalScore">0</span></p>
      <button class="restart-btn" onclick="restartGame()">リスタート</button>
    </div>

    <!-- クリア固定ボード -->
    <div id="gameClearBoard" class="game-clear-board" aria-live="polite" aria-hidden="true">
      <h2>🎉 おめでとう！！ゲームクリアです！！</h2>
      <p>最終スコア: <span id="finalScoreClear">0</span></p>
      <button class="restart-btn" onclick="restartGame()">リスタート</button>
    </div>
  </div>

    <script>
      
      const bgImg = new Image();
/* 画像をcanvasに描くので、将来toDataURL等を使う可能性に備えて */
bgImg.crossOrigin = 'anonymous'; 
bgImg.src = 'images/ookamichan-bg.png';          /* ← CSSと同じに */
bgImg.onload = () => bgReady = true;

    // === Boss Attack Config ===
    const RAINBOW_INVERT_MS = 30000;   // 被弾で反転する時間(ms)
    const BOSS_SHADOW_RATE  = 3;      // シャドー：1秒あたり3発
    const BOSS_SHADOW_TIME  = 5000;   // シャドー持続 5秒
    const BOSS_WAVE_TIME    = 5000;   // 波状(毎秒10発リング) 5秒
    const BOSS_REST5        = 5000;   // 休憩 5秒
    // （BOSS_REST3 は使わないので削除してOK）

    function drawBackground(){
      if (bgReady) ctx.drawImage(bgImg, 0, 0, canvas.width, canvas.height);
    }

    
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const PLAYER_W = 50, PLAYER_H = 40;
        

        // 操作反転ユーティリティ
        function isControlsInverted(){
          return gameState.controlsInverted && performance.now() < gameState.invertUntil;
        }
        function mapAnswerNumber(n){
          if (!isControlsInverted()) return n;
          // 1↔4, 2↔3 に反転
          return ({1:4, 2:3, 3:2, 4:1}[n] ?? n);
        }

        // ===== グローバル速度スケール =====
        // 小さくするほど遅くなる（例: 0.6 = 60% の速さ）
       // ===== グローバル調整値 =====
        const SPEED_MULT = 0.6;   // 0.6 = 60%速度（移動・弾速）
        const FIRE_RATE  = 0.6;   // 0.6 = 発射レート 60%（= 間隔は1/0.6倍）
        const SPAWN_RATE = 0.7;   // 0.7 = 出現率 70%

        // ===== デルタタイム（60fps基準） =====
        let lastTime = performance.now();
        let dt = 1; // 60fpsで1.0、120fpsで約0.5になるスケール


        // ゲーム状態
        let gameState = {
            life: 10,
            score: 0,
            gameRunning: true,
            enemies: [],
            missiles: [],
            enemyBeams: [],
            explosions: [],
            messages: [],
            stars: [],
            animationTime: 0,
            player: {
              x: (canvas.width  - PLAYER_W) / 2,
              y: (canvas.height - PLAYER_H) / 2,
              width: PLAYER_W,
              height: PLAYER_H
            },
            keys: {},
            boss: null,
            bossBeams: [],
            bossWarningActive: false,
            bossWarningStart: 0,
            bossPending: false,          // 警告後にボスを出す予約フラグ
            bossTriggerScore: 100,        // 出現スコア（必要なら変更可）
            bossCleared: false,
            bossCleared: false,
            bossFinaleActive: false,
            bossFinaleStart: 0,
            bossFinalePos: {x:0,y:0},
            controlsInverted: false,
            invertUntil: 0,


            };
        
        
        
        // 英単語データ（30個）
        const vocabularyData = [
        { word:"mom",    options:["おかあさん","おとうさん","あかちゃん","おとこのこ"], correct:1 },
        { word:"dad",    options:["おかあさん","おとうさん","おんなのこ","あかちゃん"], correct:2 },
        { word:"baby",   options:["おとこのこ","おんなのこ","あかちゃん","おかあさん"], correct:3 },
        { word:"boy",    options:["おんなのこ","あかちゃん","おとうさん","おとこのこ"], correct:4 },
        { word:"girl",   options:["おんなのこ","おとこのこ","おかあさん","ねこ"],       correct:1 },
        { word:"red",    options:["あお","あか","しろ","くろ"],                           correct:2 },
        { word:"blue",   options:["あか","きいろ","あお","くろ"],                         correct:3 },
        { word:"green",  options:["あか","あお","きいろ","みどり"],                       correct:4 },
        { word:"yellow", options:["きいろ","あお","しろ","くろ"],                         correct:1 },
        { word:"black",  options:["しろ","くろ","あか","きいろ"],                         correct:2 },
        { word:"white",  options:["あか","くろ","しろ","あお"],                           correct:3 },
        { word:"big",    options:["ちいさい","あつい","つめたい","おおきい"],               correct:4 },
        { word:"small",  options:["ちいさい","おおきい","あつい","つめたい"],             correct:1 },
        { word:"hot",    options:["つめたい","あつい","ちいさい","おおきい"],             correct:2 },
        { word:"cold",   options:["あつい","おおきい","つめたい","きいろ"],               correct:3 },
        { word:"run",    options:["あるく","たべる","のむ","はしる"],                     correct:4 },
        { word:"jump",   options:["とぶ","あるく","はしる","ねる"],                       correct:1 },
        { word:"walk",   options:["はしる","あるく","たべる","のむ"],                     correct:2 },
        { word:"eat",    options:["のむ","はしる","たべる","あるく"],                     correct:3 },
        { word:"drink",  options:["たべる","はしる","あるく","のむ"],                     correct:4 },
        { word: "love",       options: ["あい", "いぬ", "たまご", "ねこ"], correct: 1 },
        { word: "egg",        options: ["りんご", "たまご", "みず", "そら"], correct: 2 },
        { word: "dog",        options: ["とり", "ねこ", "ぞう", "いぬ"],     correct: 4 },
        { word: "cat",        options: ["いぬ", "ねこ", "うま", "さかな"],   correct: 2 },
        { word: "elephant",   options: ["ねこ", "いぬ", "ぞう", "とり"],     correct: 3 },
        { word: "apple",      options: ["ばなな", "いちご", "りんご", "みかん"], correct: 3 },
        { word: "water",      options: ["みず", "き", "そら", "やま"],       correct: 1 },
        { word: "sky",        options: ["かわ", "そら", "つち", "き"],       correct: 2 },
        { word: "moon",       options: ["たいよう", "やま", "つき", "かわ"], correct: 3 },
        { word: "sun",        options: ["つき", "たいよう", "ほし", "ゆき"], correct: 2 },
        { word: "mountain",   options: ["やま", "うみ", "かわ", "もり"],     correct: 1 },
        { word: "river",      options: ["うみ", "そら", "くも", "かわ"],     correct: 4 },
        { word: "car",        options: ["くるま", "じてんしゃ", "でんしゃ", "ふね"], correct: 1 },
        { word: "train",      options: ["くるま", "ひこうき", "ふね", "でんしゃ"],   correct: 4 },
        { word: "strawberry", options: ["いちご", "みかん", "ぶどう", "りんご"],     correct: 1 },
        { word: "flower",     options: ["かばん", "くつ", "ぼうし", "はな"],         correct: 4 },
        { word: "hand",       options: ["あし", "て", "め", "みみ"],                 correct: 2 },
        { word: "foot",       options: ["て", "みみ", "め", "あし"],                 correct: 4 },
        { word: "ear",        options: ["め", "くち", "みみ", "は"],                 correct: 3 },
        { word: "mouth",      options: ["は", "くち", "かお", "て"],                 correct: 2 },
        { word: "face",       options: ["かお", "くつ", "ぼうし", "かさ"],           correct: 1 },
        { word: "book",       options: ["うた", "ほん", "え", "おやつ"],             correct: 2 },
        { word: "pencil",     options: ["えんぴつ", "いす", "え", "ほん"],             correct: 1 },
        { word: "picture",    options: ["うた", "あそび", "え", "おにぎり"],         correct: 3 },
        { word: "song",       options: ["うた", "え", "ほん", "やさい"],             correct: 1 },
        { word: "rain",       options: ["くも", "ゆき", "かぜ", "あめ"],             correct: 4 },
        { word: "snow",       options: ["あめ", "ゆき", "かぜ", "くも"],             correct: 2 },
        { word: "wind",       options: ["ゆき", "くも", "あめ", "かぜ"],             correct: 4 },
        { word: "red",        options: ["あお", "きいろ", "あか", "しろ"],           correct: 3 },
        { word: "blue",       options: ["あお", "しろ", "くろ", "あか"],             correct: 1 }
        ];

        const emojiMap = {
        "あい":"💖", "あめ":"☔️", "ゆき":"❄️", "くも":"☁️", "かぜ":"🌬️","みず":"💧","うま":"🐴","つち":"🟫","き":"🌲",
        "たいよう":"☀️", "つき":"🌕", "ほし":"⭐️", "そら":"🌤️",
        "りんご":"🍎", "ばなな":"🍌", "いちご":"🍓", "ぶどう":"🍇", "みかん":"🍊",
        "たまご":"🥚", "いぬ":"🐶", "ねこ":"🐱", "ぞう":"🐘", "とり":"🐦", "さかな":"🐟",
        "やま":"⛰️", "うみ":"🌊", "かわ":"🏞️",
        "くるま":"🚗", "でんしゃ":"🚆", "ふね":"⛴️", "ひこうき":"✈️",
        "はな":"🌸", "ほん":"📖", "えんぴつ":"✏️", "え":"🖼️", "うた":"🎵",
        "あか":"🔴", "あお":"🔵", "きいろ":"🟡", "しろ":"⚪️", "くろ":"⚫️",
        "て":"✋", "あし":"🦶", "みみ":"👂", "め":"👀", "くち":"👄", "かお":"🙂",
        "おかあさん": "👩",
        "おとうさん": "👨",
        "あかちゃん": "👶",
        "おとこのこ": "👦",
        "おんなのこ": "👧",
        "ねこ":       "🐱",
        "あお":   "🔵",
        "あか":   "🔴",
        "しろ":   "⚪️",
        "くろ":   "⚫️",
        "きいろ": "🟡",
        "みどり": "🟢",
        "おおきい": "🐘",
        "ちいさい": "🐣",
        "あつい":   "🔥",
        "つめたい": "❄️",
        "あるく": "🚶",
        "はしる": "🏃",
        "とぶ":   "🕊️",
        "ねる":   "🛌",
        "たべる": "🍽️",
        "のむ":   "🥤",
        "やさい":"🥦",
        "いす":"🪑",
        "かさ":"☂️",
        "かばん":"👜",
        "くつ":"👞",
        "は":"🦷",
        };
        let currentVocabIndex = 0;

        // ★ ランダム山札（重複防止して一巡する）
        let vocabDeck = [];
        function refillVocabDeck() {
        // 0..N-1 のインデックス山札を作ってフィッシャー–イェーツでシャッフル
        vocabDeck = Array.from({length: vocabularyData.length}, (_, i) => i);
        for (let i = vocabDeck.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [vocabDeck[i], vocabDeck[j]] = [vocabDeck[j], vocabDeck[i]];
        }
        }
        function getRandomVocab() {
        if (vocabDeck.length === 0) refillVocabDeck();
        const idx = vocabDeck.pop();            // 山札の末尾から1枚引く
        return vocabularyData[idx];
        }
        // 初期化
        refillVocabDeck();
        
        // 難単語（ボス用）
       // 難単語（ボス用）— 英検2級レベル（絵文字なし）
const hardVocabularyData = [
  { word:"achieve",     options:["達成する","拒否する","修理する","想像する"],                           correct:1 },
  { word:"afford",      options:["〜する余裕がある","与える","借りる","保存する"],                       correct:1 },
  { word:"agriculture", options:["農業","商業","工業","観光"],                                         correct:1 },
  { word:"ancient",     options:["古代の","最新の","平均の","危険な"],                                 correct:1 },
  { word:"announce",    options:["発表する","隠す","延期する","否定する"],                               correct:1 },
  { word:"appreciate",  options:["感謝する","後悔する","批判する","拒む"],                               correct:1 },
  { word:"arrange",     options:["手配する","壊す","捨てる","疑う"],                                   correct:1 },
  { word:"attend",      options:["出席する","辞退する","忘れる","雇う"],                               correct:1 },
  { word:"attempt",     options:["試みる","成功する","許す","避ける"],                                 correct:1 },
  { word:"avoid",       options:["避ける","求める","祝う","探す"],                                     correct:1 },

  { word:"behavior",    options:["行動","環境","距離","温度"],                                         correct:1 },
  { word:"benefit",     options:["利益","不足","危機","誤り"],                                         correct:1 },
  { word:"campaign",    options:["運動","地図","請求書","予報"],                                       correct:1 },
  { word:"career",      options:["職業経歴","趣味","予算","機械"],                                     correct:1 },
  { word:"climate",     options:["気候","文化","人口","景色"],                                         correct:1 },
  { word:"communicate", options:["意思疎通する","競争する","輸送する","配達する"],                       correct:1 },
  { word:"community",   options:["地域社会","家庭菜園","個人情報","銀行口座"],                           correct:1 },
  { word:"compare",     options:["比較する","修正する","許可する","合格する"],                           correct:1 },
  { word:"competition", options:["競争","作曲","苦情","条件"],                                         correct:1 },
  { word:"complain",    options:["不平を言う","自慢する","賛成する","提案する"],                         correct:1 },

  { word:"confidence",  options:["自信","祝日","配送料","指示"],                                       correct:1 },
  { word:"consider",    options:["よく考える","急ぐ","記録する","借りる"],                               correct:1 },
  { word:"convenient",  options:["便利な","危険な","静かな","高価な"],                                 correct:1 },
  { word:"create",      options:["創造する","交換する","翻訳する","破壊する"],                           correct:1 },
  { word:"culture",     options:["文化","温度","交通","在庫"],                                         correct:1 },
  { word:"customer",    options:["顧客","競技者","係員","訪問者"],                                     correct:1 },
  { word:"decrease",    options:["減少する","装備する","輸入する","継続する"],                           correct:1 },
  { word:"demand",      options:["需要","命令","証拠","緊張"],                                         correct:1 },
  { word:"develop",     options:["発展させる","妨げる","拒む","掃除する"],                               correct:1 },
  { word:"device",      options:["装置","請求","提案","賛成"],                                         correct:1 },

  { word:"effort",      options:["努力","影","差","毒"],                                               correct:1 },
  { word:"efficient",   options:["効率的な","平凡な","古典的な","不正確な"],                             correct:1 },
  { word:"encourage",   options:["励ます","脅す","拒絶する","削除する"],                                 correct:1 },
  { word:"environment", options:["環境","実験","経験","式典"],                                         correct:1 },
  { word:"equipment",   options:["設備","事件","失敗","景気"],                                         correct:1 },
  { word:"event",       options:["出来事","費用","賞品","在庫"],                                       correct:1 },
  { word:"evidence",    options:["証拠","競技","収入","栄養"],                                         correct:1 },
  { word:"experience",  options:["経験","実験","冒険","遠足"],                                         correct:1 },
  { word:"experiment",  options:["実験","説明","遠足","経験"],                                         correct:1 },
  { word:"factory",     options:["工場","畑","港","寺"],                                               correct:1 },

  { word:"feature",     options:["特徴","欠点","費用","境界"],                                         correct:1 },
  { word:"fuel",        options:["燃料","郵便","繊維","粉末"],                                         correct:1 },
  { word:"generation",  options:["世代","発表","目的","緊急"],                                         correct:1 },
  { word:"global",      options:["世界的な","個人的な","地方の","危険な"],                               correct:1 },
  { word:"government",  options:["政府","会社","家族","研究機関"],                                     correct:1 },
  { word:"habit",       options:["習慣","祝日","住居","料理法"],                                       correct:1 },
  { word:"influence",   options:["影響","情報","燃料","雰囲気"],                                       correct:1 },
  { word:"ingredient",  options:["材料","患者","政策","装置"],                                         correct:1 },
  { word:"introduce",   options:["紹介する","削減する","避難する","誤解する"],                           correct:1 },
  { word:"issue",       options:["問題","領収書","会費","衣装"],                                       correct:1 },

  { word:"local",       options:["地元の","合法の","論理的な","孤独な"],                               correct:1 },
  { word:"maintain",    options:["維持する","提案する","説明する","放棄する"],                           correct:1 },
  { word:"material",    options:["材料","評価","契約","信号"],                                         correct:1 },
  { word:"medical",     options:["医療の","音楽の","自然の","商業の"],                                 correct:1 },
  { word:"memory",      options:["記憶","給料","会員","注意"],                                         correct:1 },
  { word:"offer",       options:["申し出る","拒む","導く","混ぜる"],                                   correct:1 },
  { word:"organize",    options:["組織する","占領する","推測する","分解する"],                           correct:1 },
  { word:"patient",     options:["患者","忍耐","支払い","文句"],                                       correct:1 },
  { word:"policy",      options:["方針","警察","詩","政治家"],                                         correct:1 },
  { word:"population",  options:["人口","世論","物価","位置"],                                         correct:1 },

  { word:"predict",     options:["予測する","保護する","発行する","採用する"],                           correct:1 },
  { word:"prevent",     options:["防ぐ","提供する","提出する","進歩する"],                               correct:1 },
  { word:"produce",     options:["生産する","紹介する","翻訳する","交換する"],                           correct:1 },
  { word:"profit",      options:["利益","習慣","資源","任務"],                                         correct:1 },
  { word:"protect",     options:["守る","選ぶ","集める","修理する"],                                   correct:1 },
  { word:"quality",     options:["質","量","料金","資格"],                                             correct:1 },
  { word:"recycle",     options:["再利用する","記録する","回復する","再演する"],                         correct:1 },
  { word:"reduce",      options:["減らす","導く","生む","述べる"],                                     correct:1 },
  { word:"replace",     options:["取り替える","思い出す","乗り換える","立証する"],                       correct:1 },
  { word:"require",     options:["要求する","断る","修了する","出発する"],                               correct:1 },

  { word:"resource",    options:["資源","研究","回答","契約"],                                         correct:1 },
  { word:"respond",     options:["応答する","尊敬する","再利用する","復旧する"],                         correct:1 },
  { word:"solution",    options:["解決策","解説","合図","装飾"],                                       correct:1 },
  { word:"technology",  options:["技術","伝統","理論","地理"],                                         correct:1 },
  { word:"tradition",   options:["伝統","翻訳","交通","取引"],                                         correct:1 },
  { word:"traffic",     options:["交通","悲劇","利益","予算"],                                         correct:1 },
  { word:"volunteer",   options:["ボランティア","見物人","店員","選挙人"],                               correct:1 },
  { word:"waste",       options:["無駄・廃棄物","味覚","湿地","念願"],                                 correct:1 }
];

        let bossVocabIndex = 0;


        // 星空初期化
        function initStars() {
            gameState.stars = [];
            for (let i = 0; i < 100; i++) {
                gameState.stars.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    size: Math.random() * 2 + 1
                });
            }
        }
        // ★ ボス用ランダム山札（使い切るまで重複なし）
       // ★ ボス用ランダム山札（正解位置1〜4を均等に割り当て）
let bossDeck = [];

function toTargetCorrect(card, targetIndex /* 1..4 */){
  // 正解テキストを取り出し、残りをダミーとしてシャッフル
  const correctText = card.options[card.correct - 1];
  const distractors = card.options.filter((_, i) => i !== card.correct - 1);
  // フィッシャー–イェーツでダミーをシャッフル
  for (let i = distractors.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [distractors[i], distractors[j]] = [distractors[j], distractors[i]];
  }
  // 新しい並び：targetIndex に正解、それ以外にダミーを詰める
  const opts = new Array(4);
  opts[targetIndex - 1] = correctText;
  let di = 0;
  for (let k = 0; k < 4; k++) {
    if (k === targetIndex - 1) continue;
    opts[k] = distractors[di++];
  }
  return { word: card.word, options: opts, correct: targetIndex };
}

function refillBossDeck(){
  // 元データの順はシャッフル
  const idxs = Array.from({ length: hardVocabularyData.length }, (_, i) => i);
  for (let i = idxs.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [idxs[i], idxs[j]] = [idxs[j], idxs[i]];
  }
  // 正解位置のスケジュール（1,2,3,4 を繰り返し）
  const schedule = Array.from({ length: idxs.length }, (_, i) => (i % 4) + 1);

  // 均等スケジュールに合わせて各カードを“再配置”してデッキ化
  bossDeck = idxs.map((idx, i) => {
    const card = hardVocabularyData[idx];
    return toTargetCorrect(card, schedule[i]);
  });
}

function getRandomBossVocab(){
  if (bossDeck.length === 0) refillBossDeck();
  return bossDeck.pop(); // すでにoptions並べ替え済みのオブジェクトを返す
}
        
// === 単語カードのサイズ（Enemy.drawの cardW/cardH と合わせる）===
const CARD_W = 120;
const CARD_H = 84;
const CARD_OFFSET = 16; // 敵の頭上からカードまでのオフセット

function enemyCardRect(e) {
  const cx = e.x + e.width / 2;
  return {
    x: cx - CARD_W / 2,
    y: e.y - (CARD_H + CARD_OFFSET),
    w: CARD_W,
    h: CARD_H
  };
}

function rectsOverlap(a, b) {
  return (
    a.x < b.x + b.w &&
    a.x + a.w > b.x &&
    a.y < b.y + b.h &&
    a.y + a.h > b.y
  );
}

// === Devil Wing Helper: 悪魔の羽（背面） ==============================
function drawDevilWing(ctx, baseX, baseY, side = 1, t = 0, size = 1){
  // side: -1=左, +1=右。呼び出し元ですでに translate/scale 済みのローカル座標を想定
  ctx.save();
  ctx.translate(baseX, baseY);
  ctx.scale(size, size);

  const flap       = Math.sin(t * 3.2);            // 羽ばたき
  const wingAngle  = flap * 0.95;                   // 角度振れ
  const wingStretch= 1 + Math.abs(flap) * 0.45;     // 伸縮
  const spines     = [];
  const spineCount = 4;

  // 骨（スパイン）
  for (let i = 0; i < spineCount; i++) {
    const a0  = -0.15 - i * 0.35;
    const wob = Math.sin(t * 2.4 + i) * 0.12;
    const ang = a0 + wob + wingAngle;
    const len = (56 + i * 16) * wingStretch;

    const ex = side * Math.cos(ang) * len;
    const ey = Math.sin(ang) * len;

    const grd = ctx.createLinearGradient(0, 0, ex, ey);
    grd.addColorStop(0, '#240000');
    grd.addColorStop(1, '#5a0a0a');
    ctx.strokeStyle = grd;
    ctx.lineCap = 'round';
    ctx.lineWidth = 6 - i * 1.2;
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.lineTo(ex, ey);
    ctx.stroke();

    spines.push({ x: ex, y: ey });
  }

  // 膜（スパイン間を結ぶ）
  ctx.save();
  ctx.globalAlpha = 0.72;
  for (let i = 0; i < spines.length - 1; i++) {
    const p0 = { x: 0, y: 0 };
    const p1 = spines[i];
    const p2 = spines[i + 1];

    const mg = ctx.createLinearGradient(p0.x, p0.y, p1.x, p1.y);
    mg.addColorStop(0, 'rgba(120, 0, 0, 0.9)');
    mg.addColorStop(1, 'rgba(20, 0, 0, 0.95)');
    ctx.fillStyle   = mg;
    ctx.strokeStyle = 'rgba(255, 30, 30, 0.15)';
    ctx.lineWidth   = 1.2;

    ctx.beginPath();
    ctx.moveTo(p0.x, p0.y);
    ctx.lineTo(p1.x, p1.y);
    ctx.lineTo(p2.x, p2.y);
    ctx.closePath();
    ctx.fill();
    ctx.stroke();
  }
  ctx.restore();

  // 膜の外縁トゲ
  ctx.strokeStyle = 'rgba(180, 0, 0, 0.45)';
  ctx.lineWidth   = 1.5;
  for (let i = 0; i < spines.length - 1; i++) {
    const a = spines[i], b = spines[i + 1];
    const mx = (a.x + b.x) / 2;
    const my = (a.y + b.y) / 2;
    const vx = (b.y - a.y);
    const vy = -(b.x - a.x);
    const vlen = Math.hypot(vx, vy) || 1;
    const nx = (vx / vlen) * 10 * wingStretch * (side === -1 ? 1 : -1);
    const ny = (vy / vlen) * 10 * wingStretch;
    ctx.beginPath();
    ctx.moveTo(mx, my);
    ctx.lineTo(mx + nx, my + ny);
    ctx.stroke();
  }

  ctx.restore();
}


function drawAngelWing(ctx, baseX, baseY, side = 1, t = 0, size = 1){
  ctx.save();
  ctx.translate(baseX, baseY);
  ctx.scale(size, size);

  // ゆるめの羽ばたき（本体は揺れない）
  const flap = Math.sin(t * 6.0);      // 速すぎず重厚
  const ang  = flap * 0.45;            // 角度の振れ
  ctx.rotate(side * (Math.PI/10 + ang));

  // 翼のメイン形（黒い羽根：曲線の一枚羽）
  const W = 90, H = 70;
  const grad = ctx.createLinearGradient(0,0, side*W, H);
  grad.addColorStop(0, '#000');
  grad.addColorStop(1, '#1b1b1b');

  ctx.fillStyle = grad;
  ctx.strokeStyle = '#333';
  ctx.lineWidth = 1.4;

  ctx.beginPath();
  ctx.moveTo(0, 0);
  ctx.quadraticCurveTo(side*40, -18, side*70, -8);
  ctx.quadraticCurveTo(side*92, 10,  side*88, 26);
  ctx.quadraticCurveTo(side*70, 46,  side*24, 54);
  ctx.quadraticCurveTo(side*14, 40,  0, 26);
  ctx.closePath();
  ctx.fill();
  ctx.stroke();

  // フェザー（羽毛すじ）を数本
  ctx.strokeStyle = 'rgba(255,255,255,0.06)';
  ctx.lineWidth = 1.1;
  for (let i=0;i<6;i++){
    const u = i/5;
    ctx.beginPath();
    ctx.moveTo(side*(20+u*58), 6+u*34);
    ctx.lineTo(side*(10+u*42), 2+u*24);
    ctx.stroke();
  }

  ctx.restore();
}


// === Canvas: Pink Wolf (enemy) =========================================
// === Canvas: Pink Wolf (enemy / 恐怖版：赤目＋長牙) ======================
function drawPinkWolf(ctx, cx, cy, w=56, h=56, t=0){
  ctx.save();
  ctx.translate(cx, cy);
  // 背面：悪魔の翼（ピンク狼）
drawDevilWing(ctx, -44, 6, -1, t, 0.95);
drawDevilWing(ctx,  44, 6,  1, t, 0.95);

  ctx.scale(w/120, h/120);           // 基準 120x120
  ctx.lineJoin = 'round';
  ctx.lineCap  = 'round';

  const blink  = 0.78 + 0.22*Math.abs(Math.sin(t*2.0)); // まばたき
  const earWig = Math.sin(t*3.0)*3;

  // 影（不気味な赤黒の後光）
  ctx.save();
  ctx.globalCompositeOperation = 'multiply';
  const gShadow = ctx.createRadialGradient(0, 10, 24, 0, 10, 80);
  gShadow.addColorStop(0, 'rgba(80,0,30,0.45)');
  gShadow.addColorStop(1, 'rgba(0,0,0,0)');
  ctx.fillStyle = gShadow;
  ctx.beginPath(); ctx.arc(0, 10, 78, 0, Math.PI*2); ctx.fill();
  ctx.restore();

  // トゲ毛のシルエット
  ctx.save();
  ctx.fillStyle = '#7a0d3a';
  for(let i=0;i<12;i++){
    const a  = i*(Math.PI*2/12) + Math.sin(t*1.3+i)*0.08;
    const r1 = 48, r2 = 60 + Math.sin(t*2+i)*4;
    const ix = Math.cos(a)*r1, iy = 10 + Math.sin(a)*r1;
    const ox = Math.cos(a)*r2, oy = 10 + Math.sin(a)*r2;
    ctx.beginPath();
    ctx.moveTo(ix, iy);
    ctx.lineTo(ox + Math.cos(a+0.18)*6, oy + Math.sin(a+0.18)*6);
    ctx.lineTo(ox + Math.cos(a-0.18)*6, oy + Math.sin(a-0.18)*6);
    ctx.closePath();
    ctx.fill();
  }
  ctx.restore();

  // 顔ベース（濃ピンク→黒のグラデ）
  let g = ctx.createRadialGradient(0,-6,6, 0,12,68);
  g.addColorStop(0,'#ffb1cf');
  g.addColorStop(0.45,'#ff5aa5');
  g.addColorStop(1,'#320016');
  ctx.fillStyle = g;
  ctx.beginPath(); ctx.ellipse(0, 12, 56, 50, 0, 0, Math.PI*2); ctx.fill();

  // 耳（外）
  ctx.fillStyle = '#8f194a';
  ctx.beginPath();
  ctx.moveTo(-42,-2); ctx.quadraticCurveTo(-68,-32+earWig, -30,-42+earWig);
  ctx.quadraticCurveTo(-20,-26+earWig, -28,-10+earWig); ctx.closePath(); ctx.fill();
  ctx.beginPath();
  ctx.moveTo( 42,-2); ctx.quadraticCurveTo( 68,-32-earWig,  30,-42-earWig);
  ctx.quadraticCurveTo( 20,-26-earWig,  28,-10-earWig); ctx.closePath(); ctx.fill();

  // 耳（内）
  ctx.fillStyle = '#ff7fb7';
  ctx.beginPath();
  ctx.moveTo(-34,-8); ctx.quadraticCurveTo(-50,-28+earWig, -26,-32+earWig);
  ctx.quadraticCurveTo(-18,-22+earWig, -22,-12+earWig); ctx.closePath(); ctx.fill();
  ctx.beginPath();
  ctx.moveTo(34,-8); ctx.quadraticCurveTo(50,-28-earWig, 26,-32-earWig);
  ctx.quadraticCurveTo(18,-22-earWig, 22,-12-earWig); ctx.closePath(); ctx.fill();

  // ほほ毛（白）
  ctx.fillStyle = 'rgba(255,255,255,0.95)';
  ctx.beginPath(); ctx.ellipse(-22, 24, 22, 16, 0, 0, Math.PI*2); ctx.fill();
  ctx.beginPath(); ctx.ellipse( 22, 24, 22, 16, 0, 0, Math.PI*2); ctx.fill();

  // 口（口腔を黒く）
  ctx.fillStyle = '#1a0007';
  ctx.beginPath();
  ctx.moveTo(-28, 36);
  ctx.quadraticCurveTo(0, 22, 28, 36);
  ctx.quadraticCurveTo(0, 48, -28, 36);
  ctx.closePath();
  ctx.fill();

  // マズル（白）
  ctx.fillStyle = '#fff';
  ctx.beginPath();
  ctx.moveTo(-30, 28); ctx.quadraticCurveTo(0, 8, 30, 28);
  ctx.quadraticCurveTo(12, 40, 0, 40);
  ctx.quadraticCurveTo(-12, 40, -30, 28); ctx.closePath(); ctx.fill();

  // 牙（長く鋭く）
  const fang = (sx, sy, dir=1) => {
    const L = 26 + Math.sin(t*5 + dir)*2;  // 長さに微振動
    ctx.fillStyle = '#f4f4f4';
    ctx.strokeStyle = '#5a0a0a';
    ctx.lineWidth = 1.6;
    ctx.beginPath();
    ctx.moveTo(sx, sy);
    ctx.lineTo(sx + dir*6, sy + 6);
    ctx.lineTo(sx + dir*2, sy + L);
    ctx.closePath();
    ctx.fill(); ctx.stroke();

    // 血のにじみ（先端）
    const rg = ctx.createRadialGradient(sx+dir*2, sy+L-2, 0, sx+dir*2, sy+L-2, 6);
    rg.addColorStop(0,'rgba(200,0,0,0.9)');
    rg.addColorStop(1,'rgba(200,0,0,0)');
    ctx.fillStyle = rg;
    ctx.beginPath(); ctx.arc(sx+dir*2, sy+L-2, 6, 0, Math.PI*2); ctx.fill();
  };
  fang(-8, 36, -1);
  fang( 8, 36, +1);

  // 鼻
  ctx.fillStyle = '#2b0b1a';
  ctx.beginPath(); ctx.arc(0, 26, 4.6, 0, Math.PI*2); ctx.fill();

  // 目（鋭い赤＋発光）
  const drawEvilEye = (ex, rot) => {
    ctx.save();
    ctx.translate(ex, 6);
    ctx.rotate(rot);
    // 白目
    ctx.fillStyle = '#fff';
    ctx.beginPath(); ctx.ellipse(0, 0, 18, 11*blink, 0, 0, Math.PI*2); ctx.fill();
    // 赤い輝き
    const eg = ctx.createRadialGradient(0,0,0, 0,0,12);
    eg.addColorStop(0,'#ffeded');
    eg.addColorStop(0.4,'#ff6b6b');
    eg.addColorStop(1,'#770000');
    ctx.fillStyle = eg;
    ctx.beginPath(); ctx.ellipse(0, 0, 9, 7.5*blink, 0, 0, Math.PI*2); ctx.fill();
    // 縦長の黒い瞳孔（スリット）
    ctx.fillStyle = '#140000';
    ctx.beginPath(); ctx.ellipse(0, 0, 2.2, 6*blink, 0, 0, Math.PI*2); ctx.fill();
    // 外光
    ctx.shadowColor = 'rgba(255, 0, 0, 0.7)';
    ctx.shadowBlur = 12;
    ctx.globalAlpha = 0.6;
    ctx.beginPath(); ctx.ellipse(0, 0, 18, 11*blink, 0, 0, Math.PI*2); ctx.strokeStyle='rgba(255,0,0,0.3)'; ctx.stroke();
    ctx.restore();
  };
  drawEvilEye(-18, -0.15);
  drawEvilEye( 18,  0.15);

  ctx.restore();
}


// === Canvas: Black Wolf (boss / 恐怖版：赤目グロー＋極長牙) ==============
function drawBlackWolf(ctx, cx, cy, w=56, h=56, t=0){
  ctx.save();
  ctx.translate(cx, cy);
// 背面：悪魔の翼（黒狼）
drawDevilWing(ctx, -46, 8, -1, t, 1.05);
drawDevilWing(ctx,  46, 8,  1, t, 1.05);
  ctx.scale(w/120, h/120);
  ctx.lineJoin = 'round'; ctx.lineCap = 'round';

  const blink  = 0.78 + 0.22*Math.abs(Math.sin(t*2.4));
  const earWig = Math.sin(t*3.2)*3;

  // 黒い頭部（縁を赤くにじませる）
  let g = ctx.createRadialGradient(0,-8,8, 0,12,70);
  g.addColorStop(0,'#383838');
  g.addColorStop(0.5,'#161616');
  g.addColorStop(1,'#000');
  ctx.fillStyle = g;
  ctx.beginPath(); ctx.ellipse(0, 10, 58, 52, 0, 0, Math.PI*2); ctx.fill();

  // 周囲トゲ毛
  ctx.save();
  ctx.fillStyle = '#330008';
  for(let i=0;i<14;i++){
    const a = i*(Math.PI*2/14) + Math.sin(t*1.7+i)*0.1;
    const r1=48, r2=66 + Math.sin(t*2+i)*5;
    const ix = Math.cos(a)*r1, iy = 10 + Math.sin(a)*r1;
    const ox = Math.cos(a)*r2, oy = 10 + Math.sin(a)*r2;
    ctx.beginPath();
    ctx.moveTo(ix,iy);
    ctx.lineTo(ox + Math.cos(a+0.18)*6, oy + Math.sin(a+0.18)*6);
    ctx.lineTo(ox + Math.cos(a-0.18)*6, oy + Math.sin(a-0.18)*6);
    ctx.closePath();
    ctx.fill();
  }
  ctx.restore();

  // 耳
  ctx.fillStyle = '#2a2a2a';
  ctx.beginPath();
  ctx.moveTo(-44,0); ctx.quadraticCurveTo(-68,-32+earWig, -30,-42+earWig);
  ctx.quadraticCurveTo(-20,-24+earWig, -28,-8+earWig); ctx.closePath(); ctx.fill();
  ctx.beginPath();
  ctx.moveTo( 44,0); ctx.quadraticCurveTo( 68,-32-earWig,  30,-42-earWig);
  ctx.quadraticCurveTo( 20,-24-earWig,  28,-8-earWig); ctx.closePath(); ctx.fill();

  // 頬の毛
  ctx.fillStyle = '#c7c7c7';
  ctx.beginPath(); ctx.ellipse(-24, 22, 22, 16, 0, 0, Math.PI*2); ctx.fill();
  ctx.beginPath(); ctx.ellipse( 24, 22, 22, 16, 0, 0, Math.PI*2); ctx.fill();

  // 口腔（黒）
  ctx.fillStyle = '#070003';
  ctx.beginPath();
  ctx.moveTo(-32, 34); ctx.quadraticCurveTo(0, 18, 32, 34);
  ctx.quadraticCurveTo(0, 52, -32, 34);
  ctx.closePath(); ctx.fill();

  // マズル（灰白）
  ctx.fillStyle = '#eaeaea';
  ctx.beginPath();
  ctx.moveTo(-32, 26); ctx.quadraticCurveTo(0, 6, 32, 26);
  ctx.quadraticCurveTo(12, 40, 0, 40);
  ctx.quadraticCurveTo(-12, 40, -32, 26); ctx.closePath();
  ctx.fill();

  // 牙（極長）
  const fang = (sx, sy, dir=1) => {
    const L = 30 + Math.sin(t*4 + dir)*2.5;
    ctx.fillStyle = '#f1f1f1';
    ctx.strokeStyle = '#3a0a0a';
    ctx.lineWidth = 1.8;
    ctx.beginPath();
    ctx.moveTo(sx, sy);
    ctx.lineTo(sx + dir*7, sy + 7);
    ctx.lineTo(sx + dir*2, sy + L);
    ctx.closePath();
    ctx.fill(); ctx.stroke();

    // 赤い先端グロー
    const rg = ctx.createRadialGradient(sx+dir*2, sy+L-3, 0, sx+dir*2, sy+L-3, 7);
    rg.addColorStop(0,'rgba(255,0,0,0.95)');
    rg.addColorStop(1,'rgba(255,0,0,0)');
    ctx.fillStyle = rg;
    ctx.beginPath(); ctx.arc(sx+dir*2, sy+L-3, 7, 0, Math.PI*2); ctx.fill();
  };
  fang(-10, 34, -1);
  fang( 10, 34, +1);

  // 鼻
  ctx.fillStyle = '#151515';
  ctx.beginPath(); ctx.arc(0, 26, 5, 0, Math.PI*2); ctx.fill();

  // 目（赤いグロー＋縦スリット）
  const drawEvilEye = (ex, rot) => {
    ctx.save();
    ctx.translate(ex, 2);
    ctx.rotate(rot);
    ctx.fillStyle = '#fff';
    ctx.beginPath(); ctx.ellipse(0, 0, 18, 10*blink, 0, 0, Math.PI*2); ctx.fill();

    const eg = ctx.createRadialGradient(0,0,0, 0,0,12);
    eg.addColorStop(0,'#ffecec');
    eg.addColorStop(0.5,'#ff3434');
    eg.addColorStop(1,'#4a0000');
    ctx.fillStyle = eg;
    ctx.beginPath(); ctx.ellipse(0, 0, 9, 7*blink, 0, 0, Math.PI*2); ctx.fill();

    ctx.fillStyle = '#090000';
    ctx.beginPath(); ctx.ellipse(0, 0, 2.4, 6.2*blink, 0, 0, Math.PI*2); ctx.fill();

    // 外光で“ギラッ”
    ctx.shadowColor = 'rgba(255,0,0,0.85)';
    ctx.shadowBlur = 16;
    ctx.globalAlpha = 0.7;
    ctx.beginPath(); ctx.ellipse(0, 0, 18, 10*blink, 0, 0, Math.PI*2); ctx.strokeStyle='rgba(255,0,0,0.35)'; ctx.stroke();
    ctx.restore();
  };
  drawEvilEye(-18, -0.18);
  drawEvilEye( 18,  0.18);

  ctx.restore();
}

// 黒い天使（人型＋単眼がプレイヤーを追視）
function drawBlackAngel(ctx, cx, cy, w=120, h=140, t=0, playerX=null, playerY=null){
  ctx.save();
  ctx.translate(cx, cy);

  // 翼（左右）：黒の天使の羽
  drawAngelWing(ctx, -52, -8, -1, t, 1.15);
  drawAngelWing(ctx,  52, -8,  1, t, 1.15);

  // 本体は揺らさない
  ctx.scale(w/120, h/140);
  ctx.lineJoin = 'round';
  ctx.lineCap  = 'round';

  // 体（ローブ風の胴）
  let g = ctx.createLinearGradient(0,-30, 0, 70);
  g.addColorStop(0,'#111');
  g.addColorStop(1,'#000');
  ctx.fillStyle = g;
  ctx.beginPath();
  ctx.moveTo(-28, -8);
  ctx.quadraticCurveTo(-38, 22, -26, 70);
  ctx.lineTo( 26, 70);
  ctx.quadraticCurveTo( 38, 22,  28, -8);
  ctx.closePath();
  ctx.fill();

  // 腕（左右）
  ctx.fillStyle = '#0d0d0d';
  // 左腕
  ctx.beginPath();
  ctx.moveTo(-28,-6); ctx.lineTo(-46,14); ctx.lineTo(-38,20); ctx.lineTo(-22,2); ctx.closePath(); ctx.fill();
  // 右腕
  ctx.beginPath();
  ctx.moveTo( 28,-6); ctx.lineTo( 46,14); ctx.lineTo( 38,20); ctx.lineTo( 22,2); ctx.closePath(); ctx.fill();

  // 足（裾の下にシルエット）
  ctx.fillStyle = '#0a0a0a';
  ctx.fillRect(-16, 70, 12, 18);
  ctx.fillRect(  4, 70, 12, 18);

  // 頭（円形）
  const headY = -28;
  g = ctx.createRadialGradient(0, headY-6, 6, 0, headY+12, 46);
  g.addColorStop(0,'#1a1a1a');
  g.addColorStop(1,'#000');
  ctx.fillStyle = g;
  ctx.beginPath();
  ctx.ellipse(0, headY, 34, 30, 0, 0, Math.PI*2);
  ctx.fill();

  // 口（小さめの切り込み）
  ctx.strokeStyle = '#222';
  ctx.lineWidth = 2;
  ctx.beginPath();
  ctx.moveTo(-6, headY+16);
  ctx.quadraticCurveTo(0, headY+18, 6, headY+16);
  ctx.stroke();

  // ===== 単眼（巨大な赤い目：主人公を追視） =====
  // プレイヤー方向角
  let ang = 0;
  if (playerX != null && playerY != null){
    // 頭の世界座標（おおよそ）
    const headWorldX = cx;
    const headWorldY = cy + headY*(h/140);
    ang = Math.atan2(playerY - headWorldY, playerX - headWorldX);
  }

  // 白目（土台）
  ctx.fillStyle = '#fff';
  ctx.beginPath();
  ctx.ellipse(0, headY, 30, 20, 0, 0, Math.PI*2);
  ctx.fill();

  // 赤い虹彩（大きめ）
  const irisGrad = ctx.createRadialGradient(0, headY, 0, 0, headY, 16);
  irisGrad.addColorStop(0,'#ffdede');
  irisGrad.addColorStop(0.5,'#ff3a3a');
  irisGrad.addColorStop(1,'#5a0000');
  ctx.fillStyle = irisGrad;
  ctx.beginPath();
  ctx.ellipse(0, headY, 18, 13, 0, 0, Math.PI*2);
  ctx.fill();

  // 瞳孔（主人公方向にオフセット）
  const rMax = 7.5;                        // 眼内の可動半径
  const px = Math.cos(ang) * rMax;
  const py = Math.sin(ang) * rMax * 0.75;  // 縦は少し抑える
  ctx.fillStyle = '#0a0000';
  ctx.beginPath();
  ctx.ellipse(px, headY + py, 4.2, 4.2, 0, 0, Math.PI*2);
  ctx.fill();

  // ハイライト & 赤い外光
  ctx.fillStyle = '#fff';
  ctx.beginPath();
  ctx.arc(px - 1.2, headY + py - 1.2, 1.4, 0, Math.PI*2);
  ctx.fill();

  ctx.save();
  ctx.shadowColor = 'rgba(255,0,0,0.85)';
  ctx.shadowBlur  = 16;
  ctx.globalAlpha = 0.7;
  ctx.strokeStyle = 'rgba(255,0,0,0.35)';
  ctx.lineWidth   = 2;
  ctx.beginPath();
  ctx.ellipse(0, headY, 30, 20, 0, 0, Math.PI*2);
  ctx.stroke();
  ctx.restore();

  ctx.restore();
}


  // 敵クラス
// ==== 修正版 Enemy クラス（丸ピンク＋大きな目＋激しい羽） ====
// 敵クラス（ピンク狼＋🍾ビーム）
class Enemy {
  constructor() {
    this.width  = 56;
    this.height = 56;
    this.speed  = (1 + Math.random()) * SPEED_MULT;
    this.vocab = getRandomVocab();
    this.lastBeamTime  = 0;
    this.beamInterval  = (2000 + Math.random() * 2000) / FIRE_RATE;
    this.phase = Math.random() * Math.PI * 2;
    this.x = this.findValidPosition();
    this.y = -this.height;
    currentVocabIndex++;
  }

  findValidPosition() {
    const CARD_W = 120, CARD_H = 84, CARD_OFFSET = 16;
    const cardRectAt = (x, futureY, w, h) => ({
      x: x + w/2 - CARD_W/2,
      y: futureY - (CARD_H + CARD_OFFSET),
      w: CARD_W, h: CARD_H
    });
    const overlap = (a, b) =>
      a.x < b.x + b.w && a.x + a.w > b.x && a.y < b.y + b.h && a.y + a.h > b.y;

    const attemptsMax = 40, margin = 8;
    for (let attempt = 0; attempt < attemptsMax; attempt++) {
      const x = Math.random() * (canvas.width - this.width);
      const mine = cardRectAt(x, -60, this.width, this.height);
      let ok = true;
      for (const other of gameState.enemies) {
        if (!other) continue;
        const theirs = cardRectAt(other.x, other.y, other.width, other.height);
        const a = { x: mine.x - margin, y: mine.y - margin, w: mine.w + margin*2, h: mine.h + margin*2 };
        const b = { x: theirs.x - margin, y: theirs.y - margin, w: theirs.w + margin*2, h: theirs.h + margin*2 };
        if (overlap(a, b)) { ok = false; break; }
      }
      if (ok) return x;
    }
    return Math.random() * (canvas.width - this.width);
  }

  // 🍾ビーム発射（矩形当たり判定を保つ）
  fireBeam(){
    const w = 24, h = 24;
    gameState.enemyBeams.push({
      kind: 'wine',
      emoji: '🍾',
      x: this.x + this.width/2 - w/2,
      y: this.y + this.height - 2,
      width: w,
      height: h,
      speed: 3 * SPEED_MULT
    });
  }

  update() {
    const CARD_W = 120, CARD_H = 84, CARD_OFFSET = 16, margin = 6;
    const cardRectAt = (x, futureY, w, h) => ({
      x: x + w/2 - CARD_W/2,
      y: futureY - (CARD_H + CARD_OFFSET),
      w: CARD_W, h: CARD_H
    });
    const overlap = (a, b) =>
      a.x < b.x + b.w && a.x + a.w > b.x && a.y < b.y + b.h && a.y + a.h > b.y;

    // 落下（前方の敵カードに詰めすぎない）
    let nextY = this.y + this.speed * dt;
    const nextRect = cardRectAt(this.x, nextY, this.width, this.height);
    if (Array.isArray(gameState.enemies)) {
      for (const other of gameState.enemies) {
        if (!other || other === this) continue;
        if (other.y >= this.y) {
          const oRect = cardRectAt(other.x, other.y, other.width, other.height);
          const a = { x: nextRect.x - margin, y: nextRect.y - margin, w: nextRect.w + margin*2, h: nextRect.h + margin*2 };
          const b = { x: oRect.x - margin,  y: oRect.y  - margin,  w: oRect.w  + margin*2, h: oRect.h  + margin*2 };
          if (overlap(a, b)) {
            const maxNextY = oRect.y - margin + CARD_OFFSET;
            nextY = Math.min(nextY, maxNextY);
          }
        }
      }
    }
    this.y = nextY;

    // ビームタイマー
    const now = Date.now();
    if (!Number.isFinite(this.lastBeamTime)) this.lastBeamTime = 0;
    if (!Number.isFinite(this.beamInterval)) this.beamInterval = 2000 + Math.random()*2000;

    if (now - this.lastBeamTime >= this.beamInterval) {
      this.fireBeam();
      this.lastBeamTime = now;
      this.beamInterval = (1500 + Math.random()*1500) / FIRE_RATE;
    }

    // 画面外で消滅
    return this.y < canvas.height + 100;
  }

  draw() {
    const cx = this.x + this.width/2;
    const cy = this.y + this.height/2;
    const t  = (gameState.animationTime||0) / 60;
    if (!Number.isFinite(cx) || !Number.isFinite(cy)) return;

    // ← ピンク狼を描画
    drawPinkWolf(ctx, cx, cy, this.width, this.height, t);

    // ← 単語カード（あなたの現行ロジックを維持）
    {
      const cardW = 120, cardH = 84, radius = 12;
      const left = cx - cardW/2;
      const top  = this.y - (cardH + 16);
      const rr = (x,y,w,h,r)=>{
        ctx.beginPath();
        ctx.moveTo(x+r,y);
        ctx.lineTo(x+w-r,y);
        ctx.quadraticCurveTo(x+w,y,x+w,y+r);
        ctx.lineTo(x+w,y+h-r);
        ctx.quadraticCurveTo(x+w,y+h,x+w-r,y+h);
        ctx.lineTo(x+r,y+h);
        ctx.quadraticCurveTo(x,y+h,x,y+h-r);
        ctx.lineTo(x,y+r);
        ctx.quadraticCurveTo(x,y,x+r,y);
        ctx.closePath();
      };
      ctx.save();
      ctx.fillStyle = 'rgba(255,255,255,0.96)'; rr(left, top, cardW, cardH, radius); ctx.fill();
      ctx.lineWidth = 1; ctx.strokeStyle = 'rgba(0,0,0,0.08)'; rr(left, top, cardW, cardH, radius); ctx.stroke();
      ctx.textAlign = 'center'; ctx.fillStyle = '#000';
      ctx.font = 'bold 15px Arial'; ctx.strokeStyle = 'rgba(0,0,0,0.25)'; ctx.lineWidth = 2;
      ctx.strokeText(this.vocab.word, cx, top+22); ctx.fillText(this.vocab.word, cx, top+22);

      ctx.font = 'bold 12px Arial';
      for(let i=0;i<4;i++){
        const opt   = this.vocab.options[i];
        const emoji = emojiMap[opt] || '';
        const label = `${i+1}. ${opt}${emoji}`;
        const y = top + 42 + i*13;
        const maxW = cardW - 12;
        let s = label;
        while(ctx.measureText(s).width > maxW && s.length > 2) s = s.slice(0,-2)+'…';
        ctx.strokeText(s, cx, y); ctx.fillText(s, cx, y);
      }
      ctx.restore();
    }
  }
}


function drawWordCard(vocab, centerX, top, cardW = 320, cardH = 220) {
  const s = cardW / 160;                    // 160基準の倍率（320なら2倍）
  const radius = 12 * s;
  const left = centerX - cardW / 2;

  // 角丸パス
  const rr = (x, y, w, h, r) => {
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.lineTo(x + w - r, y);
    ctx.quadraticCurveTo(x + w, y, x + w, y + r);
    ctx.lineTo(x + w, y + h - r);
    ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
    ctx.lineTo(x + r, y + h);
    ctx.quadraticCurveTo(x, y + h, x, y + h - r);
    ctx.lineTo(x, y + r);
    ctx.quadraticCurveTo(x, y, x + r, y);
    ctx.closePath();
  };

  // 背景
  ctx.save();
  ctx.fillStyle = 'rgba(255,255,255,0.96)';
  rr(left, top, cardW, cardH, radius);
  ctx.fill();

  ctx.lineWidth = 1 * s;
  ctx.strokeStyle = 'rgba(0,0,0,0.08)';
  rr(left, top, cardW, cardH, radius);
  ctx.stroke();

  // テキスト共通設定
  ctx.textAlign = 'center';
  ctx.fillStyle = '#000';
  ctx.strokeStyle = 'rgba(0,0,0,0.25)';
  ctx.lineWidth = 2 * s;

  // 単語（2倍相当：15px → 30px）
  ctx.font = `bold ${15 * s}px Arial`;
  ctx.strokeText(vocab.word, centerX, top + 24 * s);
  ctx.fillText  (vocab.word, centerX, top + 24 * s);

  // 選択肢（2倍相当：12px → 24px）
  ctx.font = `bold ${12 * s}px Arial`;
  const startY = top + 46 * s;        // 46 → 2倍
  const stepY  = 14 * s;              // 14 → 2倍
  const maxW   = cardW - 12 * s;

  for (let i = 0; i < 4; i++) {
    const text = `${i + 1}. ${vocab.options[i]}`;
    let display = text;
    // はみ出しは幅で判定して“…”省略
    while (ctx.measureText(display).width > maxW && display.length > 2) {
      display = display.slice(0, -2) + '…';
    }
    const y = startY + i * stepY;
    ctx.strokeText(display, centerX, y);
    ctx.fillText  (display, centerX, y);
  }

  ctx.restore();
}


    // ボス
    // ★★★ ここから Boss を全置換 ★★★
   // === Boss: Black Wolf (emoji wine beams & contact damage) ==============
// === Boss: 新サイクル ===
// 1) 黒いシャドーボール 10発/秒 5秒（壁で跳ね返り、5回で消滅）
// 2) 休憩 3秒
// 3) 波状攻撃：毎秒10発を360°リングで5秒
// 4) レインボー光線：追尾する1発（被弾で操作反転）5秒
// 5) 休憩 3秒
// 6) 360°ミサイル（同時に5方向）1秒
// 7) 休憩 5秒 → 1に戻る
class Boss {
  constructor(){
    const pw = (gameState.player?.width  ?? 50);
    const ph = (gameState.player?.height ?? 40);
    const k = 1.5;                     // ★ ここを 3.0 に
    this.width  = Math.round(pw * k);
    this.height = Math.round(ph * k);
    // （以下そのまま）
    this.x = canvas.width/2 - this.width/2;
    this.y = canvas.height/2 - this.height/2;
    this.speed = 10 * SPEED_MULT;
    this.life  = 10;
    this.vocab = getRandomBossVocab();

    this.phaseIndex = 0;
    // Boss.constructor 内
this.phases = [
  { type:'rainbow', duration: 5000 },
  { type:'rest',    duration: BOSS_REST5 },
  { type:'shadow',  duration: BOSS_SHADOW_TIME },
  { type:'rest',    duration: BOSS_REST5 },
  { type:'waves',   duration: BOSS_WAVE_TIME },
  { type:'laser',   duration: 5000 },   // ← 5秒
  { type:'rest',    duration: BOSS_REST5 },
];

    this.phaseStart = performance.now();
    this._onEnterPhase(this.phases[0].type, this.phaseStart);

    // 接触ダメージ
    this.lastTouchTime = 0;
    this.touchCooldown = 900;
    this.lastMoveChange = 0;
    this.moveTarget = {x:this.x, y:this.y};
  }
  
// Boss クラス内に追加
_fireLaserBurst4(){
  const now = performance.now();
  const cx = this.x + this.width/2;
  const cy = this.y + this.height/2;

  const spd = 20 * SPEED_MULT;
  const life = 1200;          // 1.2秒で自然消滅（画面外でも消える）
  const end  = now + life;

  // 縦レーザー（上下）
  const vw = 28, vh = 140;
  // 上
  gameState.bossBeams.push({
    type:'laser', x: cx - vw/2, y: this.y - vh + 4, w: vw, h: vh,
    vx: 0, vy: -spd, until: end
  });
  // 下
  gameState.bossBeams.push({
    type:'laser', x: cx - vw/2, y: this.y + this.height - 4, w: vw, h: vh,
    vx: 0, vy: spd, until: end
  });

  // 横レーザー（左右）
  const hw = 140, hh = 28;
  // 左
  gameState.bossBeams.push({
    type:'laser', x: this.x - hw + 4, y: cy - hh/2, w: hw, h: hh,
    vx: -spd, vy: 0, until: end
  });
  // 右
  gameState.bossBeams.push({
    type:'laser', x: this.x + this.width - 4, y: cy - hh/2, w: hw, h: hh,
    vx: spd, vy: 0, until: end
  });
}

_onEnterPhase(type, now){
  if (type === 'shadow'){
    this.shadowInterval = (1000 / BOSS_SHADOW_RATE) / FIRE_RATE;
    this.nextShadowTime = now;

  } else if (type === 'waves'){
    this.waveInterval = 1000 / FIRE_RATE; // 毎秒1リング
    this.nextWaveTime = now;

  } else if (type === 'rainbow'){
    // フィールドに1発も無ければ生成（“当たるまで消えない”）
    const hasOne = gameState.bossBeams.some(b => b.type === 'rainbow');
    if (!hasOne){
      const cx = this.x + this.width/2, cy = this.y + this.height/2;
      gameState.bossBeams.push({
        type:'rainbow', x:cx, y:cy, r:50,            // ← 3倍サイズ
        vx:0, vy:0,
        seek:{ strength: 0.5 * SPEED_MULT, maxSpeed: 12 * SPEED_MULT },
        hue0: Math.floor(Math.random()*360)
      });
    }

  } else if (type === 'laser'){
    // 1秒間30発（= 33.33ms間隔）で5秒間
    this.laserInterval = (1000/30) / FIRE_RATE;
    this.nextLaserTime = now;
  }
}


_fireLaserBeam(){
  const cx = this.x + this.width/2;
  const headY = this.y + this.height;

  const w = 28, h = 140;           // 巨大感
  const spd = 20 * SPEED_MULT;     // 直進スピード（下方向＝正面）

  gameState.bossBeams.push({
    type:'laser',
    x: cx - w/2, y: headY - 10,    // ボスの正面から
    w, h,
    vx: 0, vy: spd
  });
}


_onExitPhase(type){
  // ★ レインボー弾は“ヒットするまで残す”ので、ここでは何もしない
  // if (type === 'rainbow'){ ... } ← この掃除処理は削除
}

  _fireShadowBall(){
    const cx = this.x + this.width/2, cy = this.y + this.height/2;
    const ang = Math.random() * Math.PI*2;
    const spd = 6.5 * SPEED_MULT;
    gameState.bossBeams.push({
      type:'shadow', x:cx, y:cy, r:9,
      vx: Math.cos(ang)*spd, vy: Math.sin(ang)*spd,
      bounces:0, maxBounces:5
    });
  }
  _fireWaveRing(){
    const cx = this.x + this.width/2, cy = this.y + this.height/2;
    for (let i=0;i<10;i++){
      const ang = (i/10) * Math.PI*2;
      const spd = 5.5 * SPEED_MULT;
      gameState.bossBeams.push({
        type:'wave', x:cx, y:cy, r:8,
        vx: Math.cos(ang)*spd, vy: Math.sin(ang)*spd
      });
    }
  }

  _updatePhase(now){
  const cur = this.phases[this.phaseIndex];
  const elapsed = now - this.phaseStart;

  if (cur.type === 'shadow'){
    while (now >= this.nextShadowTime && elapsed <= cur.duration){
      this._fireShadowBall();
      this.nextShadowTime += this.shadowInterval;
    }
  } else if (cur.type === 'waves'){
    while (now >= this.nextWaveTime && elapsed <= cur.duration){
      this._fireWaveRing();
      this.nextWaveTime += this.waveInterval;
    }
  } else if (cur.type === 'laser'){
    while (now >= this.nextLaserTime && elapsed <= cur.duration){
      this._fireLaserBurst4();                // ← 4方向 まとめて1“発”
      this.nextLaserTime += this.laserInterval;
    }
  }

  if (elapsed >= cur.duration){
    this._onExitPhase(cur.type);
    this.phaseIndex = (this.phaseIndex + 1) % this.phases.length;
    this.phaseStart = now;
    this._onEnterPhase(this.phases[this.phaseIndex].type, now);
  }
}



  _contactDamage(now){
    const p = gameState.player;
    const hit = (p.x < this.x + this.width &&
                 p.x + p.width > this.x &&
                 p.y < this.y + this.height &&
                 p.y + p.height > this.y);
    if (hit && (now - this.lastTouchTime > this.touchCooldown)){
      this.lastTouchTime = now;
      gameState.life--; updateUI?.();
      gameState.explosions.push(new Explosion(p.x+p.width/2, p.y-16));
    }
  }

  pickNewTarget(){
    const pad = 8;
    this.moveTarget.x = Math.random()*(canvas.width - this.width - pad*2) + pad;
    this.moveTarget.y = Math.random()*(canvas.height - this.height - pad*2) + pad;
  }

  update(now){
    this._updatePhase(now);

    if (now - this.lastMoveChange > 400){
      this.lastMoveChange = now;
      this.pickNewTarget();
    }
    const dx = this.moveTarget.x - this.x;
    const dy = this.moveTarget.y - this.y;
    const d  = Math.hypot(dx,dy) || 1;
    this.x += (dx/d)*this.speed * dt;
    this.y += (dy/d)*this.speed * dt;

    const pad = 4;
    this.x = Math.max(pad, Math.min(this.x, canvas.width - this.width - pad));
    this.y = Math.max(pad, Math.min(this.y, canvas.height - this.height - pad));

    this._contactDamage(now);
  }

  nextWord(){
    this.vocab = getRandomBossVocab();
  }

  draw(){
  const cx = this.x + this.width/2;
  const cy = this.y + this.height/2;
  const t  = performance.now()/1000;

  // ボス描画（あなたの現在の関数名に合わせて）
  // 例）drawBlackAngel(ctx, cx, cy, this.width, this.height, t, px, py);
  //     または drawBlackWolf(...)
  drawBlackAngel?.(ctx, cx, cy, this.width, this.height, t)
  // ▼ 単語カード：上下で自動回避（ボスと重ならない）
  const CARD_W = 320;      // 2倍想定
  const CARD_H = 220;      // 2倍想定
  const MARGIN = 24;       // ボスとカードの隙間
  const PAD    = 10;       // 画面端の余白

  // できれば上に置く
  let cardTopPref = this.y - (CARD_H + MARGIN);
  let cardTop;

  if (cardTopPref >= PAD) {
    // 上に十分なスペースがある→上に置く（重ならない）
    cardTop = cardTopPref;
  } else {
    // 上が狭い→下に置く（画面外に出ないように調整）
    cardTop = Math.min(canvas.height - CARD_H - PAD, this.y + this.height + MARGIN);
  }

  drawWordCard(this.vocab, cx, cardTop, CARD_W, CARD_H);
}

}


        
    // メッセージクラス
    class FloatingMessage {
        constructor(x, y, text, color) {
            this.x = x;
            this.y = y;
            this.text = text;
            this.color = color;
            this.life = 30; // 0.5秒 (60fps基準で30フレーム)
            this.maxLife = 30;
            this.startY = y;
        }
        
        update() {
            this.life--;
            // メッセージが上に浮上
            this.y = this.startY - (this.maxLife - this.life) * 0.2;
            return this.life > 0;
        }
        
        draw() {
            const alpha = this.life / this.maxLife;
            ctx.save();
            ctx.globalAlpha = alpha;
            ctx.font = 'bold 20px Arial';
            ctx.fillStyle = this.color;
            ctx.textAlign = 'center';
            ctx.strokeStyle = '#000000';
            ctx.lineWidth = 2;
            
            // 文字の縁取り
            ctx.strokeText(this.text, this.x, this.y);
            // 文字本体
            ctx.fillText(this.text, this.x, this.y);
            
            ctx.restore();
        }
    }
    class Explosion {
        constructor(x, y) {
            this.x = x;
            this.y = y;
            this.radius = 10;
            this.maxRadius = 40;
            this.life = 30;
            this.maxLife = 30;
        }
        
        update() {
            this.life--;
            this.radius = (this.maxRadius * (this.maxLife - this.life)) / this.maxLife;
            return this.life > 0;
        }
        
        draw() {
            const alpha = this.life / this.maxLife;
            ctx.save();
            ctx.globalAlpha = alpha;
            
            // 外側の爆発
            ctx.fillStyle = '#ff6600';
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fill();
            
            // 内側の爆発
            ctx.fillStyle = '#ffff00';
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius * 0.6, 0, Math.PI * 2);
            ctx.fill();
            
            // 中心の爆発
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius * 0.3, 0, Math.PI * 2);
            ctx.fill();
            
            ctx.restore();
        }
    }
    // === 番号別カラー＆数字入りミサイル ===
    class Missile {
    constructor(x, y, number) {
        this.x = x;
        this.y = y;
        this.width = 22;   // 少し大きくして数字を見やすく
        this.height = 34;
        this.speed = 9 * SPEED_MULT;
        this.number = number;

        // 番号→色パレット
        const palette = {
        1: { base: '#e53935', light: '#ff7673', dark: '#b71c1c' }, // 赤
        2: { base: '#29b6f6', light: '#7fd3ff', dark: '#0288d1' }, // 水色
        3: { base: '#ffa726', light: '#ffcc80', dark: '#ef6c00' }, // オレンジ
        4: { base: '#26c684', light: '#7be0b3', dark: '#1b9e68' }, // 緑
        default: { base: '#9e9e9e', light: '#cfcfcf', dark: '#616161' }
        };
        this.colors = palette[number] || palette.default;
    }

    update() {
        this.y -= this.speed * dt;
        return this.y > -60;
    }

    draw() {
        const c = this.colors;
        const { x, y, width: w, height: h } = this;

        // ボディのグラデ
        const g = ctx.createLinearGradient(x, y, x, y + h);
        g.addColorStop(0, c.light);
        g.addColorStop(0.5, c.base);
        g.addColorStop(1, c.dark);

        ctx.save();

        // 角丸ボディ
        ctx.fillStyle = g;
        ctx.strokeStyle = '#111';
        ctx.lineWidth = 1.2;
        const r = 5;
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.lineTo(x + w - r, y);
        ctx.quadraticCurveTo(x + w, y, x + w, y + r);
        ctx.lineTo(x + w, y + h - r);
        ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
        ctx.lineTo(x + r, y + h);
        ctx.quadraticCurveTo(x, y + h, x, y + h - r);
        ctx.lineTo(x, y + r);
        ctx.quadraticCurveTo(x, y, x + r, y);
        ctx.closePath();
        ctx.fill();
        ctx.stroke();

        // ノーズ（先端）
        ctx.beginPath();
        ctx.moveTo(x + w / 2, y - 10);
        ctx.lineTo(x + w, y + 4);
        ctx.lineTo(x, y + 4);
        ctx.closePath();
        ctx.fillStyle = c.base;
        ctx.fill();
        ctx.stroke();

        // フィン（左右）
        ctx.fillStyle = c.dark;
        ctx.beginPath(); // 左
        ctx.moveTo(x, y + h * 0.55);
        ctx.lineTo(x - 10, y + h * 0.75);
        ctx.lineTo(x, y + h * 0.8);
        ctx.closePath();
        ctx.fill();

        ctx.beginPath(); // 右
        ctx.moveTo(x + w, y + h * 0.55);
        ctx.lineTo(x + w + 10, y + h * 0.75);
        ctx.lineTo(x + w, y + h * 0.8);
        ctx.closePath();
        ctx.fill();

        // 中央の番号（アウトライン付きで視認性UP）
        ctx.font = 'bold 18px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.lineWidth = 3;
        ctx.strokeStyle = 'rgba(0,0,0,0.5)';
        ctx.strokeText(String(this.number), x + w / 2, y + h / 2);
        ctx.fillStyle = '#ffffff';
        ctx.fillText(String(this.number), x + w / 2, y + h / 2);

        // 後部の噴射炎（ちょい演出）
        const flameH = 10 + Math.random() * 6;
        const flameW = 8;
        ctx.beginPath();
        ctx.moveTo(x + w / 2, y + h + flameH);
        ctx.lineTo(x + w / 2 - flameW / 2, y + h);
        ctx.lineTo(x + w / 2 + flameW / 2, y + h);
        ctx.closePath();
        const fg = ctx.createLinearGradient(x, y + h, x, y + h + flameH);
        fg.addColorStop(0, 'rgba(255,255,255,0.95)');
        fg.addColorStop(1, 'rgba(255,180,0,0.85)');
        ctx.fillStyle = fg;
        ctx.fill();

        ctx.restore();
    }
    }

        
    // プレイヤー描画
    // ===== 主役キャラ：赤目ぐるぐる＋触角ウネウネ版 =====
    // ===== 主役キャラ：羽ばたき激化（残像つき）版 =====
    function drawPlayer() {
    const p = gameState.player;
    const t = gameState.animationTime;
    const time = t * (1/60);

    ctx.save();

    // ── 羽ばたきパラメータ（激しめ） ──
    // 周波数を上げ、角度・スケールの振れ幅も増やす
    const flapBase = Math.sin(time * 16.0);               // 16rad/s ≒ 2.55Hz
    const flap = Math.sign(flapBase) * Math.pow(Math.abs(flapBase), 0.85); // エッジ強調
    const wingAngle = flap * 0.85;                        // 最大 ~0.85rad ≒ 49°
    const wingScaleX = 1 + Math.abs(flap) * 0.60;         // 横に伸縮（スピード感）
    const wingJitter = Math.sin(time * 60) * 0.02;        // 微細な震え（高速感）

    // 残像を描く共通の“翼の形”
    const drawWingShape = () => {
        ctx.fillStyle = '#220000';
        ctx.strokeStyle = '#660000';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.quadraticCurveTo(18, -12, 34, -2);
        ctx.quadraticCurveTo(28, 8,  16, 14);
        ctx.quadraticCurveTo(8,  10, 0,  0);
        ctx.closePath();
        ctx.fill();
        ctx.stroke();

        // 骨ライン
        ctx.strokeStyle = '#ff0000';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(0,0);         ctx.lineTo(14,-8);
        ctx.moveTo(0,0);         ctx.lineTo(18, 6);
        ctx.moveTo(10, 2);       ctx.lineTo(26,-2);
        ctx.stroke();
    };

    // 翼を描く（side: -1 左 / +1 右）
    const drawWing = (originX, originY, side) => {
        // 残像3枚 → 奥から手前へ
        for (let i = 3; i >= 1; i--) {
        const trailScale = wingScaleX * (1 - i * 0.10);
        const trailAngle = wingAngle * (1 - i * 0.18) + wingJitter * i;
        ctx.save();
        ctx.globalAlpha = 0.12 * i;           // 残像の濃さ
        ctx.translate(originX, originY);
        ctx.scale(side * trailScale, 1);
        ctx.rotate(side * (Math.PI/8 + trailAngle));
        drawWingShape();
        ctx.restore();
        }
        // メイン翼（最も濃い）
        ctx.save();
        ctx.globalAlpha = 1;
        ctx.translate(originX, originY);
        ctx.scale(side * wingScaleX, 1);
        ctx.rotate(side * (Math.PI/8 + wingAngle));
        drawWingShape();
        ctx.restore();
    };

    // 左右の翼
    drawWing(p.x + 10, p.y + 16, -1);
    drawWing(p.x + 40, p.y + 16, +1);

    // ── 本体 ──
    ctx.fillStyle = '#2d1f0f';
    ctx.beginPath();
    ctx.ellipse(p.x + 25, p.y + 20, 20, 15, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#1a1008';
    ctx.beginPath();
    ctx.ellipse(p.x + 25, p.y + 8, 12, 10, 0, 0, Math.PI * 2);
    ctx.fill();

    // ── 目：ぐるぐる回転（前回仕様） ──
    const Lx = p.x + 18, Ly = p.y + 6;
    const Rx = p.x + 32, Ry = p.y + 6;
    const eyeR = 8, irisR = 7, pupilR = 3.2;
    const spinL = time * 5.0;
    const spinR = time * 5.0 + 0.6;
    const blink = 0.88 + 0.12 * Math.abs(Math.sin(time * 1.2));

    ctx.fillStyle = '#ffffff';
    ctx.beginPath(); ctx.ellipse(Lx, Ly, eyeR, eyeR*blink, 0, 0, Math.PI*2); ctx.fill();
    ctx.beginPath(); ctx.ellipse(Rx, Ry, eyeR, eyeR*blink, 0, 0, Math.PI*2); ctx.fill();

    const drawRotIris = (cx, cy, spin) => {
        ctx.fillStyle = '#ff1a1a';
        ctx.beginPath();
        ctx.ellipse(cx, cy, irisR, irisR*blink, 0, 0, Math.PI*2);
        ctx.fill();

        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(spin);
        ctx.globalAlpha = 0.75;
        ctx.fillStyle = '#ff8080';
        for (let k = 0; k < 3; k++) {
        ctx.rotate((Math.PI*2)/3);
        ctx.beginPath();
        ctx.ellipse(irisR*0.35, 0, irisR*0.45, irisR*0.18*blink, 0, 0, Math.PI*2);
        ctx.fill();
        }
        ctx.restore();

        const orbit = 2.8;
        const px = cx + Math.cos(spin) * orbit;
        const py = cy + Math.sin(spin) * orbit * blink;
        ctx.fillStyle = '#111';
        ctx.beginPath();
        ctx.ellipse(px, py, pupilR, pupilR*blink, 0, 0, Math.PI*2);
        ctx.fill();
        ctx.fillStyle = '#fff';
        ctx.beginPath();
        ctx.arc(px - 1.0, py - 1.2, 1.2, 0, Math.PI*2);
        ctx.fill();
    };
    drawRotIris(Lx, Ly, spinL);
    drawRotIris(Rx, Ry, spinR);

    // ── 触角：ウネウネ（前回仕様） ──
    const drawWigglyAntenna = (bx, by, dir, len=28, seg=6, amp=5, phase=0) => {
        const pts = [];
        for (let i = 0; i <= seg; i++) {
        const u = i / seg;
        const x = bx + dir * (u * len);
        const y = by - u * (len * 0.6) + Math.sin((u*6 + time*2) + phase) * amp * (1 - u*0.2);
        pts.push({x, y});
        }
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.beginPath();
        ctx.moveTo(pts[0].x, pts[0].y);
        for (let i = 1; i < pts.length; i++) {
        const p0 = pts[i-1], p1 = pts[i];
        const cx = (p0.x + p1.x) / 2;
        const cy = (p0.y + p1.y) / 2;
        ctx.quadraticCurveTo(p0.x, p0.y, cx, cy);
        }
        ctx.stroke();

        const tip = pts[pts.length-1];
        const tipJitter = Math.sin(time*8 + phase) * 1.3;
        ctx.fillStyle = '#ff0000';
        ctx.beginPath();
        ctx.arc(tip.x + tipJitter, tip.y + tipJitter, 2.4, 0, Math.PI*2);
        ctx.fill();
    };
    drawWigglyAntenna(p.x + 18, p.y + 4, -1, 30, 7, 5.5, 0.0);
    drawWigglyAntenna(p.x + 32, p.y + 4, +1, 30, 7, 5.5, 1.1);

    // 背中の縞
    ctx.fillStyle = '#3d2f1f';
    for (let i = 0; i < 3; i++) ctx.fillRect(p.x + 10, p.y + 18 + i*4, 30, 2);

    // 足（簡易）
    const legT = Math.sin(time * 3.2) * 1.2;
    ctx.strokeStyle = '#1a1008';
    ctx.lineWidth = 2;
    ctx.beginPath();
    // 左3本
    ctx.moveTo(p.x + 10, p.y + 15); ctx.lineTo(p.x + 5 + legT, p.y + 25);
    ctx.moveTo(p.x + 12, p.y + 20); ctx.lineTo(p.x + 7 - legT, p.y + 30);
    ctx.moveTo(p.x + 14, p.y + 25); ctx.lineTo(p.x + 9 + legT, p.y + 35);
    // 右3本
    ctx.moveTo(p.x + 40, p.y + 15); ctx.lineTo(p.x + 45 - legT, p.y + 25);
    ctx.moveTo(p.x + 38, p.y + 20); ctx.lineTo(p.x + 43 + legT, p.y + 30);
    ctx.moveTo(p.x + 36, p.y + 25); ctx.lineTo(p.x + 41 - legT, p.y + 35);
    ctx.stroke();

    ctx.restore();
    }
    // ボス出現の流れ：条件到達→3秒WARNING→出現
    function spawnBossIfReady() {
    const now = performance.now();

    // クリア済み or フィナーレ中は何もしない（WARNINGも出さない）
    if (gameState.bossCleared || gameState.bossFinaleActive) return;
    // 条件到達＆未処理なら警告を開始
    if (!gameState.boss && !gameState.bossWarningActive && !gameState.bossPending &&
        gameState.score >= gameState.bossTriggerScore) {
        gameState.bossWarningActive = true;
        gameState.bossWarningStart = now;
        gameState.bossPending = true;
    }

    // 警告から3秒経過でボス出現＆通常敵一掃
    if (gameState.bossWarningActive && now - gameState.bossWarningStart >= 3000) {
        gameState.bossWarningActive = false;
        gameState.bossPending = false;

        gameState.enemies = [];
        gameState.enemyBeams = [];

        gameState.boss = new Boss();
    }
    }

        
// 敵ビーム更新・描画
// 敵ビーム更新・描画（高速＆丸いレーザー）
function updateEnemyBeams() {
  // まず移動＆当たり判定＆生存フィルタ
  gameState.enemyBeams = gameState.enemyBeams.filter(beam => {
    beam.y += beam.speed * dt;

    // プレイヤー衝突（AABB）
    if (beam.x < gameState.player.x + gameState.player.width &&
        beam.x + beam.width > gameState.player.x &&
        beam.y < gameState.player.y + gameState.player.height &&
        beam.y + beam.height > gameState.player.y) {

      // 爆発はプレイヤー前方で
      gameState.explosions.push(new Explosion(
        gameState.player.x + gameState.player.width / 2,
        gameState.player.y - 20
      ));
      gameState.life--;
      updateUI();
      return false; // 消滅
    }

    // 画面外で消滅
    return beam.y < canvas.height + 60;
  });

  // 丸いカプセル形のビームを描画
  const drawCapsule = (left, top, w, h, hue) => {
    const r = w / 2;
    // カプセル外形パス
    const path = () => {
      ctx.beginPath();
      ctx.moveTo(left + r, top);
      ctx.lineTo(left + w - r, top);
      ctx.quadraticCurveTo(left + w, top, left + w, top + r);
      ctx.lineTo(left + w, top + h - r);
      ctx.quadraticCurveTo(left + w, top + h, left + w - r, top + h);
      ctx.lineTo(left + r, top + h);
      ctx.quadraticCurveTo(left, top + h, left, top + h - r);
      ctx.lineTo(left, top + r);
      ctx.quadraticCurveTo(left, top, left + r, top);
      ctx.closePath();
    };

    // 外側のグロー＋カラーグラデ
    ctx.save();
    ctx.shadowColor = `hsla(${hue}, 100%, 60%, 0.95)`;
    ctx.shadowBlur = 20;

    const g = ctx.createLinearGradient(left, top, left, top + h);
    g.addColorStop(0.00, `hsla(${hue}, 100%, 75%, 0.95)`);
    g.addColorStop(0.45, `hsla(${hue}, 100%, 60%, 0.95)`);
    g.addColorStop(0.55, `rgba(255,255,255,0.98)`); // コアを白っぽく
    g.addColorStop(1.00, `hsla(${hue}, 100%, 50%, 0.95)`);

    ctx.fillStyle = g;
    path();
    ctx.fill();

    // 内側コア（細い白い芯）
    ctx.shadowBlur = 0;
    ctx.fillStyle = 'rgba(255,255,255,0.9)';
    const coreW = Math.max(2, w * 0.28);
    const coreLeft = left + (w - coreW) / 2;
    const coreTop = top + 4;
    const coreH = h - 8;

    // 芯も丸角で
    const cr = coreW / 2;
    ctx.beginPath();
    ctx.moveTo(coreLeft + cr, coreTop);
    ctx.lineTo(coreLeft + coreW - cr, coreTop);
    ctx.quadraticCurveTo(coreLeft + coreW, coreTop, coreLeft + coreW, coreTop + cr);
    ctx.lineTo(coreLeft + coreW, coreTop + coreH - cr);
    ctx.quadraticCurveTo(coreLeft + coreW, coreTop + coreH, coreLeft + coreW - cr, coreTop + coreH);
    ctx.lineTo(coreLeft + cr, coreTop + coreH);
    ctx.quadraticCurveTo(coreLeft, coreTop + coreH, coreLeft, coreTop + coreH - cr);
    ctx.lineTo(coreLeft, coreTop + cr);
    ctx.quadraticCurveTo(coreLeft, coreTop, coreLeft + cr, coreTop);
    ctx.closePath();
    ctx.fill();

    ctx.restore();
  };

  // 描画
  gameState.enemyBeams.forEach(beam => {
    drawCapsule(beam.x, beam.y, beam.width, beam.height, beam.hue || 320);
  });
}

   // ★ ここから全置換：ボス弾（🍾🍷対応 & フォールバックで円弾もOK）
// === Boss弾の更新＆描画（跳ね返り・追尾・色分け） ===
function updateBossBeams(){
  const p = gameState.player;
  const now = performance.now();

  gameState.bossBeams = gameState.bossBeams.filter(b => {
    // 追尾（レインボー）
    if (b.type === 'rainbow' && b.seek){
      const dx = (p.x + p.width/2)  - b.x;
      const dy = (p.y + p.height/2) - b.y;
      const d  = Math.hypot(dx, dy) || 1;
      const ax = (dx/d) * b.seek.strength;
      const ay = (dy/d) * b.seek.strength;
      b.vx = (b.vx + ax); b.vy = (b.vy + ay);
      const sp = Math.hypot(b.vx, b.vy);
      const cap = b.seek.maxSpeed;
      if (sp > cap){ b.vx = b.vx/sp*cap; b.vy = b.vy/sp*cap; }
    }

    // 移動（円弾＝中心座標／レーザー＝矩形左上）
    b.x += (b.vx||0) * dt;
    b.y += (b.vy||0) * dt;

    // レーザーの寿命（任意）
    if (b.type === 'laser' && Number.isFinite(b.until) && now > b.until) {
      return false;
    }

    // 跳ね返り（シャドー：円）
    if (b.type === 'shadow'){
      const r = b.r||8;
      if (b.x - r <= 0 && (b.vx||0) < 0){ b.vx = -b.vx; b.bounces++; b.x = r; }
      if (b.x + r >= canvas.width  && (b.vx||0) > 0){ b.vx = -b.vx; b.bounces++; b.x = canvas.width - r; }
      if (b.y - r <= 0 && (b.vy||0) < 0){ b.vy = -b.vy; b.bounces++; b.y = r; }
      if (b.y + r >= canvas.height && (b.vy||0) > 0){ b.vy = -b.vy; b.bounces++; b.y = canvas.height - r; }
      if ((b.bounces||0) >= (b.maxBounces||5)) return false;
    }

    // ── 当たり判定 ─────────────────────────────────
    let hit = false;

    if (b.type === 'laser'){
      // 矩形 vs 矩形（レーザーは太い長方形）
      const rx = b.x, ry = b.y, rw = b.w||0, rh = b.h||0;
      hit = !(rx + rw < p.x || rx > p.x + p.width || ry + rh < p.y || ry > p.y + p.height);
    } else {
      // 円 vs 矩形（既存：shadow, wave, rainbow, ring）
      const r = b.r || 8;
      const nx = Math.max(p.x, Math.min(b.x, p.x + p.width));
      const ny = Math.max(p.y, Math.min(b.y, p.y + p.height));
      hit = Math.hypot(b.x - nx, b.y - ny) <= r;
    }

    if (hit){
  if (b.type === 'rainbow'){
    // ← ダメージ0：反転効果だけ
    gameState.controlsInverted = true;
    gameState.invertUntil = now + RAINBOW_INVERT_MS;
    gameState.messages.push(new FloatingMessage(
      p.x + p.width/2, p.y - 24, "CONFUSED!", "#88f"
    ));
    // ※見た目用の爆発は入れない（誤解を避ける）
  } else {
    // 通常弾・紫レーザーなどは従来通りダメージ
    gameState.explosions.push(new Explosion(p.x + p.width/2, p.y - 20));
    gameState.life--;
    updateUI?.();
  }
  return false; // 当たった弾は消滅
}

    // 画面外で破棄（shadowはバウンド、rainbowは“当たるまで消えない”）
    if (b.type !== 'shadow' && b.type !== 'rainbow'){
      const m = 60;
      if (b.x < -m || b.x > canvas.width + m || b.y < -m || b.y > canvas.height + m) return false;
    }

    return true;
  });

  // ── 描画 ────────────────────────────────────────
  gameState.bossBeams.forEach(b => {
    // レーザー（巨大な紫の直線）
    if (b.type === 'laser'){
      const x = b.x, y = b.y, w = b.w||0, h = b.h||0;

      // 外側グロー
      ctx.save();
      ctx.shadowColor = 'rgba(160, 60, 255, 0.95)';
      ctx.shadowBlur  = 24;

      // 本体（紫グラデ：上下で変化）
      const g = ctx.createLinearGradient(x, y, x, y + h);
      g.addColorStop(0.00, 'rgba(210,170,255,0.95)');
      g.addColorStop(0.25, 'rgba(168, 80,255,0.98)');
      g.addColorStop(0.50, 'rgba(255,255,255,0.98)'); // 白いコア
      g.addColorStop(0.75, 'rgba(168, 80,255,0.98)');
      g.addColorStop(1.00, 'rgba(110, 40,200,0.95)');
      ctx.fillStyle = g;
      ctx.fillRect(x, y, w, h);

      // さらに細い芯（白）
      const coreW = Math.max(4, w * 0.35);
      const cx = x + (w - coreW)/2;
      ctx.fillStyle = 'rgba(255,255,255,0.9)';
      ctx.fillRect(cx, y + 3, coreW, Math.max(0, h - 6));

      ctx.restore();
      return; // ← レーザーはここで終了（下の円弾描画はスキップ）
    }

    // 円弾（shadow, wave, rainbow, ring）
    const r = b.r || 8;
    let g;
    if (b.type === 'shadow'){
      g = ctx.createRadialGradient(b.x, b.y, 0, b.x, b.y, r);
      g.addColorStop(0,'#555'); g.addColorStop(1,'#000');
    } else if (b.type === 'wave'){
      g = ctx.createRadialGradient(b.x, b.y, 0, b.x, b.y, r);
      g.addColorStop(0,'#e6f6ff'); g.addColorStop(0.6,'#4ec3ff'); g.addColorStop(1,'#0066cc');
    } else if (b.type === 'rainbow'){
      const hue = ((performance.now()/20 + (b.hue0||0)) % 360)|0;
      g = ctx.createRadialGradient(b.x, b.y, 0, b.x, b.y, r);
      g.addColorStop(0,  `hsla(${hue},100%,95%,1)`);
      g.addColorStop(0.5,`hsla(${hue},100%,60%,1)`);
      g.addColorStop(1,  `hsla(${(hue+60)%360},100%,45%,1)`);
    } else { // ring360 など
      g = ctx.createRadialGradient(b.x, b.y, 0, b.x, b.y, r);
      g.addColorStop(0,'#fff5e6'); g.addColorStop(0.6,'#ffb347'); g.addColorStop(1,'#ff7f27');
    }
    ctx.save();
    ctx.shadowColor = 'rgba(255,255,255,0.35)';
    ctx.shadowBlur = 8;
    ctx.fillStyle = g;
    ctx.beginPath();
    ctx.arc(b.x, b.y, r, 0, Math.PI*2);
    ctx.fill();
    ctx.restore();
  });
}


function updatePlayer(){
  const p = gameState.player;
  const moveSpeed = 12 * SPEED_MULT;
  const inv = isControlsInverted();

  const left  = inv ? gameState.keys['ArrowRight'] : gameState.keys['ArrowLeft'];
  const right = inv ? gameState.keys['ArrowLeft']  : gameState.keys['ArrowRight'];
  const up    = inv ? gameState.keys['ArrowDown']  : gameState.keys['ArrowUp'];
  const down  = inv ? gameState.keys['ArrowUp']    : gameState.keys['ArrowDown'];

  if (left  && p.x > 0) p.x -= moveSpeed * dt;
  if (right && p.x < canvas.width - p.width) p.x += moveSpeed * dt;
  if (up    && p.y > 0) p.y -= moveSpeed * dt;
  if (down  && p.y < canvas.height - p.height - 100) p.y += moveSpeed * dt;
}


        // ボスHPバー
        function drawBossHPBar() {
        if (!gameState.boss) return;

        const max = 10;
        const ratio = Math.max(0, gameState.boss.life) / max;

        const barW = 200, barH = 12;
        const x = canvas.width / 2 - barW / 2;
        const y = 10;

        // 枠（薄い黒背景＋白枠）
        ctx.fillStyle = 'rgba(0,0,0,0.5)';
        ctx.fillRect(x - 2, y - 2, barW + 4, barH + 4);

        // 背景＆残量
        ctx.fillStyle = '#550000';
        ctx.fillRect(x, y, barW, barH);
        ctx.fillStyle = '#ff3333';
        ctx.fillRect(x, y, barW * ratio, barH);

        ctx.strokeStyle = '#ffffff';
        ctx.strokeRect(x - 2, y - 2, barW + 4, barH + 4);

        // ← ここを変更：ラベルを右横＆縦中央に
        const label = 'BOSS';
        ctx.font = 'bold 12px Arial';
        ctx.textAlign = 'left';
        ctx.textBaseline = 'middle';
        const tx = x + barW + 8;      // バーの右端から8px右
        const ty = y + barH / 2;      // バーの縦中央

        // 縁取りで視認性UP（任意）
        ctx.lineWidth = 3;
        ctx.strokeStyle = 'rgba(0,0,0,0.5)';
        ctx.strokeText(label, tx, ty);

        ctx.fillStyle = '#fff';
        ctx.fillText(label, tx, ty);
        }


        // （D）WARNINGオーバーレイ
        function drawWarningOverlay() {
        if (!gameState.bossWarningActive || gameState.bossFinaleActive || gameState.bossCleared) return;

        const t = performance.now() / 1000;
        const blink = (Math.sin(t * 6) + 1) / 2; // 点滅
        const alpha = 0.4 + 0.6 * blink;

        ctx.save();
        // うっすら暗く
        ctx.fillStyle = 'rgba(0,0,0,0.35)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // 大きな赤文字
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.font = 'bold 52px Arial';
        ctx.fillStyle = `rgba(255, 0, 0, ${alpha})`;
        ctx.strokeStyle = `rgba(0,0,0, ${Math.min(0.8, alpha)})`;
        ctx.lineWidth = 6;
        ctx.strokeText('WARNING', canvas.width / 2, canvas.height / 2);
        ctx.fillText('WARNING', canvas.width / 2, canvas.height / 2);
        ctx.restore();
        }

 
    // 衝突判定
    function checkCollisions() {
    try {
        if (!gameState.missiles) return;

        for (let m = gameState.missiles.length - 1; m >= 0; m--) {
        const missile = gameState.missiles[m];
        if (!missile) continue;
        let handled = false;

        // 1) 通常敵
        for (let e = gameState.enemies.length - 1; e >= 0; e--) {
            const enemy = gameState.enemies[e];
            if (!enemy) continue;

            if (missile.x < enemy.x + enemy.width &&
                missile.x + missile.width > enemy.x &&
                missile.y < enemy.y + enemy.height &&
                missile.y + missile.height > enemy.y) {

            gameState.explosions.push(new Explosion(enemy.x + enemy.width/2, enemy.y + enemy.height/2));

            if (missile.number === enemy.vocab.correct) {
                gameState.score += 100;
                gameState.messages.push(new FloatingMessage(enemy.x + enemy.width/2, enemy.y - 10, "OK", "#00aaff"));
            } else {
                gameState.life--;
                gameState.messages.push(new FloatingMessage(enemy.x + enemy.width/2, enemy.y - 10, "MISS", "#ff0000"));
            }

            gameState.missiles.splice(m, 1);
            gameState.enemies.splice(e, 1);
            updateUI();
            handled = true;
            break;
            }
        }
        if (handled) continue;

        // 2) ボス
        if (gameState.boss) {
        const b = gameState.boss;
        if (missile.x < b.x + b.width &&
            missile.x + missile.width > b.x &&
            missile.y < b.y + b.height &&
            missile.y + missile.height > b.y) {

            gameState.explosions.push(new Explosion(missile.x + missile.width/2, missile.y));

            if (missile.number === b.vocab.correct) {
            b.life--;
            gameState.score += 200;
            gameState.messages.push(new FloatingMessage(b.x + b.width/2, b.y - 10, "OK", "#00aaff"));

            if (b.life <= 0) {
            startBossFinale(b);              // ← 3秒大爆発へ
            } else {
            b.nextWord();
            }
            } else {
            // ミスはプレイヤーにダメージ
            gameState.life--;
            gameState.messages.push(new FloatingMessage(b.x + b.width/2, b.y - 10, "MISS", "#ff0000"));
            }

            gameState.missiles.splice(m, 1);
            updateUI();
            continue;
        }
        }
        }
    } catch (err) {
        console.error('衝突判定エラー:', err.message);
    }
    }

    // ボス大爆発開始
    function startBossFinale(boss) {
    const cx = boss.x + boss.width / 2;
    const cy = boss.y + boss.height / 2;

    gameState.bossFinaleActive = true;
    gameState.bossFinaleStart  = performance.now();
    gameState.bossFinalePos    = { x: cx, y: cy };

    // ボスは退場、弾も止める
    gameState.boss = null;
    gameState.bossBeams.length = 0;
    // WARNING 系は完全リセット
    gameState.bossWarningActive = false;
    gameState.bossPending = false;

    // 初期の爆発を数発
    for (let i = 0; i < 20; i++) {
        const ang = Math.random() * Math.PI * 2;
        const r   = 10 + Math.random() * 40;
        gameState.explosions.push(new Explosion(cx + Math.cos(ang)*r, cy + Math.sin(ang)*r));
    }
    }
    // ボス大爆発更新・描画
    function updateAndDrawBossFinale() {
    if (!gameState.bossFinaleActive) return;

    const now     = performance.now();
    const elapsed = now - gameState.bossFinaleStart;
    const dur     = 3000; // 3秒
    const { x, y } = gameState.bossFinalePos;

    // 継続的に爆発パーティクルを追加
    const burstsPerFrame = 4;
    for (let i = 0; i < burstsPerFrame; i++) {
        const ang = Math.random() * Math.PI * 2;
        const radius = 20 + Math.random() * (160 * Math.min(1, elapsed / dur));
        gameState.explosions.push(new Explosion(x + Math.cos(ang)*radius, y + Math.sin(ang)*radius));
    }

    // 中心の巨大な発光
    const coreR = 40 + 160 * Math.min(1, elapsed / dur);
    ctx.save();
    ctx.globalCompositeOperation = 'lighter';
    const g = ctx.createRadialGradient(x, y, 0, x, y, coreR);
    g.addColorStop(0.0, 'rgba(255,255,255,0.95)');
    g.addColorStop(0.3, 'rgba(255,210,80,0.9)');
    g.addColorStop(0.6, 'rgba(255,90,0,0.75)');
    g.addColorStop(1.0, 'rgba(0,0,0,0)');
    ctx.fillStyle = g;
    ctx.beginPath();
    ctx.arc(x, y, coreR, 0, Math.PI * 2);
    ctx.fill();

    // 画面フラッシュ
    const flash = Math.max(0, 1 - elapsed / dur);
    ctx.fillStyle = `rgba(255,255,255,${0.25 * flash})`;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.restore();

    // 終了してゲームクリアへ
    if (elapsed >= dur) {
        gameState.bossFinaleActive = false;
        gameState.bossCleared = true;     // 以後ボス再出現させない
        triggerGameClear();               // モーダル表示（下#6参照）
    }
    }


    // ゲームクリア演出
    function triggerGameClear() {
    // ボス関連のクリーンアップ（既存のまま）
    gameState.boss = null;
    gameState.bossBeams.length = 0;
    gameState.bossWarningActive = false;
    gameState.bossPending = false;
    gameState.bossCleared = true;

    // スコア表示＆ゲーム停止
    document.getElementById('finalScoreClear').textContent = gameState.score;
    gameState.gameRunning = false;
    // 固定ボードを表示
    const board = document.getElementById('gameClearBoard');
    if (board) board.style.display = 'block';
    }


        
    // プレイヤーと敵の衝突判定
    function checkPlayerEnemyCollisions() {
        try {
            if (!gameState.enemies || !gameState.player) return;
            
            for (let eIndex = gameState.enemies.length - 1; eIndex >= 0; eIndex--) {
                const enemy = gameState.enemies[eIndex];
                if (!enemy) continue;
                
                if (gameState.player.x < enemy.x + enemy.width &&
                    gameState.player.x + gameState.player.width > enemy.x &&
                    gameState.player.y < enemy.y + enemy.height &&
                    gameState.player.y + gameState.player.height > enemy.y) {
                    
                    // プレイヤーの前方（上側）に爆発エフェクト
                    gameState.explosions.push(new Explosion(
                        gameState.player.x + gameState.player.width / 2,
                        gameState.player.y - 20  // プレイヤーの上20ピクセル前方
                    ));
                    
                    // 敵にも爆発エフェクト
                    gameState.explosions.push(new Explosion(
                        enemy.x + enemy.width / 2,
                        enemy.y + enemy.height / 2
                    ));
                    
                    // 敵を消滅させる
                    gameState.enemies.splice(eIndex, 1);
                    
                    // プレイヤーのライフを1減らす
                    gameState.life--;
                    updateUI();
                    
                    // 1回の衝突のみ処理
                    break;
                }
            }
        } catch (error) {
            console.error('プレイヤー衝突判定エラー:', error.message);
        }
    }
    
   
    // ライフ表示
    function renderLife(n){
        const el = document.getElementById('lifeDisplay');
        if (!el) return;
        el.textContent = '🧡'.repeat(Math.max(0, n));
        el.setAttribute('aria-label', `ライフ ${n}`);
        }
    // スコア表示
    function updateUI(){
        renderLife(gameState.life);
        const sc = document.getElementById('scoreCount');
        if (sc) sc.textContent = gameState.score;
        }

    
    // 敵生成
    function spawnEnemy() {
    if (gameState.boss || gameState.bossWarningActive || gameState.bossFinaleActive) return;
    try {
        if (gameState.boss || gameState.bossWarningActive) return; // ← 追加ポイント

        if (Math.random() < 0.015 * SPAWN_RATE && gameState.enemies.length < 4) {
        const newEnemy = new Enemy();
        gameState.enemies.push(newEnemy);
        }
    } catch (error) {
        console.error('敵生成エラー:', error.message);
    }
    }
    
    // ゲームループ
    function gameLoop(now = performance.now()) {
  try {
    if (!gameState.gameRunning) return;

    // 60fps基準のdt（上限を3にして暴走防止）
    dt = Math.min(3, (now - lastTime) / (1000 / 60));
    lastTime = now;

    gameState.animationTime += dt;
    if (gameState.life <= 0) { gameOver(); return; }

    ctx.clearRect(0, 0, canvas.width, canvas.height);


    // 敵（生成は時間依存に・後述の spawnEnemy を修正）
    spawnEnemy();
    gameState.enemies = gameState.enemies.filter(enemy => {
      if (enemy && enemy.draw && enemy.update) {
        enemy.draw();
        return enemy.update(); // ← 各update内でdtを使うよう修正済み
      }
      return false;
    });

    // ボス
    spawnBossIfReady();
    if (gameState.boss && !gameState.bossFinaleActive) {
      const nowPerf = performance.now();
      gameState.boss.update(nowPerf); // ← update内でdtを掛ける
      gameState.boss.draw();
    }

    // ミサイル
    gameState.missiles = gameState.missiles.filter(m => {
      if (m && m.draw && m.update) { m.draw(); return m.update(); }
      return false;
    });

    updateEnemyBeams(); // ← 中でdtを掛ける
    updateBossBeams();  // ← 中でdtを掛ける
    checkCollisions();
    checkPlayerEnemyCollisions();

    // 爆発・メッセージ
    gameState.explosions = gameState.explosions.filter(ex => ex && ex.draw && ex.update && (ex.draw(), ex.update()));
    updateAndDrawBossFinale();
    gameState.messages   = gameState.messages.filter(msg => msg && msg.draw && msg.update && (msg.draw(), msg.update()));

    // プレイヤー
    updatePlayer(); // ← 中でdtを掛ける
    drawPlayer();

    drawBossHPBar();
    drawWarningOverlay();

    requestAnimationFrame(gameLoop);
  } catch (e) {
    console.error(e);
    gameState.gameRunning = false;
  }
}



    
    // ゲームオーバー
    function gameOver() {
        gameState.gameRunning = false;
        document.getElementById('finalScore').textContent = gameState.score;
        document.getElementById('gameOver').style.display = 'block';
    }
    
    // ゲーム再開
    function restartGame() {
        // ① まず全てのオーバーレイ/ボードを閉じる（存在すれば）
        ['gameOver', 'gameClear', 'gameClearBoard', 'gameClearBanner'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
        // ② 状態を初期化（bossCleared は1回だけ定義）
        gameState = {
            life: 10,
            score: 0,
            gameRunning: true,
            enemies: [],
            missiles: [],
            enemyBeams: [],
            explosions: [],
            messages: [],
            stars: [],
            animationTime: 0,
            player: {
              x: (canvas.width  - PLAYER_W) / 2,
              y: (canvas.height - PLAYER_H) / 2,
              width: PLAYER_W,
              height: PLAYER_H
            },
            keys: {},
            // ボス関連
            boss: null,
            bossBeams: [],
            bossWarningActive: false,
            bossWarningStart: 0,
            bossPending: false,
            bossTriggerScore: 100,
            bossCleared: false,       // ← 重複を削除
            bossFinaleActive: false,
            bossFinaleStart: 0,
            bossFinalePos: { x: 0, y: 0 }
        };

        // ③ 山札などをリセット（使っている場合）
        if (typeof refillVocabDeck === 'function') refillVocabDeck(); // 通常敵
        if (typeof refillBossDeck  === 'function') refillBossDeck();  // ボス
        if (typeof currentVocabIndex !== 'undefined') currentVocabIndex = 0;
        if (typeof bossVocabIndex    !== 'undefined') bossVocabIndex = 0;

        // ④ 背景とUIの初期化
        initStars();
        updateUI();

        // ⑤ ループ開始（1回だけ呼べばOK）
        gameLoop();
        }

    
    // === キーボード入力（PC）===
const ARROWS = ['ArrowUp','ArrowDown','ArrowLeft','ArrowRight'];

document.addEventListener('keydown', (e) => {
  const k = e.key;

  // 矢印：押下中ON
  if (ARROWS.includes(k)) {
    e.preventDefault();
    gameState.keys[k] = true;
    return;
  }

  // 1〜4：発射
  if (k === '1' || k === '2' || k === '3' || k === '4') {
    e.preventDefault();
    const n = mapAnswerNumber(parseInt(k, 10));
    gameState.missiles.push(new Missile(
      gameState.player.x + gameState.player.width / 2 - 10,
      gameState.player.y - 30,
      n
    ));
  }
});

// 離したらOFF
window.addEventListener('keyup', (e) => {
  const k = e.key;
  if (ARROWS.includes(k)) {
    e.preventDefault();
    gameState.keys[k] = false;
  }
});

// タブ外へ行ったらクリア
window.addEventListener('blur', () => {
  ARROWS.forEach(k => (gameState.keys[k] = false));
});


    

    // === 2) 方向パッド（右）: 押下中は keys をON、離したらOFF ===
    document.querySelectorAll('#moveControls .arrow-btn').forEach(btn => {
        const key = btn.dataset.key;
        const set = (v) => {
        gameState.keys[key] = v;
        btn.dataset.active = v ? '1' : '0';
        };

        // タッチ（スマホ）
        btn.addEventListener('touchstart', (e) => { e.preventDefault(); set(true); }, { passive: false });
        btn.addEventListener('touchend',   (e) => { e.preventDefault(); set(false); }, { passive: false });
        btn.addEventListener('touchcancel',(e) => { e.preventDefault(); set(false); }, { passive: false });

        // マウス（PCでも試せるように）
        btn.addEventListener('mousedown', () => set(true));
        window.addEventListener('mouseup', () => set(false));
    });
    // === 1〜4ボタン：ミサイル発射（CLICK + TOUCH）===
const answerButtons = document.querySelectorAll(
  '.answers-inline .answer-btn, .touch-controls-left .answer-btn, .answer-btn'
);

function fireFromButton(num){
  if (!gameState.gameRunning) return;
  const n = mapAnswerNumber(num);
  gameState.missiles.push(new Missile(
    gameState.player.x + gameState.player.width / 2 - 10,
    gameState.player.y - 30,
    n
  ));
}


answerButtons.forEach(btn => {
  const n = parseInt(btn.dataset.answer, 10);
  // クリック
  btn.addEventListener('click', () => fireFromButton(n));
  // タッチ（300ms遅延防止・ダブル発火防止）
  btn.addEventListener('touchstart', (e) => {
    e.preventDefault();
    fireFromButton(n);
  }, { passive: false });
});

        
    // ゲーム開始
    initStars();
    updateUI();
    gameLoop();

(function(){
  const container = document.querySelector('.game-container');

  // ゲームの論理解像度（変えると当たり判定やレイアウトも変わる）
  const BASE_W = 400, BASE_H = 800;

  // PC と モバイル で倍率に上限を設ける（お好みで調整）
  const DESKTOP_USER_SCALE = 0.5; // PC では半分表示
  const MOBILE_MAX_SCALE   = 1.0; // モバイルは“等倍まで”（超拡大しない）

  // モバイル判定（UAではなく入力特性で）
  const isMobile = () =>
    window.matchMedia('(hover: none) and (pointer: coarse)').matches;

  // 実際の見えているビューポートを取得（iOS のアドレスバー縮みも追従）
  function getViewport(){
    const vv = window.visualViewport;
    if (vv) return { w: vv.width, h: vv.height };
    return { w: window.innerWidth, h: window.innerHeight };
  }

  // “contain（収まる）” 方式でフィット。必要なら “cover（満たす/一部はみ出し）” も可能。
  const FIT_MODE = 'contain'; // 'contain' or 'cover'

  function layout(){
    const { w, h } = getViewport();

    const fitContain = Math.min( w / BASE_W, h / BASE_H );
    const fitCover   = Math.max( w / BASE_W, h / BASE_H );
    const fitScale   = (FIT_MODE === 'cover') ? fitCover : fitContain;

    // PC：大画面で巨大化しすぎないよう 1 を上限にし、さらに任意倍率を掛ける
    // モバイル：画面いっぱい（等倍まで）にフィット
    const base = isMobile() ? fitScale : Math.min(1, fitScale);
    const user = isMobile() ? 1.0 : DESKTOP_USER_SCALE;
    const s = Math.min(base * user, MOBILE_MAX_SCALE);

    // 余白ぶんだけ平行移動して中央配置（サブピクセルぼけ回避のため丸め）
    const tx = Math.round((w - BASE_W * s) / 2);
    const ty = Math.round((h - BASE_H * s) / 2);

    container.style.transform = `translate(${tx}px, ${ty}px) scale(${s})`;
  }

  // 画面サイズ変化や iOS のアドレスバー開閉に追従
  window.addEventListener('resize', layout, { passive: true });
  window.addEventListener('orientationchange', layout, { passive: true });
  if (window.visualViewport) {
    visualViewport.addEventListener('resize', layout, { passive: true });
    visualViewport.addEventListener('scroll', layout, { passive: true });
  }

  // 初期実行
  layout();
})();
</script>



</body>
</html>

