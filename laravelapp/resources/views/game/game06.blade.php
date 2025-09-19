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
        数字キー1-4で正しい答えを選んで攻撃！矢印キーで移動
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
            explosions: [],
            messages: [],
            stars: [],
            player: {
                x: canvas.width / 2 - 25,
                y: canvas.height - 80,
                width: 50,
                height: 40
            },
            keys: {}
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
        
        // 星空描画
        function drawStars() {
            ctx.fillStyle = '#ffffff';
            gameState.stars.forEach(star => {
                ctx.fillRect(star.x, star.y, star.size, star.size);
            });
        }
        
        // 敵クラス
        class Enemy {
            constructor() {
                this.x = this.findValidPosition();
                this.y = -60;
                this.width = 60;
                this.height = 80;
                this.speed = 1 + Math.random();
                this.vocab = vocabularyData[currentVocabIndex % vocabularyData.length];
                this.lastBeamTime = 0;
                this.beamInterval = 2000 + Math.random() * 2000;
                currentVocabIndex++;
            }
            
            findValidPosition() {
                const minDistance = 120; // 敵同士の最小距離
                let attempts = 0;
                let x;
                
                do {
                    x = Math.random() * (canvas.width - 60);
                    attempts++;
                    
                    // 他の敵との距離をチェック
                    let validPosition = true;
                    if (gameState && gameState.enemies) {
                        for (let enemy of gameState.enemies) {
                            const distance = Math.abs(x - enemy.x);
                            const yDistance = Math.abs(-60 - enemy.y);
                            if (distance < minDistance && yDistance < 150) {
                                validPosition = false;
                                break;
                            }
                        }
                    }
                    
                    if (validPosition || attempts > 20) {
                        break;
                    }
                } while (true);
                
                return x;
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
                // 狂気の天使デザイン（さらに小さくなったサイズ）
                ctx.save();
                
                // 本体（不気味な天使）
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(this.x + 20, this.y + 30, 20, 30);
                
                // 翼（歪んだ）
                ctx.fillStyle = '#ffeeaa';
                ctx.fillRect(this.x, this.y + 25, 15, 40);
                ctx.fillRect(this.x + 45, this.y + 25, 15, 40);
                
                // 目（狂気）
                ctx.fillStyle = '#ff0000';
                ctx.fillRect(this.x + 23, this.y + 33, 4, 4);
                ctx.fillRect(this.x + 33, this.y + 33, 4, 4);
                
                // ハロー（歪んだ）
                ctx.strokeStyle = '#ffff00';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.arc(this.x + 30, this.y + 25, 12, 0, Math.PI * 2);
                ctx.stroke();
                
                ctx.restore();
                
                // 単語カード（さらに大きく調整）
                ctx.fillStyle = 'rgba(255, 255, 255, 0.95)';
                ctx.fillRect(this.x - 10, this.y - 40, 80, 60);
                
                ctx.fillStyle = '#000';
                ctx.font = '11px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(this.vocab.word, this.x + 30, this.y - 28);
                
                ctx.font = '9px Arial';
                ctx.textAlign = 'left';
                for (let i = 0; i < 4; i++) {
                    const text = `${i+1}.${this.vocab.options[i]}`;
                    const maxLength = 10; // 最大文字数を増やす
                    const displayText = text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
                    ctx.fillText(displayText, this.x - 7, this.y - 15 + i * 11);
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
                    
                    // プレイヤーに爆発エフェクト
                    gameState.explosions.push(new Explosion(
                        gameState.player.x + gameState.player.width / 2,
                        gameState.player.y + gameState.player.height / 2
                    ));
                    
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
            try {
                if (!gameState.missiles || !gameState.enemies) return;
                
                for (let mIndex = gameState.missiles.length - 1; mIndex >= 0; mIndex--) {
                    const missile = gameState.missiles[mIndex];
                    if (!missile) continue;
                    
                    for (let eIndex = gameState.enemies.length - 1; eIndex >= 0; eIndex--) {
                        const enemy = gameState.enemies[eIndex];
                        if (!enemy) continue;
                        
                        if (missile.x < enemy.x + enemy.width &&
                            missile.x + missile.width > enemy.x &&
                            missile.y < enemy.y + enemy.height &&
                            missile.y + missile.height > enemy.y) {
                            
                            // 敵に爆発エフェクト
                            gameState.explosions.push(new Explosion(
                                enemy.x + enemy.width / 2,
                                enemy.y + enemy.height / 2
                            ));
                            
                            // 正解チェック
                            if (missile.number === enemy.vocab.correct) {
                                gameState.score += 100;
                                // "OK"メッセージを表示（赤字）
                                gameState.messages.push(new FloatingMessage(
                                    enemy.x + enemy.width / 2,
                                    enemy.y - 10,
                                    "OK",
                                    "#ff0000"
                                ));
                            } else {
                                gameState.life--;
                                // "MISS"メッセージを表示（青字）
                                gameState.messages.push(new FloatingMessage(
                                    enemy.x + enemy.width / 2,
                                    enemy.y - 10,
                                    "MISS",
                                    "#0000ff"
                                ));
                            }
                            
                            gameState.missiles.splice(mIndex, 1);
                            gameState.enemies.splice(eIndex, 1);
                            updateUI();
                            break;
                        }
                    }
                }
            } catch (error) {
                console.error('衝突判定エラー:', error.message);
            }
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
                        
                        // プレイヤーに爆発エフェクト
                        gameState.explosions.push(new Explosion(
                            gameState.player.x + gameState.player.width / 2,
                            gameState.player.y + gameState.player.height / 2
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
            const moveSpeed = 8;
            if (gameState.keys['ArrowLeft'] && gameState.player.x > 0) {
                gameState.player.x -= moveSpeed;
            }
            if (gameState.keys['ArrowRight'] && gameState.player.x < canvas.width - gameState.player.width) {
                gameState.player.x += moveSpeed;
            }
            if (gameState.keys['ArrowUp'] && gameState.player.y > 0) {
                gameState.player.y -= moveSpeed;
            }
            if (gameState.keys['ArrowDown'] && gameState.player.y < canvas.height - gameState.player.height) {
                gameState.player.y += moveSpeed;
            }
        }
        function updateUI() {
            document.getElementById('lifeCount').textContent = gameState.life;
            document.getElementById('scoreCount').textContent = gameState.score;
        }
        
        // 敵生成
        function spawnEnemy() {
            try {
                // 敵の生成頻度を少し下げて重なりを防ぐ
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
                
                // ライフチェック
                if (gameState.life <= 0) {
                    gameOver();
                    return;
                }
                
                // 画面クリア
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                // 星空背景（固定）
                drawStars();
                
                // 敵生成
                spawnEnemy();
                
                // 敵更新・描画
                gameState.enemies = gameState.enemies.filter(enemy => {
                    if (enemy && typeof enemy.draw === 'function' && typeof enemy.update === 'function') {
                        enemy.draw();
                        return enemy.update();
                    }
                    return false;
                });
                
                // ミサイル更新・描画
                gameState.missiles = gameState.missiles.filter(missile => {
                    if (missile && typeof missile.draw === 'function' && typeof missile.update === 'function') {
                        missile.draw();
                        return missile.update();
                    }
                    return false;
                });
                
                // 敵ビーム更新
                updateEnemyBeams();
                
                // 衝突判定
                checkCollisions();
                
                // プレイヤーと敵の衝突判定
                checkPlayerEnemyCollisions();
                
                // 爆発更新・描画
                gameState.explosions = gameState.explosions.filter(explosion => {
                    if (explosion && typeof explosion.draw === 'function' && typeof explosion.update === 'function') {
                        explosion.draw();
                        return explosion.update();
                    }
                    return false;
                });
                
                // メッセージ更新・描画
                gameState.messages = gameState.messages.filter(message => {
                    if (message && typeof message.draw === 'function' && typeof message.update === 'function') {
                        message.draw();
                        return message.update();
                    }
                    return false;
                });
                
                // プレイヤー更新
                updatePlayer();
                
                // プレイヤー描画
                drawPlayer();
                
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
                player: {
                    x: canvas.width / 2 - 25,
                    y: canvas.height - 80,
                    width: 50,
                    height: 40
                },
                keys: {}
            };
            currentVocabIndex = 0;
            initStars();
            document.getElementById('gameOver').style.display = 'none';
            updateUI();
            gameLoop();
        }
        
        // キーボード入力
        document.addEventListener('keydown', (e) => {
            if (!gameState.gameRunning) return;
            
            const key = e.key;
            gameState.keys[key] = true;
            
            if (['1', '2', '3', '4'].includes(key)) {
                const number = parseInt(key);
                gameState.missiles.push(new Missile(
                    gameState.player.x + gameState.player.width / 2 - 10,
                    gameState.player.y - 30,
                    number
                ));
            }
        });
        
        document.addEventListener('keyup', (e) => {
            gameState.keys[e.key] = false;
        });
        
        // ゲーム開始
        initStars();
        updateUI();
        gameLoop();
    </script>
</body>
</html>