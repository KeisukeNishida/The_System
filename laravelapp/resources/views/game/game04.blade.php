<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>悪魔vs天使 3D英単語シューティング</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #000;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
            color: white;
        }

        #container {
            position: relative;
            width: 100vw;
            height: 100vh;
        }

        #ui {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 10;
        }

        #hud {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            background: rgba(0,0,0,0.5);
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #4ecdc4;
        }

        #score {
            color: #ff6b6b;
        }

        #lives {
            color: #4ecdc4;
            margin-top: 10px;
        }

        #wordCard {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.7);
            border: 3px solid #ffd93d;
            display: none;
            pointer-events: auto;
            min-width: 350px;
            backdrop-filter: blur(10px);
        }

        #englishWord {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 25px;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .choice {
            display: block;
            margin: 12px 0;
            padding: 15px 25px;
            background: linear-gradient(45deg, #74b9ff, #0984e3);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .choice:hover {
            transform: scale(1.05) translateY(-2px);
            background: linear-gradient(45deg, #0984e3, #74b9ff);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        #feedback {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 56px;
            font-weight: bold;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.8);
            display: none;
            z-index: 20;
            animation: feedbackPulse 0.5s ease-out;
        }

        @keyframes feedbackPulse {
            0% { transform: translateX(-50%) scale(0.5); opacity: 0; }
            50% { transform: translateX(-50%) scale(1.2); opacity: 1; }
            100% { transform: translateX(-50%) scale(1); opacity: 1; }
        }

        .correct {
            color: #00ff00;
            text-shadow: 0 0 20px #00ff00;
        }

        .incorrect {
            color: #ff0000;
            text-shadow: 0 0 20px #ff0000;
        }

        #gameOver, #startScreen {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 50px;
            border-radius: 20px;
            text-align: center;
            pointer-events: auto;
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255,255,255,0.2);
        }

        #gameOver {
            display: none;
        }

        button {
            padding: 15px 30px;
            font-size: 18px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        #startBtn {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
        }

        .instructions {
            margin: 20px 0;
            line-height: 1.6;
            color: #ccc;
        }

        #gameCanvas {
            display: block;
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
        }

        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 18px;
            display: none;
        }
    </style>
