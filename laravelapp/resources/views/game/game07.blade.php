<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>English Learning Shooting Game</title>
    <style>
       /* ===== Base ===== */
        body {
        margin: 0;
        padding: 0;
        background: #000;
        color: #fff;
        font-family: 'Arial', sans-serif;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        }

        /* キャンバスの入れ物（サイズ固定） */
        .game-container{
        position: relative;
        width: 400px;
        height: 800px;
        }

        /* キャンバスは全面に敷く */
        #gameCanvas{
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, #001133 0%, #003366 100%);
        border: none;
        touch-action: none;
        }

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
</body>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        
        // ゲーム状態
        let gameState = {
            life: 3,
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
                x: canvas.width / 2 - 25,
                y: canvas.height - 120,
                width: 50,
                height: 40
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
        const hardVocabularyData = [
            { word:"frog",     options:["かえる🐸","さる🐵","きつね🦊","ねずみ🐭"],                 correct:1 },
            { word:"panda",    options:["らいおん🦁","ぱんだ🐼","くま🐻","ねこ🐱"],                   correct:2 },
            { word:"monkey",   options:["いぬ🐶","とり🐦","ねこ🐱","さる🐵"],                         correct:4 },
            { word:"fox",      options:["ねずみ🐭","ぶた🐷","くま🐻","きつね🦊"],                     correct:4 },
            { word:"koala",    options:["こあら🐨","くじら🐋","うし🐮","うま🐴"],                     correct:1 },
            { word:"whale",    options:["いるか🐬","ぺんぎん🐧","くじら🐋","さかな🐟"],               correct:3 },
            { word:"dolphin",  options:["いるか🐬","かに🦀","たこ🐙","えび🦐"],                       correct:1 },
            { word:"penguin",  options:["にわとり🐔","あひる🦆","ぺんぎん🐧","とり🐦"],               correct:3 },
            { word:"giraffe",  options:["しまうま🦓","きりん🦒","うさぎ🐰","らくだ🐫"],               correct:2 },
            { word:"zebra",    options:["きりん🦒","やぎ🐐","しまうま🦓","ひつじ🐑"],                 correct:3 },

            { word:"peach",    options:["めろん🍈","りんご🍎","ばなな🍌","もも🍑"],                   correct:4 },
            { word:"melon",    options:["いちご🍓","めろん🍈","ぶどう🍇","みかん🍊"],                 correct:2 },
            { word:"carrot",   options:["じゃがいも🥔","にんじん🥕","たまねぎ🧅","とまと🍅"],         correct:2 },
            { word:"potato",   options:["じゃがいも🥔","にんじん🥕","なすび🍆","きゃべつ🥬"],     correct:1 },
            { word:"tomato",   options:["とまと🍅","きゅうり🥒","なす🍆","とうもろこし🌽"],           correct:1 },
            { word:"cherry",   options:["さくらんぼ🍒","ぶどう🍇","もも🍑","りんご🍎"],               correct:1 },
            { word:"rice",     options:["ぱん🍞","めん🍜","ぷりん🍮","ごはん🍚"],                     correct:4 },
            { word:"cookie",   options:["あめ🍬","くっきー🍪","けーき🍰","あいす🍨"],                 correct:2 },
            { word:"candy",    options:["くっきー🍪","けーき🍰","あめ🍬","あいす🍨"],                 correct:3 },
            { word:"juice",    options:["みず💧","じゅーす🧃","ぎゅうにゅう🥛","おちゃ🍵"],           correct:2 },

            { word:"balloon",  options:["たこ🪁","しゃぼんだま🫧","ぼうし🎩","ふうせん🎈"],           correct:4 },
            { word:"kite",     options:["ふうせん🎈","しゃぼんだま🫧","ぼうし🎩","たこ🪁"],           correct:4 },
            { word:"robot",    options:["ぬいぐるみ🧸","でんしゃ🚆","ろぼっと🤖","くるま🚗"],         correct:3 },
            { word:"bus",      options:["じてんしゃ🚲","くるま🚗","ふね⛵️","ばす🚌"],                 correct:4 },
            { word:"boat",     options:["ひこうき✈️","ふね⛵️","でんしゃ🚆","ばす🚌"],                 correct:2 },
            { word:"plane",    options:["ふね⛵️","ばす🚌","ひこうき✈️","でんしゃ🚆"],                 correct:3 },
            { word:"star",     options:["つき🌕","たいよう☀️","ほし⭐️","にじ🌈"],                     correct:3 },
            { word:"tree",     options:["はな🌸","くさ🌿","はっぱ🍃","き🌲"],                           correct:4 },
            { word:"rainbow",  options:["にじ🌈","くも☁️","あめ☔️","ゆき❄️"],                         correct:1 },
            { word:"ice cream",options:["けーき🍰","くっきー🍪","あいすくりーむ🍨","あめ🍬"],           correct:3 }
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
        let bossDeck = [];
        function refillBossDeck() {
        bossDeck = Array.from({ length: hardVocabularyData.length }, (_, i) => i);
        // フィッシャー–イェーツでシャッフル
        for (let i = bossDeck.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [bossDeck[i], bossDeck[j]] = [bossDeck[j], bossDeck[i]];
        }
        }
        function getRandomBossVocab() {
        if (bossDeck.length === 0) refillBossDeck();
        const idx = bossDeck.pop();
        return hardVocabularyData[idx];
        }
        // 初期化
        refillBossDeck();
        // 星空描画
        function drawStars() {
            ctx.fillStyle = '#ffffff';
            gameState.stars.forEach(star => {
                ctx.fillRect(star.x, star.y, star.size, star.size);
            });
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

        // 敵クラス
      // ==== 修正版 Enemy クラス（丸ピンク＋大きな目＋激しい羽） ====
class Enemy {
  constructor() {
    // 先にサイズ・基本プロパティを確定させる
    this.width  = 56;
    this.height = 56;
    this.speed  = 1 + Math.random();
    this.vocab = getRandomVocab();
    this.lastBeamTime  = 0;
    this.beamInterval  = 2000 + Math.random() * 2000;
    this.phase = Math.random() * Math.PI * 2; // 個体差
    // その後に位置を決定
    this.x = this.findValidPosition();
    this.y = -this.height;
    currentVocabIndex++;
  }

  findValidPosition() {
  // Enemy.draw() のカードと同じサイズ・位置計算に合わせる
  const CARD_W = 120, CARD_H = 84, CARD_OFFSET = 16;
  const cardRectAt = (x, futureY, w, h) => ({
    x: x + w/2 - CARD_W/2,
    y: futureY - (CARD_H + CARD_OFFSET),
    w: CARD_W, h: CARD_H
  });
  const overlap = (a, b) =>
    a.x < b.x + b.w && a.x + a.w > b.x && a.y < b.y + b.h && a.y + a.h > b.y;

  const attemptsMax = 40;
  const margin = 8;

  for (let attempt = 0; attempt < attemptsMax; attempt++) {
    const x = Math.random() * (canvas.width - this.width);
    const mine = cardRectAt(x, /*spawnY*/ -60, this.width, this.height);

    let ok = true;
    for (const other of gameState.enemies) {
      if (!other) continue;
      const theirs = cardRectAt(other.x, other.y, other.width, other.height);
      const a = { x: mine.x - margin,   y: mine.y - margin,   w: mine.w + margin*2,   h: mine.h + margin*2 };
      const b = { x: theirs.x - margin, y: theirs.y - margin, w: theirs.w + margin*2, h: theirs.h + margin*2 };
      if (overlap(a, b)) { ok = false; break; }
    }
    if (ok) return x;
  }
  // 最後は妥協
  return Math.random() * (canvas.width - this.width);
}


update() {
  // Enemy.draw() のカードと同値にすること（ズレると判定と表示が合わない）
  const CARD_W = 120, CARD_H = 84, CARD_OFFSET = 16, margin = 6;

  const cardRectAt = (x, futureY, w, h) => ({
    x: x + w/2 - CARD_W/2,
    y: futureY - (CARD_H + CARD_OFFSET),
    w: CARD_W, h: CARD_H
  });
  const overlap = (a, b) =>
    a.x < b.x + b.w && a.x + a.w > b.x && a.y < b.y + b.h && a.y + a.h > b.y;

  // ---- 落下（前方の敵カードに詰めすぎない）----
  let nextY = this.y + this.speed;
  const nextRect = cardRectAt(this.x, nextY, this.width, this.height);

  if (Array.isArray(gameState.enemies)) {
    for (const other of gameState.enemies) {
      if (!other || other === this) continue;
      if (other.y >= this.y) {
        const oRect = cardRectAt(other.x, other.y, other.width, other.height);
        const a = { x: nextRect.x - margin, y: nextRect.y - margin, w: nextRect.w + margin*2, h: nextRect.h + margin*2 };
        const b = { x: oRect.x  - margin,  y: oRect.y  - margin,  w: oRect.w  + margin*2,  h: oRect.h  + margin*2 };
        if (overlap(a, b)) {
          const maxNextY = oRect.y - margin + CARD_OFFSET;
          nextY = Math.min(nextY, maxNextY);
        }
      }
    }
  }
  this.y = nextY;

  // ---- ビーム発射タイマー ----
  const now = Date.now();
  if (!Number.isFinite(this.lastBeamTime)) this.lastBeamTime = 0;
  if (!Number.isFinite(this.beamInterval)) this.beamInterval = 2000 + Math.random() * 2000;

  if (now - this.lastBeamTime >= this.beamInterval) {
    if (typeof this.fireBeam === 'function') {
      this.fireBeam();
    } else {
      // 念のため：fireBeam が無い環境でも動かす最小実装
      gameState.enemyBeams.push({
        x: this.x + this.width / 2 - 2,
        y: this.y + this.height,
        width: 5, height: 15, speed: 3
      });
    }
    this.lastBeamTime = now;
    // 次の間隔を軽くランダム化（固定で良ければこの行は削除）
    this.beamInterval = 1500 + Math.random() * 1500;
  }

  // 画面外で消滅
  return this.y < canvas.height + 100;
}


  draw() {
    const t  = gameState.animationTime;
    const cx = this.x + this.width  / 2;
    const cy = this.y + this.height / 2;
    if (!Number.isFinite(cx) || !Number.isFinite(cy)) return; // 念のため

    // 羽ばたき
    const flap = Math.sin(t * 0.45 + this.phase);
    const wingScale = 1 + 0.6 * Math.abs(flap);
    const wingAngle = flap * 0.6;

    // 翼
    const drawWing = (side = -1) => {
      ctx.save();
      ctx.translate(cx + side * (this.width * 0.42), cy - 2);
      ctx.rotate(side * (0.5 + wingAngle));
      ctx.scale(wingScale, 1);
      ctx.fillStyle = '#ffd1e8';
      ctx.strokeStyle = '#ff9ad1';
      ctx.lineWidth = 2;
      ctx.beginPath();
      ctx.ellipse(0, 0, 22, 14, 0, 0, Math.PI * 2);
      ctx.fill();
      ctx.stroke();
      ctx.globalAlpha = 0.5;
      ctx.strokeStyle = '#ffc0dd';
      ctx.beginPath(); ctx.moveTo(-12, -6); ctx.lineTo(12, -6); ctx.stroke();
      ctx.beginPath(); ctx.moveTo(-14, 0);  ctx.lineTo(14, 0);  ctx.stroke();
      ctx.beginPath(); ctx.moveTo(-12, 6);  ctx.lineTo(12, 6);  ctx.stroke();
      ctx.globalAlpha = 1;
      ctx.restore();
    };
    drawWing(-1);
    drawWing(+1);

    // 本体（丸いピンク：ラジアルグラデ）
    const grad = ctx.createRadialGradient(cx - 6, cy - 6, 6, cx, cy, this.width * 0.5);
    grad.addColorStop(0.0, '#ffe2f1');
    grad.addColorStop(0.4, '#ffb6dc');
    grad.addColorStop(1.0, '#ff74c4');
    ctx.fillStyle = grad;
    ctx.beginPath();
    ctx.ellipse(cx, cy, this.width * 0.45, this.height * 0.45, 0, 0, Math.PI * 2);
    ctx.fill();

    // 目（追従＋瞬き）
    const px = gameState.player.x + gameState.player.width / 2;
    const py = gameState.player.y + gameState.player.height/ 2;
    let dx = px - cx, dy = py - cy;
    const d = Math.max(1, Math.hypot(dx, dy));
    dx /= d; dy /= d;
    const pupilMax = 6;
    const jitter   = Math.sin(t * 0.3 + this.phase) * 0.6;
    const offX = dx * pupilMax + jitter;
    const offY = dy * pupilMax * 0.7;
    const eyeGap = 14, eyeR = 12, pupilR = 6;
    const blink = 0.88 + 0.12 * Math.abs(Math.sin(t * 0.2 + this.phase * 1.7));

    ctx.fillStyle = '#ffffff';
    ctx.beginPath(); ctx.ellipse(cx - eyeGap, cy - 4, eyeR, eyeR * blink, 0, 0, Math.PI * 2); ctx.fill();
    ctx.beginPath(); ctx.ellipse(cx + eyeGap, cy - 4, eyeR, eyeR * blink, 0, 0, Math.PI * 2); ctx.fill();

    ctx.fillStyle = '#111';
    ctx.beginPath(); ctx.ellipse(cx - eyeGap + offX, cy - 4 + offY, pupilR, pupilR * blink, 0, 0, Math.PI * 2); ctx.fill();
    ctx.beginPath(); ctx.ellipse(cx + eyeGap + offX, cy - 4 + offY, pupilR, pupilR * blink, 0, 0, Math.PI * 2); ctx.fill();

    ctx.fillStyle = '#fff';
    ctx.beginPath(); ctx.arc(cx - eyeGap + offX - 2, cy - 6 + offY, 2, 0, Math.PI * 2); ctx.fill();
    ctx.beginPath(); ctx.arc(cx + eyeGap + offX - 2, cy - 6 + offY, 2, 0, Math.PI * 2); ctx.fill();

    // 単語カード（位置微調整）
    // 単語カード（中央揃え＆太字）
    // ===== 単語カード：サイズUP＋丸角（中央揃え・太字） =====
{
  // 敵の中心にカードを合わせる
  const cx = this.x + this.width / 2;
  const cardW = 120;   // 横を少し拡大（以前: 80）
  const cardH = 84;    // 縦を少し拡大（以前: 60）
  const radius = 12;   // 丸み
  const left = cx - cardW / 2;
  const top  = this.y - (cardH + 16); // 敵の少し上に表示

  // 丸角パス
  const roundRectPath = (x, y, w, h, r) => {
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

  // 背景（薄い影つきで視認性UP・任意）
  ctx.save();

  ctx.fillStyle = 'rgba(255, 255, 255, 0.96)';
  roundRectPath(left, top, cardW, cardH, radius);
  ctx.fill();

  // 枠線をうすく
  ctx.shadowBlur = 0;
  ctx.lineWidth = 1;
  ctx.strokeStyle = 'rgba(0,0,0,0.08)';
  roundRectPath(left, top, cardW, cardH, radius);
  ctx.stroke();

  // 文字（中央揃え・太字）
  ctx.textAlign = 'center';
  ctx.fillStyle = '#000';

  // 単語（少し大きく）
  ctx.font = 'bold 15px Arial';
  // アウトラインで読みやすく（任意）
  ctx.strokeStyle = 'rgba(0,0,0,0.25)';
  ctx.lineWidth = 2;
  ctx.strokeText(this.vocab.word, cx, top + 22);
  ctx.fillText(this.vocab.word,   cx, top + 22);

  // 選択肢（太字＆中央）★絵文字付与＋はみ出し対策
ctx.font = 'bold 12px Arial';
for (let i = 0; i < 4; i++) {
  const opt   = this.vocab.options[i];           // 例: "あめ"
  const emoji = emojiMap[opt] || '';              // 例: "☔️"
  const label = `${i + 1}. ${opt}${emoji}`;       // "1. あめ☔️"
  const y = top + 42 + i * 13;

  // はみ出し防止（カード幅に収める）
  const maxW = cardW - 12;                        // パディング相当
  let textToDraw = label;
  while (ctx.measureText(textToDraw).width > maxW && textToDraw.length > 2) {
    textToDraw = textToDraw.slice(0, -2) + '…';
  }

  ctx.strokeText(textToDraw, cx, y);
  ctx.fillText(textToDraw,   cx, y);
}
}
}
}

function drawWordCard(vocab, centerX, top, cardW = 160, cardH = 110) {
  const radius = 12;
  const left = centerX - cardW / 2;

  // 角丸
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
  ctx.lineWidth = 1;
  ctx.strokeStyle = 'rgba(0,0,0,0.08)';
  rr(left, top, cardW, cardH, radius);
  ctx.stroke();

  // 文字（中央太字＋薄い縁取りで視認性UP）
  ctx.textAlign = 'center';
  ctx.fillStyle = '#000';
  ctx.strokeStyle = 'rgba(0,0,0,0.25)';
  ctx.lineWidth = 2;

  // 単語
  ctx.font = 'bold 15px Arial';
  ctx.strokeText(vocab.word, centerX, top + 24);
  ctx.fillText  (vocab.word, centerX, top + 24);

  // 選択肢
  ctx.font = 'bold 12px Arial';
  for (let i = 0; i < 4; i++) {
    const text = `${i + 1}. ${vocab.options[i]}`;
    const display = text.length > 16 ? text.slice(0, 16) + '…' : text;
    const y = top + 46 + i * 14;
    ctx.strokeText(display, centerX, y);
    ctx.fillText  (display, centerX, y);
  }
  ctx.restore();
}



    // ボス
    // ★★★ ここから Boss を全置換 ★★★
    class Boss {
    constructor() {
        this.width  = 140;       // 少し大きめの胴体
        this.height = 120;
        this.x = canvas.width / 2 - this.width / 2;
        this.y = 100;
        this.speed = 4;
        this.life = 10;

        // 移動関連
        this.lastMoveChange = 0;
        this.moveTarget = { x: this.x, y: this.y };
        this.phase = Math.random() * Math.PI * 2;

        // 単語
        this.vocab = getRandomBossVocab();
        bossVocabIndex++;

        // === 攻撃サイクル ===
        // 5秒攻撃（50発＝100msごと）→5秒休憩→…を繰り返す
        this.attackDuration = 5000;
        this.restDuration   = 5000;
        this.cycleDuration  = this.attackDuration + this.restDuration;
        this.shotInterval   = 100; // 100msで1発 → 5秒で50発
        this.cycleStartTime = performance.now();
        this.isAttacking    = true;   // 生成直後は攻撃フェーズから
        this.prevAttacking  = true;
        this.nextShotTime   = this.cycleStartTime; // フェーズ開始直後に撃ち始める
    }

    pickNewTarget() {
        const padX = 40, padY = 60;
        const w = canvas.width, h = canvas.height;
        this.moveTarget.x = Math.random() * (w - this.width  - padX*2) + padX;
        this.moveTarget.y = Math.random() * (h * 0.55 - this.height - padY*2) + padY;
    }

    update(now) {
        // ---- 攻撃サイクル判定 ----
        const tInCycle   = (now - this.cycleStartTime) % this.cycleDuration;
        const attacking  = tInCycle < this.attackDuration;
        const phaseStart = now - tInCycle; // 現在サイクルの開始時刻
        const attackEnd  = phaseStart + this.attackDuration;

        // フェーズ切り替え（休憩→攻撃 に入った瞬間に弾のスケジュールをリセット）
        if (!this.prevAttacking && attacking) {
        this.nextShotTime = phaseStart; // 攻撃フェーズ頭から100ms刻みで発射
        }
        this.prevAttacking = this.isAttacking;
        this.isAttacking   = attacking;

        // ---- 攻撃（攻撃フェーズ中のみ100ms間隔で発射。5秒で50発）----
        if (attacking) {
        while (now >= this.nextShotTime && this.nextShotTime < attackEnd) {
            this.fireBeam();
            this.nextShotTime += this.shotInterval;
        }
        }

        // ---- 機動（休憩中も動く。止めたい場合は attacking のときだけ移動するようにしてね）----
        if (now - this.lastMoveChange > 700) {
        this.lastMoveChange = now;
        this.pickNewTarget();
        }
        const dx = this.moveTarget.x - this.x;
        const dy = this.moveTarget.y - this.y;
        const d  = Math.hypot(dx, dy) || 1;
        const vx = (dx / d) * this.speed;
        const vy = (dy / d) * this.speed;
        this.x += vx * (1 + Math.sin(now * 0.01 + this.phase) * 0.15);
        this.y += vy * (1 + Math.cos(now * 0.012 + this.phase) * 0.15);

        // 画面内に収める
        this.x = Math.max(10, Math.min(this.x, canvas.width - this.width - 10));
        this.y = Math.max(10, Math.min(this.y, canvas.height * 0.7 - this.height));
    }

    fireBeam() {
        // プレイヤー狙いの赤い弾（少しばらける）
        const cx = this.x + this.width/2;
        const cy = this.y + this.height/2;
        const px = gameState.player.x + gameState.player.width/2;
        const py = gameState.player.y + gameState.player.height/2;
        let dx = px - cx, dy = py - cy;
        const dist = Math.hypot(dx, dy) || 1;
        dx /= dist; dy /= dist;

        const spread = (Math.random() - 0.5) * 0.22;
        const angle  = Math.atan2(dy, dx) + spread;
        const speed  = 12;

        gameState.bossBeams.push({
        x: cx, y: cy,
        vx: Math.cos(angle) * speed,
        vy: Math.sin(angle) * speed,
        r: 8
        });
    }

    nextWord() {
        this.vocab = getRandomBossVocab();
        bossVocabIndex++;
    }

    // Boss.draw だけ差し替え
    draw() {
    const now = performance.now();
    const t  = now * 0.002 + this.phase;
    const cx = this.x + this.width/2;
    const cy = this.y + this.height/2;

    // ====== 悪魔の羽（背面・大きく激しく動く）======
    // 強めのフラップ（角度＆伸縮が大きい）
    const flap = Math.sin(t * 3.2);         // 振動数
    const wingAngle = flap * 0.95;          // 角度の振れ（約±54°）
    const wingStretch = 1 + Math.abs(flap) * 0.45; // 伸縮で“バサッ”感

    const drawDemonWing = (side) => {
        // side: -1=左 / +1=右（左右対称に反転）
        const baseX = cx + side * (this.width * 0.36);
        const baseY = cy - 10;

        // スパイン（骨）を何本か放射状に
        const spines = [];
        const spineCount = 4; // 指の本数
        for (let i = 0; i < spineCount; i++) {
        // 各スパインの角度（外側ほど下向き）
        const a0 = -0.15 - i * 0.35;              // ベース角
        const wob = Math.sin(t * 2.4 + i) * 0.12; // わずかにうねる
        const ang = a0 + wob + wingAngle;         // フラップ反映
        const len = (64 + i * 18) * wingStretch;  // 外側ほど長い
        const ex = baseX + side * Math.cos(ang) * len;
        const ey = baseY + Math.sin(ang) * len;

        // 骨（太い→細い）
        const grd = ctx.createLinearGradient(baseX, baseY, ex, ey);
        grd.addColorStop(0, '#240000');
        grd.addColorStop(1, '#5a0a0a');
        ctx.strokeStyle = grd;
        ctx.lineCap = 'round';
        ctx.lineWidth = 6 - i * 1.2;
        ctx.beginPath();
        ctx.moveTo(baseX, baseY);
        ctx.lineTo(ex, ey);
        ctx.stroke();

        spines.push({ x: ex, y: ey });
        }

        // 膜（スパイン同士を三角～四角形で結び、悪魔っぽいギザギザ）
        ctx.save();
        ctx.globalAlpha = 0.72; // 透ける膜
        for (let i = 0; i < spines.length; i++) {
        const p0 = { x: baseX, y: baseY };
        const p1 = spines[i];
        const p2 = spines[Math.min(i + 1, spines.length - 1)];

        // 暗赤～黒のグラデ膜
        const mg = ctx.createLinearGradient(p0.x, p0.y, p1.x, p1.y);
        mg.addColorStop(0, 'rgba(120, 0, 0, 0.9)');
        mg.addColorStop(1, 'rgba(20, 0, 0, 0.95)');
        ctx.fillStyle = mg;
        ctx.strokeStyle = 'rgba(255, 30, 30, 0.15)';
        ctx.lineWidth = 1.2;

        ctx.beginPath();
        ctx.moveTo(p0.x, p0.y);
        ctx.lineTo(p1.x, p1.y);
        ctx.lineTo(p2.x, p2.y);
        ctx.closePath();
        ctx.fill();
        ctx.stroke();
        }
        ctx.restore();

        // 膜の縁にトゲ（ギザギザ）
        ctx.save();
        ctx.strokeStyle = 'rgba(180, 0, 0, 0.45)';
        ctx.lineWidth = 1.5;
        for (let i = 0; i < spines.length - 1; i++) {
        const a = spines[i], b = spines[i + 1];
        const mx = (a.x + b.x) / 2;
        const my = (a.y + b.y) / 2;
        // 外側に小トゲ
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
    };

    // 羽は“背面”なので、本体を描く前に描画
    ctx.save();
    drawDemonWing(-1);
    drawDemonWing(+1);
    ctx.restore();

    // ===== 蜘蛛っぽい不気味な本体（前回のまま） =====
    ctx.save();
    // 影
    ctx.fillStyle = 'rgba(0,0,0,0.35)';
    ctx.beginPath();
    ctx.ellipse(cx, this.y + this.height + 10, this.width*0.45, 12, 0, 0, Math.PI*2);
    ctx.fill();

    // 腹部
    let g = ctx.createRadialGradient(cx-10, cy-10, 6, cx, cy, this.width*0.6);
    g.addColorStop(0, '#1a0f14');
    g.addColorStop(0.6, '#0b0508');
    g.addColorStop(1, '#000000');
    ctx.fillStyle = g;
    ctx.beginPath();
    ctx.ellipse(cx, cy+8, this.width*0.45, this.height*0.42, 0, 0, Math.PI*2);
    ctx.fill();

    // 頭胸部
    g = ctx.createRadialGradient(cx-6, cy-6, 4, cx+2, cy-2, this.width*0.28);
    g.addColorStop(0, '#26151a');
    g.addColorStop(1, '#040203');
    ctx.fillStyle = g;
    ctx.beginPath();
    ctx.ellipse(cx, cy-18, this.width*0.28, this.height*0.22, 0, 0, Math.PI*2);
    ctx.fill();

    // 牙
    ctx.fillStyle = '#e6e6e6';
    const fangW = 8, fangH = 16;
    ctx.beginPath();
    ctx.moveTo(cx-10, cy-6);
    ctx.lineTo(cx-10-fangW, cy-6+fangH);
    ctx.lineTo(cx-10+fangW*0.3, cy-6+fangH*0.7);
    ctx.closePath();
    ctx.fill();
    ctx.beginPath();
    ctx.moveTo(cx+10, cy-6);
    ctx.lineTo(cx+10+fangW, cy-6+fangH);
    ctx.lineTo(cx+10-fangW*0.3, cy-6+fangH*0.7);
    ctx.closePath();
    ctx.fill();

    // 赤い複眼
    const eye = (ex, ey, r, a) => {
        ctx.save();
        ctx.globalAlpha = a;
        const eg = ctx.createRadialGradient(ex-1, ey-1, 0, ex, ey, r*1.6);
        eg.addColorStop(0, '#ff5555');
        eg.addColorStop(0.6, '#990000');
        eg.addColorStop(1, '#220000');
        ctx.fillStyle = eg;
        ctx.beginPath();
        ctx.arc(ex, ey, r, 0, Math.PI*2);
        ctx.fill();
        ctx.restore();
    };
    const eyeR = 4;
    const eyeLayout = [
        [-18, -18], [-6, -20], [6, -20], [18, -18],
        [-12, -10], [-4, -12], [4, -12], [12, -10],
    ];
    eyeLayout.forEach(([ox, oy], i) => {
        const wob = Math.sin(t*3 + i) * 1.2;
        eye(cx + ox + wob, cy + oy + wob*0.5, eyeR + (i%3===0?1:0), 0.9);
    });

    // 脚（8本）
    const drawLeg = (side, order) => {
        const baseX = cx + side * (this.width*0.25);
        const baseY = cy - 10 + order*6;
        const segLen = 26 + order*3;
        const bend1  = (Math.sin(t*2 + order*0.6 + side*0.8) * 0.35) + (side>0?0.2:-0.2);
        const bend2  = (Math.cos(t*2.4 + order*0.8 + side*0.3) * 0.45) + (side>0?0.3:-0.3);

        const j1x = baseX + side * segLen * Math.cos(0.2 + bend1);
        const j1y = baseY + segLen * Math.sin(0.2 + bend1);
        const j2x = j1x   + side * segLen * Math.cos(0.7 + bend2);
        const j2y = j1y   + segLen * Math.sin(0.7 + bend2);
        const tipx= j2x   + side * (segLen*0.9) * Math.cos(1.1 + bend2*0.8);
        const tipy= j2y   + (segLen*0.9) * Math.sin(1.1 + bend2*0.8);

        ctx.strokeStyle = '#1b0f12';
        ctx.lineWidth = 5;
        ctx.lineCap = 'round';
        ctx.beginPath();
        ctx.moveTo(baseX, baseY);
        ctx.lineTo(j1x, j1y);
        ctx.lineTo(j2x, j2y);
        ctx.lineTo(tipx, tipy);
        ctx.stroke();

        ctx.strokeStyle = '#3a1f2a';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(baseX, baseY);
        ctx.lineTo(j1x, j1y);
        ctx.lineTo(j2x, j2y);
        ctx.lineTo(tipx, tipy);
        ctx.stroke();
    };
    for (let i = 0; i < 4; i++) { drawLeg(-1, i); drawLeg(+1, i); }
    ctx.restore();

    // ===== 単語カード（重ならないよう上に）=====
    const cardTop = Math.max(10, this.y - this.height - 140);
    drawWordCard(this.vocab, cx, cardTop, 180, 120);

    // 休憩中のオーラ
    if (!this.isAttacking) {
        ctx.save();
        ctx.globalAlpha = 0.25;
        ctx.strokeStyle = 'rgba(120,0,0,0.7)';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.ellipse(cx, cy+2, this.width*0.55, this.height*0.48, 0, 0, Math.PI*2);
        ctx.stroke();
        ctx.restore();
    }
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
            this.y = this.startY - (this.maxLife - this.life) * 2;
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
        this.speed = 9;
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
        this.y -= this.speed;
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
    beam.y += beam.speed;

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

    function updateBossBeams() {
        const p = gameState.player;
        gameState.bossBeams = gameState.bossBeams.filter(b => {
            b.x += b.vx; b.y += b.vy;

            // 円（ビーム）と矩形（プレイヤー）の簡易当たり
            const nearestX = Math.max(p.x, Math.min(b.x, p.x + p.width));
            const nearestY = Math.max(p.y, Math.min(b.y, p.y + p.height));
            const dist = Math.hypot(b.x - nearestX, b.y - nearestY);

            if (dist <= b.r) {
            gameState.explosions.push(new Explosion(p.x + p.width/2, p.y - 20));
            gameState.life--;
            updateUI();
            return false;
            }

            return b.x > -40 && b.x < canvas.width + 40 && b.y > -40 && b.y < canvas.height + 40;
        });

        // 描画（赤い発光弾）
        gameState.bossBeams.forEach(b => {
            ctx.save();
            ctx.shadowColor = 'rgba(255,40,40,0.9)';
            ctx.shadowBlur = 18;
            const g = ctx.createRadialGradient(b.x, b.y, 0, b.x, b.y, b.r);
            g.addColorStop(0, '#fff5f5');
            g.addColorStop(0.6, '#ff5555');
            g.addColorStop(1, '#aa0000');
            ctx.fillStyle = g;
            ctx.beginPath();
            ctx.arc(b.x, b.y, b.r, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
        });
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
    
    // プレイヤー更新（なめらかな移動）
    function updatePlayer() {
        const moveSpeed = 18;
        if (gameState.keys['ArrowLeft'] && gameState.player.x > 0) {
            gameState.player.x -= moveSpeed;
        }
        if (gameState.keys['ArrowRight'] && gameState.player.x < canvas.width - gameState.player.width) {
            gameState.player.x += moveSpeed;
        }
        if (gameState.keys['ArrowUp'] && gameState.player.y > 0) {
            gameState.player.y -= moveSpeed;
        }
        if (gameState.keys['ArrowDown'] && gameState.player.y < canvas.height - gameState.player.height - 100) {
            gameState.player.y += moveSpeed;
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

        if (Math.random() < 0.015 && gameState.enemies.length < 4) {
        const newEnemy = new Enemy();
        gameState.enemies.push(newEnemy);
        }
    } catch (error) {
        console.error('敵生成エラー:', error.message);
    }
    }
    
    // ゲームループ
function gameLoop() {
    try {
        if (!gameState.gameRunning) return;

        gameState.animationTime++;

        if (gameState.life <= 0) { gameOver(); return; }

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawStars();

        // 通常敵の生成・更新・描画
        spawnEnemy();
        gameState.enemies = gameState.enemies.filter(enemy => {
            if (enemy && enemy.draw && enemy.update) {
                enemy.draw();
                return enemy.update();
            }
            return false;
        });

        // ★ スコア到達でボス出現
        spawnBossIfReady();
            // ★ ボス更新・描画（フィナーレ中は描かない）
            if (gameState.boss && !gameState.bossFinaleActive) {
            const now = performance.now();
            gameState.boss.update(now);
            gameState.boss.draw();
        }

        // ミサイル更新・描画
        gameState.missiles = gameState.missiles.filter(missile => {
            if (missile && missile.draw && missile.update) {
                missile.draw();
                return missile.update();
            }
            return false;
        });

        // 通常敵ビーム
        updateEnemyBeams();

        // ★ ボスのビーム
        updateBossBeams();

        // 衝突
        checkCollisions();
        checkPlayerEnemyCollisions();

        // 爆発
        gameState.explosions = gameState.explosions.filter(ex => {
            if (ex && ex.draw && ex.update) { ex.draw(); return ex.update(); }
            return false;
        });
        gameState.explosions = gameState.explosions.filter(ex => {
        if (ex && ex.draw && ex.update) { ex.draw(); return ex.update(); }
        return false;
        });

        // ★ 3秒大爆発の演出
        updateAndDrawBossFinale();

        // メッセージ
        gameState.messages = gameState.messages.filter(msg => {
            if (msg && msg.draw && msg.update) { msg.draw(); return msg.update(); }
            return false;
        });

        // プレイヤー
        updatePlayer();
        drawPlayer();

        // ★ ボスHPバー（最後にUIとして）
        drawBossHPBar();

        // ★ WARNINGオーバーレイ（最前面に出したいので一番最後に描く）
        drawWarningOverlay();

        requestAnimationFrame(gameLoop);
    } catch (error) {
        console.error('ゲームループエラー詳細:', error.message);
        console.error('スタックトレース:', error.stack);
        console.error('gameState:', gameState);
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
            life: 3,
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
            x: canvas.width / 2 - 25,
            y: canvas.height - 120,
            width: 50,
            height: 40
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
    // 矢印キーは押下中 true、離したら false。既定動作は止める。
    const ARROWS = ['ArrowUp','ArrowDown','ArrowLeft','ArrowRight'];

    document.addEventListener('keydown', (e) => {
    if (!gameState.gameRunning) return;

    const k = e.key;

    // 矢印：移動フラグON＋既定動作を止める（スクロール／フォーカス移動防止）
    if (ARROWS.includes(k)) {
        e.preventDefault();
        gameState.keys[k] = true;
    }

    // 数字1〜4：発射
    if (k === '1' || k === '2' || k === '3' || k === '4') {
        e.preventDefault(); // IMEやフォーカス移動の悪影響を避ける
        const n = parseInt(k, 10);
        gameState.missiles.push(new Missile(
        gameState.player.x + gameState.player.width / 2 - 10,
        gameState.player.y - 30,
        n
        ));
    }
    });

    // 離したらフラグOFF（document でも window でもOK。安全のため window）
    window.addEventListener('keyup', (e) => {
    const k = e.key;
    if (ARROWS.includes(k)) {
        e.preventDefault();
        gameState.keys[k] = false;
    }
    });

    // タブ外へフォーカスが移った時に押下状態をクリア（キー張り付き防止）
    window.addEventListener('blur', () => {
    ARROWS.forEach(k => gameState.keys[k] = false);
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
  gameState.missiles.push(new Missile(
    gameState.player.x + gameState.player.width / 2 - 10,
    gameState.player.y - 30,
    num
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
    </script>
</body>
</html>