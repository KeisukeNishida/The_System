<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>English Learning Shooting Game</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #000;
            color: white;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
        }
        
        #gameCanvas {
            display: block;
            margin: 0 auto;
            background: linear-gradient(180deg, #001133 0%, #003366 100%);
            border: 2px solid #333;
        }
        
        .game-ui {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 18px;
            z-index: 10;
        }
        
        .life-display {
            color: #ff4444;
        }
        
        .score-display {
            color: #44ff44;
            margin-top: 5px;
        }
        
        .instructions {
            position: absolute;
            bottom: 10px;
            left: 10px;
            font-size: 14px;
            color: #aaa;
        }
        
        .game-over {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(0,0,0,0.8);
            padding: 20px;
            border-radius: 10px;
            display: none;
        }
        
        .restart-btn {
            background: #4444ff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .restart-btn:hover {
            background: #6666ff;
        }
    </style>
</head>
<body>
    <div class="game-ui">
        <div class="life-display">❤️ Life: <span id="lifeCount">3</span></div>
        <div class="score-display">⭐ Score: <span id="scoreCount">0</span></div>
    </div>
    
    <div class="instructions">
        数字キー1-4で正しい答えを選んで攻撃！
    </div>
    
    <div class="game-over" id="gameOver">
        <h2>ゲームオーバー</h2>
        <p>最終スコア: <span id="finalScore">0</span></p>
        <button class="restart-btn" onclick="restartGame()">リスタート</button>
    </div>
    
    <canvas id="gameCanvas" width="800" height="600"></canvas>

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
            player: {
                x: canvas.width / 2 - 25,
                y: canvas.height - 80,
                width: 50,
                height: 40
            }
        };
        
        // 英単語データ（30個）
        const vocabularyData = [
            { word: "Administrator", options: ["管理者", "遊牧民", "国会議員", "両生類"], correct: 1 },
            { word: "Beautiful", options: ["醜い", "美しい", "巨大な", "小さな"], correct: 2 },
            { word: "Computer", options: ["椅子", "机", "コンピュータ", "本"], correct: 3 },
            { word: "Democracy", options: ["独裁", "共和制", "君主制", "民主主義"], correct: 4 },
            { word: "Education", options: ["教育", "娯楽", "運動", "食事"], correct: 1 },
            { word: "Fantastic", options: ["普通の", "素晴らしい", "悪い", "古い"], correct: 2 },
            { word: "Geography", options: ["歴史", "数学", "地理", "科学"], correct: 3 },
            { word: "Hospital", options: ["学校", "公園", "店", "病院"], correct: 4 },
            { word: "Important", options: ["重要な", "簡単な", "難しい", "楽しい"], correct: 1 },
            { word: "Journey", options: ["家", "旅行", "仕事", "勉強"], correct: 2 },
            { word: "Kitchen", options: ["寝室", "浴室", "台所", "居間"], correct: 3 },
            { word: "Language", options: ["音楽", "絵画", "ダンス", "言語"], correct: 4 },
            { word: "Mountain", options: ["山", "海", "川", "湖"], correct: 1 },
            { word: "Necessary", options: ["不要な", "必要な", "可能な", "不可能な"], correct: 2 },
            { word: "Ocean", options: ["森", "砂漠", "海洋", "草原"], correct: 3 },
            { word: "Peaceful", options: ["騒がしい", "危険な", "忙しい", "平和な"], correct: 4 },
            { word: "Question", options: ["質問", "答え", "問題", "解決"], correct: 1 },
            { word: "Rainbow", options: ["稲妻", "虹", "雲", "雨"], correct: 2 },
            { word: "Sculpture", options: ["音楽", "詩", "彫刻", "小説"], correct: 3 },
            { word: "Telephone", options: ["テレビ", "ラジオ", "新聞", "電話"], correct: 4 },
            { word: "Universe", options: ["宇宙", "地球", "太陽", "月"], correct: 1 },
            { word: "Vegetable", options: ["肉", "野菜", "果物", "パン"], correct: 2 },
            { word: "Wonderful", options: ["普通の", "悪い", "素晴らしい", "古い"], correct: 3 },
            { word: "Yesterday", options: ["今日", "明日", "来週", "昨日"], correct: 4 },
            { word: "Adventure", options: ["冒険", "平凡", "退屈", "日常"], correct: 1 },
            { word: "Butterfly", options: ["蜘蛛", "蝶", "蜂", "蟻"], correct: 2 },
            { word: "Celebrate", options: ["悲しむ", "怒る", "祝う", "心配する"], correct: 3 },
            { word: "Dangerous", options: ["安全な", "簡単な", "楽しい", "危険な"], correct: 4 },
            { word: "Elephant", options: ["象", "ライオン", "虎", "熊"], correct: 1 },
            { word: "Furniture", options: ["食べ物", "家具", "服", "本"], correct: 2 }
        ];
        
        let currentVocabIndex = 0;
        
        // 敵クラス
        class Enemy {
            constructor() {
                this.x = Math.random() * (canvas.width - 120);
                this.y = -100;
                this.width = 120;
                this.height = 140;
                this.speed = 1 + Math.random();
                this.vocab = vocabularyData[currentVocabIndex % vocabularyData.length];
                this.lastBeamTime = 0;
                this.beamInterval = 2000 + Math.random() * 2000;
                currentVocabIndex++;
            }
            
            update() {
                this.y += this.speed;
                
                // ビーム攻撃
                const now = Date.now();
                if (now - this.lastBeamTime > this.beamInterval) {
                    this.fireBeam();
                    this.lastBeamTime = now;
                }
                
                return this.y < canvas.height + 100;
            }
            
            fireBeam() {
                gameState.enemyBeams.push({
                    x: this.x + this.width / 2,
                    y: this.y + this.height,
                    width: 5,
                    height: 15,
                    speed: 3
                });
            }
            
            draw() {
                // 狂気の天使デザイン
                ctx.save();
                
                // 本体（不気味な天使）
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(this.x + 40, this.y + 60, 40, 60);
                
                // 翼（歪んだ）
                ctx.fillStyle = '#ffeeaa';
                ctx.fillRect(this.x, this.y + 40, 30, 80);
                ctx.fillRect(this.x + 90, this.y + 40, 30, 80);
                
                // 目（狂気）
                ctx.fillStyle = '#ff0000';
                ctx.fillRect(this.x + 45, this.y + 65, 8, 8);
                ctx.fillRect(this.x + 67, this.y + 65, 8, 8);
                
                // ハロー（歪んだ）
                ctx.strokeStyle = '#ffff00';
                ctx.lineWidth = 3;
                ctx.beginPath();
                ctx.arc(this.x + 60, this.y + 50, 25, 0, Math.PI * 2);
                ctx.stroke();
                
                ctx.restore();
                
                // 単語カード
                ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
                ctx.fillRect(this.x - 10, this.y - 50, 140, 80);
                
                ctx.fillStyle = '#000';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(this.vocab.word, this.x + 60, this.y - 25);
                
                ctx.font = '12px Arial';
                ctx.textAlign = 'left';
                for (let i = 0; i < 4; i++) {
                    ctx.fillText(`${i+1}.${this.vocab.options[i]}`, this.x - 5, this.y - 5 + i * 15);
                }
            }
        }
        
        // ミサイルクラス
        class Missile {
            constructor(x, y, number) {
                this.x = x;
                this.y = y;
                this.width = 20;
                this.height = 30;
                this.speed = 8;
                this.number = number;
            }
            
            update() {
                this.y -= this.speed;
                return this.y > -50;
            }
            
            draw() {
                // 数字の形をしたミサイル
                ctx.fillStyle = '#ff4444';
                ctx.fillRect(this.x, this.y, this.width, this.height);
                
                ctx.fillStyle = '#ffffff';
                ctx.font = '20px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(this.number, this.x + this.width/2, this.y + 22);
            }
        }
        
        // プレイヤー描画
        function drawPlayer() {
            const p = gameState.player;
            
            // 狂気の悪魔戦闘機
            ctx.fillStyle = '#330000';
            ctx.fillRect(p.x, p.y, p.width, p.height);
            
            // 翼（悪魔風）
            ctx.fillStyle = '#660000';
            ctx.fillRect(p.x - 10, p.y + 10, 20, 30);
            ctx.fillRect(p.x + 40, p.y + 10, 20, 30);
            
            // エンジン
            ctx.fillStyle = '#ff6600';
            ctx.fillRect(p.x + 20, p.y + 35, 10, 15);
            
            // 悪魔の目
            ctx.fillStyle = '#ff0000';
            ctx.fillRect(p.x + 15, p.y + 5, 6, 6);
            ctx.fillRect(p.x + 29, p.y + 5, 6, 6);
            
            // 角
            ctx.fillStyle = '#000000';
            ctx.fillRect(p.x + 12, p.y - 5, 4, 8);
            ctx.fillRect(p.x + 34, p.y - 5, 4, 8);
        }
        
        // 敵ビーム更新・描画
        function updateEnemyBeams() {
            gameState.enemyBeams = gameState.enemyBeams.filter(beam => {
                beam.y += beam.speed;
                
                // プレイヤーとの衝突判定
                if (beam.x < gameState.player.x + gameState.player.width &&
                    beam.x + beam.width > gameState.player.x &&
                    beam.y < gameState.player.y + gameState.player.height &&
                    beam.y + beam.height > gameState.player.y) {
                    gameState.life--;
                    updateUI();
                    return false;
                }
                
                return beam.y < canvas.height;
            });
            
            // ビーム描画
            ctx.fillStyle = '#ff00ff';
            gameState.enemyBeams.forEach(beam => {
                ctx.fillRect(beam.x, beam.y, beam.width, beam.height);
            });
        }
        
        // 衝突判定
        function checkCollisions() {
            gameState.missiles.forEach((missile, mIndex) => {
                gameState.enemies.forEach((enemy, eIndex) => {
                    if (missile.x < enemy.x + enemy.width &&
                        missile.x + missile.width > enemy.x &&
                        missile.y < enemy.y + enemy.height &&
                        missile.y + missile.height > enemy.y) {
                        
                        // 正解チェック
                        if (missile.number === enemy.vocab.correct) {
                            gameState.score += 100;
                        } else {
                            gameState.life--;
                        }
                        
                        gameState.missiles.splice(mIndex, 1);
                        gameState.enemies.splice(eIndex, 1);
                        updateUI();
                    }
                });
            });
        }
        
        // UI更新
        function updateUI() {
            document.getElementById('lifeCount').textContent = gameState.life;
            document.getElementById('scoreCount').textContent = gameState.score;
        }
        
        // 敵生成
        function spawnEnemy() {
            if (Math.random() < 0.02) {
                gameState.enemies.push(new Enemy());
            }
        }
        
        // ゲームループ
        function gameLoop() {
            if (!gameState.gameRunning) return;
            
            // ライフチェック
            if (gameState.life <= 0) {
                gameOver();
                return;
            }
            
            // 画面クリア
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // 星空背景
            for (let i = 0; i < 50; i++) {
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(Math.random() * canvas.width, Math.random() * canvas.height, 1, 1);
            }
            
            // 敵生成
            spawnEnemy();
            
            // 敵更新・描画
            gameState.enemies = gameState.enemies.filter(enemy => {
                enemy.draw();
                return enemy.update();
            });
            
            // ミサイル更新・描画
            gameState.missiles = gameState.missiles.filter(missile => {
                missile.draw();
                return missile.update();
            });
            
            // 敵ビーム更新
            updateEnemyBeams();
            
            // 衝突判定
            checkCollisions();
            
            // プレイヤー描画
            drawPlayer();
            
            requestAnimationFrame(gameLoop);
        }
        
        // ゲームオーバー
        function gameOver() {
            gameState.gameRunning = false;
            document.getElementById('finalScore').textContent = gameState.score;
            document.getElementById('gameOver').style.display = 'block';
        }
        
        // ゲーム再開
        function restartGame() {
            gameState = {
                life: 3,
                score: 0,
                gameRunning: true,
                enemies: [],
                missiles: [],
                enemyBeams: [],
                player: {
                    x: canvas.width / 2 - 25,
                    y: canvas.height - 80,
                    width: 50,
                    height: 40
                }
            };
            currentVocabIndex = 0;
            document.getElementById('gameOver').style.display = 'none';
            updateUI();
            gameLoop();
        }
        
        // キーボード入力
        document.addEventListener('keydown', (e) => {
            if (!gameState.gameRunning) return;
            
            const key = e.key;
            if (['1', '2', '3', '4'].includes(key)) {
                const number = parseInt(key);
                gameState.missiles.push(new Missile(
                    gameState.player.x + gameState.player.width / 2 - 10,
                    gameState.player.y - 30,
                    number
                ));
            }
            
            // プレイヤー移動
            const moveSpeed = 5;
            if (key === 'ArrowLeft' && gameState.player.x > 0) {
                gameState.player.x -= moveSpeed;
            }
            if (key === 'ArrowRight' && gameState.player.x < canvas.width - gameState.player.width) {
                gameState.player.x += moveSpeed;
            }
        });
        
        // ゲーム開始
        updateUI();
        gameLoop();
    </script>
</body>
</html>