</head>
<body>
    <div id="container">
        <canvas id="gameCanvas"></canvas>
        
        <div class="loading" id="loading">3D環境を読み込み中...</div>
        
        <div id="ui">
            <div id="hud">
                <div id="score">スコア: <span id="scoreValue">0</span></div>
                <div id="lives">ライフ: <span id="livesValue">3</span></div>
            </div>

            <div id="wordCard">
                <div id="englishWord"></div>
                <button class="choice" data-choice="1"></button>
                <button class="choice" data-choice="2"></button>
                <button class="choice" data-choice="3"></button>
                <button class="choice" data-choice="4"></button>
            </div>

            <div id="feedback"></div>

            <div id="startScreen">
                <h1>🔥 悪魔vs天使 3D英単語シューティング 😇</h1>
                <div class="instructions">
                    <p>💀 悪魔となって天使の軍団を撃退せよ！</p>
                    <p>📚 正しい英単語の意味を選んで3Dミサイル攻撃！</p>
                    <p>🎮 マウスクリックまたは数字キー（1-4）で選択</p>
                    <p>❤️ ライフ3で開始、敵の侵入や間違いでライフ減少</p>
                </div>
                <button id="startBtn">ゲームスタート</button>
            </div>

            <div id="gameOver">
                <h2>🎮 ゲームオーバー</h2>
                <p>最終スコア: <span id="finalScore">0</span></p>
                <button id="restartBtn">リスタート</button>
            </div>
        </div>
    </div>

    <script>
        // 英単語データ（30個）
        const wordData = [
            { word: "Administrator", correct: 1, choices: ["管理者", "遊牧民", "国会議員", "両生類"] },
            { word: "Beautiful", correct: 2, choices: ["恐ろしい", "美しい", "巨大な", "古い"] },
            { word: "Computer", correct: 3, choices: ["机", "椅子", "コンピューター", "本"] },
            { word: "Democracy", correct: 1, choices: ["民主主義", "独裁制", "君主制", "封建制"] },
            { word: "Education", correct: 4, choices: ["娯楽", "運動", "食事", "教育"] },
            { word: "Freedom", correct: 2, choices: ["束縛", "自由", "義務", "責任"] },
            { word: "Generation", correct: 1, choices: ["世代", "季節", "時間", "場所"] },
            { word: "Hospital", correct: 3, choices: ["学校", "銀行", "病院", "図書館"] },
            { word: "Information", correct: 4, choices: ["混乱", "秘密", "噂", "情報"] },
            { word: "Justice", correct: 2, choices: ["犯罪", "正義", "復讐", "処罰"] },
            { word: "Knowledge", correct: 1, choices: ["知識", "無知", "疑問", "答え"] },
            { word: "Language", correct: 3, choices: ["数学", "音楽", "言語", "芸術"] },
            { word: "Mountain", correct: 4, choices: ["海", "川", "平原", "山"] },
            { word: "Necessary", correct: 2, choices: ["不要な", "必要な", "可能な", "危険な"] },
            { word: "Opinion", correct: 1, choices: ["意見", "事実", "法則", "証拠"] },
            { word: "President", correct: 3, choices: ["市民", "学生", "大統領", "労働者"] },
            { word: "Question", correct: 4, choices: ["答え", "説明", "理由", "質問"] },
            { word: "Restaurant", correct: 2, choices: ["映画館", "レストラン", "病院", "学校"] },
            { word: "Strategy", correct: 1, choices: ["戦略", "戦術", "武器", "平和"] },
            { word: "Technology", correct: 3, choices: ["自然", "伝統", "技術", "文化"] },
            { word: "Universe", correct: 4, choices: ["地球", "国家", "都市", "宇宙"] },
            { word: "Victory", correct: 2, choices: ["敗北", "勝利", "引き分け", "試合"] },
            { word: "Weather", correct: 1, choices: ["天気", "季節", "時間", "場所"] },
            { word: "Yesterday", correct: 3, choices: ["明日", "今日", "昨日", "来週"] },
            { word: "Adventure", correct: 4, choices: ["平和", "安全", "日常", "冒険"] },
            { word: "Creative", correct: 2, choices: ["破壊的", "創造的", "保守的", "消極的"] },
            { word: "Different", correct: 1, choices: ["異なる", "同じ", "似ている", "普通の"] },
            { word: "Economic", correct: 3, choices: ["政治の", "社会の", "経済の", "文化の"] },
            { word: "Fantastic", correct: 4, choices: ["現実的", "平凡", "普通", "素晴らしい"] },
            { word: "Important", correct: 2, choices: ["些細な", "重要な", "簡単な", "難しい"] }
        ];

        // ゲーム状態
        let gameState = {
            lives: 3,
            score: 0,
            currentWordIndex: 0,
            gameRunning: false,
            enemies: [],
            missiles: [],
            particles: []
        };

        // Canvas設定
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        let animationId;

        // UI要素
        const wordCard = document.getElementById('wordCard');
        const englishWord = document.getElementById('englishWord');
        const choices = document.querySelectorAll('.choice');
        const feedback = document.getElementById('feedback');
        const scoreValue = document.getElementById('scoreValue');
        const livesValue = document.getElementById('livesValue');
        const startScreen = document.getElementById('startScreen');
        const gameOverScreen = document.getElementById('gameOver');
        const finalScore = document.getElementById('finalScore');
        const loading = document.getElementById('loading');

        function initCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        // 敵クラス（3D風）
        class Enemy3D {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = -100;
                this.z = Math.random() * 300 + 100; // 奥行き
                this.speed = 2 + Math.random() * 2;
                this.size = 40 + Math.random() * 20;
                this.angle = 0;
                this.wobble = Math.random() * Math.PI * 2;
                this.wordIndex = gameState.currentWordIndex;
                this.showCard = false;
                this.wingAngle = 0;
            }

            update() {
                this.y += this.speed;
                this.angle += 0.02;
                this.wingAngle += 0.15;
                this.wobble += 0.05;
                
                // 3D効果の計算
                this.scale = 200 / (this.z + 200);
                this.screenSize = this.size * this.scale;
                
                // 画面中央に近づいたらカードを表示
                if (this.y > canvas.height * 0.2 && !this.showCard) {
                    this.showCard = true;
                    showWordCard(this.wordIndex);
                }

                // 画面下に到達したらダメージ
                if (this.y > canvas.height + 100) {
                    this.destroy();
                    gameState.lives--;
                    updateUI();
                    if (gameState.lives <= 0) {
                        endGame();
                    }
                }
            }

            draw() {
                const screenX = this.x + Math.sin(this.wobble) * 20;
                const screenY = this.y;
                const size = this.screenSize;

                ctx.save();
                ctx.translate(screenX, screenY);
                ctx.rotate(this.angle);
                
                // 影効果
                ctx.shadowColor = 'rgba(0,0,0,0.5)';
                ctx.shadowBlur = 10;
                ctx.shadowOffsetY = 5;
                
                // 天使の翼（3D風アニメーション）
                ctx.fillStyle = `rgba(240, 248, 255, ${0.8 * this.scale})`;
                ctx.save();
                ctx.rotate(Math.sin(this.wingAngle) * 0.3);
                ctx.beginPath();
                ctx.ellipse(-size * 0.8, -size * 0.2, size * 0.4, size * 0.6, -0.3, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
                
                ctx.save();
                ctx.rotate(-Math.sin(this.wingAngle) * 0.3);
                ctx.beginPath();
                ctx.ellipse(size * 0.8, -size * 0.2, size * 0.4, size * 0.6, 0.3, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
                
                // 天使の体（グラデーション）
                const bodyGradient = ctx.createRadialGradient(0, 0, 0, 0, 0, size * 0.8);
                bodyGradient.addColorStop(0, '#fff8dc');
                bodyGradient.addColorStop(1, '#f0e68c');
                ctx.fillStyle = bodyGradient;
                ctx.beginPath();
                ctx.ellipse(0, 0, size * 0.6, size * 0.8, 0, 0, Math.PI * 2);
                ctx.fill();
                
                // 天使の顔
                const faceGradient = ctx.createRadialGradient(0, -size * 0.3, 0, 0, -size * 0.3, size * 0.4);
                faceGradient.addColorStop(0, '#ffb6c1');
                faceGradient.addColorStop(1, '#ffc0cb');
                ctx.fillStyle = faceGradient;
                ctx.beginPath();
                ctx.arc(0, -size * 0.3, size * 0.4, 0, Math.PI * 2);
                ctx.fill();
                
                // 目（3D風）
                ctx.fillStyle = '#000';
                ctx.beginPath();
                ctx.ellipse(-size * 0.15, -size * 0.4, size * 0.08, size * 0.06, 0, 0, Math.PI * 2);
                ctx.fill();
                ctx.beginPath();
                ctx.ellipse(size * 0.15, -size * 0.4, size * 0.08, size * 0.06, 0, 0, Math.PI * 2);
                ctx.fill();
                
                // 天使の輪（光る効果）
                ctx.strokeStyle = '#ffd700';
                ctx.shadowColor = '#ffd700';
                ctx.shadowBlur = 15;
                ctx.lineWidth = 4;
                ctx.beginPath();
                ctx.arc(0, -size * 0.8, size * 0.3, 0, Math.PI * 2);
                ctx.stroke();
                
                ctx.restore();
            }

            destroy() {
                const index = gameState.enemies.indexOf(this);
                if (index > -1) {
                    gameState.enemies.splice(index, 1);
                }
                createExplosion3D(this.x, this.y);
                hideWordCard();
            }
        }

        // ミサイルクラス（3D風）
        class Missile3D {
            constructor(x, y, number) {
                this.x = x;
                this.y = y;
                this.z = 50;
                this.speed = 10;
                this.number = number;
                this.size = 25;
                this.angle = 0;
                this.trail = [];
            }

            update() {
                this.y -= this.speed;
                this.angle += 0.2;
                this.z += 2;
                
                // トレイル効果
                this.trail.push({x: this.x, y: this.y, life: 20});
                this.trail = this.trail.filter(t => t.life-- > 0);
                
                // 敵との衝突判定
                gameState.enemies.forEach(enemy => {
                    const dx = this.x - enemy.x;
                    const dy = this.y - enemy.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < enemy.screenSize + this.size) {
                        this.destroy();
                        enemy.destroy();
                        gameState.score += 10;
                        updateUI();
                        spawnEnemy();
                    }
                });

                // 画面外で削除
                if (this.y < -50) {
                    this.destroy();
                }
            }

            draw() {
                // トレイル描画
                this.trail.forEach((t, i) => {
                    const alpha = t.life / 20;
                    ctx.fillStyle = `rgba(255, 107, 53, ${alpha * 0.6})`;
                    ctx.beginPath();
                    ctx.arc(t.x, t.y, (this.size * 0.3) * alpha, 0, Math.PI * 2);
                    ctx.fill();
                });

                ctx.save();
                ctx.translate(this.x, this.y);
                ctx.rotate(this.angle);
                
                // 3D効果の影
                ctx.shadowColor = 'rgba(0,0,0,0.6)';
                ctx.shadowBlur = 8;
                ctx.shadowOffsetY = 3;
                
                // ミサイル本体（3D風）
                const gradient = ctx.createRadialGradient(0, 0, 0, 0, 0, this.size);
                gradient.addColorStop(0, '#ff6b6b');
                gradient.addColorStop(0.7, '#ff4757');
                gradient.addColorStop(1, '#c44569');
                ctx.fillStyle = gradient;
                ctx.strokeStyle = '#ffffff';
                ctx.lineWidth = 3;
                
                ctx.beginPath();
                ctx.ellipse(0, 0, this.size, this.size * 1.5, 0, 0, Math.PI * 2);
                ctx.fill();
                ctx.stroke();
                
                // 数字（3D風）
                ctx.shadowColor = 'rgba(0,0,0,0.8)';
                ctx.shadowBlur = 5;
                ctx.fillStyle = '#ffffff';
                ctx.font = `bold ${this.size * 0.8}px Arial`;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(this.number.toString(), 0, 0);
                
                // 炎エフェクト（アニメーション）
                const flameSize = this.size * (0.6 + Math.sin(Date.now() * 0.02) * 0.2);
                const flameGradient = ctx.createRadialGradient(0, this.size * 1.2, 0, 0, this.size * 1.2, flameSize);
                flameGradient.addColorStop(0, '#ff6b35');
                flameGradient.addColorStop(0.5, '#f7931e');
                flameGradient.addColorStop(1, 'rgba(255, 107, 53, 0)');
                ctx.fillStyle = flameGradient;
                ctx.beginPath();
                ctx.ellipse(0, this.size * 1.4, flameSize, flameSize * 1.2, 0, 0, Math.PI * 2);
                ctx.fill();
                
                ctx.restore();
            }

            destroy() {
                const index = gameState.missiles.indexOf(this);
                if (index > -1) {
                    gameState.missiles.splice(index, 1);
                }
            }
        }

        // パーティクルクラス（3D風）
        class Particle3D {
            constructor(x, y) {
                this.x = x;
                this.y = y;
                this.z = Math.random() * 100;
                this.vx = (Math.random() - 0.5) * 12;
                this.vy = (Math.random() - 0.5) * 12;
                this.vz = (Math.random() - 0.5) * 6;
                this.life = 40;
                this.maxLife = 40;
                this.size = Math.random() * 8 + 3;
                this.color = `hsl(${Math.random() * 60 + 10}, 100%, ${50 + Math.random() * 30}%)`;
                this.angle = Math.random() * Math.PI * 2;
                this.spin = (Math.random() - 0.5) * 0.3;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;
                this.z += this.vz;
                this.vx *= 0.96;
                this.vy *= 0.96;
                this.vz *= 0.96;
                this.angle += this.spin;
                this.life--;
            }

            draw() {
                const alpha = this.life / this.maxLife;
                const scale = 200 / (this.z + 200);
                const screenSize = this.size * scale * alpha;
                
                ctx.save();
                ctx.globalAlpha = alpha;
                ctx.translate(this.x, this.y);
                ctx.rotate(this.angle);
                
                // 3D風パーティクル
                const gradient = ctx.createRadialGradient(0, 0, 0, 0, 0, screenSize);
                gradient.addColorStop(0, this.color);
                gradient.addColorStop(1, 'rgba(255,255,255,0)');
                ctx.fillStyle = gradient;
                ctx.beginPath();
                ctx.arc(0, 0, screenSize, 0, Math.PI * 2);
                ctx.fill();
                
                ctx.restore();
            }
        }

        // プレイヤー描画（3D風悪魔）
        function drawPlayer3D() {
            const playerX = canvas.width / 2;
            const playerY = canvas.height - 120;
            const size = 50;
            const time = Date.now() * 0.005;

            ctx.save();
            ctx.translate(playerX, playerY + Math.sin(time) * 5);
            
            // 影効果
            ctx.shadowColor = 'rgba(0,0,0,0.7)';
            ctx.shadowBlur = 15;
            ctx.shadowOffsetY = 8;
            
            // 悪魔の翼（アニメーション）
            const wingFlap = Math.sin(time * 2) * 0.3;
            ctx.fillStyle = 'rgba(44, 44, 44, 0.9)';
            
            ctx.save();
            ctx.rotate(wingFlap);
            ctx.beginPath();
            ctx.ellipse(-size * 1.4, 0, size * 0.8, size * 0.6, -0.5, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
            
            ctx.save();
            ctx.rotate(-wingFlap);
            ctx.beginPath();
            ctx.ellipse(size * 1.4, 0, size * 0.8, size * 0.6, 0.5, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
            
            // 悪魔の体（グラデーション）
            const bodyGradient = ctx.createRadialGradient(0, 0, 0, 0, 0, size);
            bodyGradient.addColorStop(0, '#dc143c');
            bodyGradient.addColorStop(0.7, '#8b0000');
            bodyGradient.addColorStop(1, '#4b0000');
            ctx.fillStyle = bodyGradient;
            ctx.beginPath();
            ctx.ellipse(0, 0, size * 0.8, size * 1.2, 0, 0, Math.PI * 2);
            ctx.fill();
            
            // 悪魔の角
            ctx.fillStyle = '#000';
            ctx.beginPath();
            ctx.moveTo(-size * 0.4, -size * 0.8);
            ctx.lineTo(-size * 0.2, -size * 1.4);
            ctx.lineTo(-size * 0.1, -size * 0.8);
            ctx.fill();
            
            ctx.beginPath();
            ctx.moveTo(size * 0.4, -size * 0.8);
            ctx.lineTo(size * 0.2, -size * 1.4);
            ctx.lineTo(size * 0.1, -size * 0.8);
            ctx.fill();
            
            // 悪魔の顔
            const faceGradient = ctx.createRadialGradient(0, -size * 0.3, 0, 0, -size * 0.3, size * 0.5);
            faceGradient.addColorStop(0, '#dc143c');
            faceGradient.addColorStop(1, '#8b0000');
            ctx.fillStyle = faceGradient;
            ctx.beginPath();
            ctx.arc(0, -size * 0.3, size * 0.5, 0, Math.PI * 2);
            ctx.fill();
            
            // 悪魔の目（光る効果）
            ctx.fillStyle = '#ff0000';
            ctx.shadowColor = '#ff0000';
            ctx.shadowBlur = 10;
            ctx.beginPath();
            ctx.arc(-size * 0.2, -size * 0.4, size * 0.12, 0, Math.PI * 2);
            ctx.fill();
            ctx.beginPath();
            ctx.arc(size * 0.2, -size * 0.4, size * 0.12, 0, Math.PI * 2);
            ctx.fill();
            
            ctx.restore();
        }

        // 3D風背景
        function drawBackground3D() {
            // グラデーション背景
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, '#1a1a2e');
            gradient.addColorStop(0.5, '#16213e');
            gradient.addColorStop(1, '#0f3460');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // 3D風星空
            const time = Date.now() * 0.0005;
            for (let i = 0; i < 200; i++) {
                const x = (i * 137.5 + time * 50) % canvas.width;
                const y = (i * 12.5) % canvas.height;
                const z = (i * 23.7) % 300;
                const scale = 200 / (z + 200);
                const alpha = scale;
                const size = 1 + scale * 2;
                
                ctx.fillStyle = `rgba(255, 255, 255, ${alpha})`;
                ctx.beginPath();
                ctx.arc(x, y, size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        // 爆発エフェクト
        function createExplosion3D(x, y) {
            for (let i = 0; i < 25; i++) {
                gameState.particles.push(new Particle3D(x, y));
            }
        }

        // 敵生成
        function spawnEnemy() {
            if (gameState.currentWordIndex < wordData.length) {
                gameState.enemies.push(new Enemy3D());
            } else {
                setTimeout(() => {
                    alert('🎉 おめでとうございます！全問クリア！');
                    endGame();
                }, 1000);
            }
        }

        // 単語カード表示
        function showWordCard(wordIndex) {
            if (wordIndex >= wordData.length) return;
            
            const data = wordData[wordIndex];
            englishWord.textContent = data.word;
            
            choices.forEach((choice, index) => {
                choice.textContent = `${index + 1}. ${data.choices[index]}`;
                choice.onclick = () => handleChoice(index + 1);
            });
            
            wordCard.style.display = 'block';
        }

        // 単語カード非表示
        function hideWordCard() {
            wordCard.style.display = 'none';
        }

        // 選択肢処理
        function handleChoice(choiceNumber) {
            const currentData = wordData[gameState.currentWordIndex];
            const isCorrect = choiceNumber === currentData.correct;
            
            hideWordCard();
            
            if (isCorrect) {
                showFeedback('正解！', true);
                fireMissile(choiceNumber);
                gameState.currentWordIndex++;
            } else {
                showFeedback('NG', false);
            }
        }

        // フィードバック表示
        function showFeedback(text, isCorrect) {
            feedback.textContent = text;
            feedback.className = isCorrect ? 'correct' : 'incorrect';
            feedback.style.display = 'block';
            
            setTimeout(() => {
                feedback.style.display = 'none';
            }, 1500);
        }

        // ミサイル発射
        function fireMissile(number) {
            const missile = new Missile3D(canvas.width / 2, canvas.height - 120, number);
            gameState.missiles.push(missile);
        }

        // UI更新
        function updateUI() {
            scoreValue.textContent = gameState.score;
            livesValue.textContent = gameState.lives;
        }

        // ゲーム開始
        function startGame() {
            gameState = {
                lives: 3,
                score: 0,
                currentWordIndex: 0,
                gameRunning: true,
                enemies: [],
                missiles: [],
                particles: []
            };
            
            startScreen.style.display = 'none';
            gameOverScreen.style.display = 'none';
            loading.style.display = 'none';
            updateUI();
            spawnEnemy();
            gameLoop();
        }

        // ゲーム終了
        function endGame() {
            gameState.gameRunning = false;
            finalScore.textContent = gameState.score;
            gameOverScreen.style.display = 'block';
            hideWordCard();
            if (animationId) {
                cancelAnimationFrame(animationId);
            }
        }

        // ゲームループ
        function gameLoop() {
            if (!gameState.gameRunning) return;
            
            // 背景描画
            drawBackground3D();
            
            // プレイヤー描画
            drawPlayer3D();
            
            // 敵の更新と描画
            gameState.enemies.forEach((enemy, index) => {
                enemy.update();
                enemy.draw();
            });
            
            // ミサイルの更新と描画
            gameState.missiles.forEach((missile, index) => {
                missile.update();
                missile.draw();
            });
            
            // パーティクルの更新と描画
            gameState.particles = gameState.particles.filter(particle => {
                particle.update();
                particle.draw();
                return particle.life > 0;
            });
            
            animationId = requestAnimationFrame(gameLoop);
        }

        // イベントリスナー
        document.getElementById('startBtn').onclick = () => {
            loading.style.display = 'block';
            setTimeout(startGame, 500); // ローディング演出
        };
        
        document.getElementById('restartBtn').onclick = () => {
            loading.style.display = 'block';
            setTimeout(startGame, 500);
        };

        // キーボード操作
        document.addEventListener('keydown', (e) => {
            if (gameState.gameRunning && wordCard.style.display === 'block') {
                const key = parseInt(e.key);
                if (key >= 1 && key <= 4) {
                    handleChoice(key);
                }
            }
        });

        // ウィンドウリサイズ対応
        window.addEventListener('resize', () => {
            initCanvas();
        });

        // 初期化
        initCanvas();
        updateUI();
    </script>
</body>
</html>