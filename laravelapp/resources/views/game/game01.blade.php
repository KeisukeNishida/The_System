<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スーパーマリオ風ゲーム</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #5C94FC, #87CEEB);
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        canvas {
            display: block;
            border: 2px solid #000;
            background: linear-gradient(to bottom, #87CEEB, #98FB98);
        }

        .ui {
            position: absolute;
            top: 10px;
            left: 10px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            z-index: 10;
        }

        .controls {
            position: absolute;
            bottom: 10px;
            left: 10px;
            color: white;
            font-size: 14px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            z-index: 10;
        }

        .game-over {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 24px;
            display: none;
            z-index: 20;
        }

        button {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #ff5252;
        }
    </style>
</head>
<body>
    <div class="ui">
        <div>スコア: <span id="score">0</span></div>
        <div>ライフ: <span id="lives">3</span></div>
    </div>
    
    <div class="controls">
        操作: ←→で移動、スペースでジャンプ
    </div>

    <div class="game-over" id="gameOver">
        <div>ゲームオーバー</div>
        <div>最終スコア: <span id="finalScore">0</span></div>
        <button onclick="restartGame()">もう一度プレイ</button>
    </div>

    <canvas id="gameCanvas" width="800" height="400"></canvas>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');

        // ゲーム状態
        let gameState = {
            score: 0,
            lives: 3,
            camera: { x: 0, y: 0 },
            gameRunning: true
        };

        // プレイヤー
        const player = {
            x: 100,
            y: 300,
            width: 32,
            height: 32,
            velocityX: 0,
            velocityY: 0,
            speed: 5,
            jumpPower: 15,
            onGround: false,
            color: '#4a0e4e',
            eyeOffset: 0,
            mouthOffset: 0,
            twitchTimer: 0
        };

        // キー入力
        const keys = {};
        document.addEventListener('keydown', (e) => keys[e.code] = true);
        document.addEventListener('keyup', (e) => keys[e.code] = false);

        // プラットフォーム
        const platforms = [
            {x: 0, y: 350, width: 200, height: 50, color: '#8B4513'},
            {x: 250, y: 300, width: 100, height: 20, color: '#8B4513'},
            {x: 400, y: 250, width: 150, height: 20, color: '#8B4513'},
            {x: 600, y: 200, width: 100, height: 20, color: '#8B4513'},
            {x: 750, y: 350, width: 200, height: 50, color: '#8B4513'},
            {x: 1000, y: 300, width: 150, height: 20, color: '#8B4513'},
            {x: 1200, y: 250, width: 100, height: 20, color: '#8B4513'},
            {x: 1350, y: 350, width: 200, height: 50, color: '#8B4513'},
            {x: 1600, y: 200, width: 150, height: 20, color: '#8B4513'},
            {x: 1800, y: 350, width: 200, height: 50, color: '#8B4513'}
        ];

        // 敵
        const enemies = [
            {x: 300, y: 270, width: 24, height: 24, velocityX: -1, color: '#ff4444', alive: true},
            {x: 500, y: 220, width: 24, height: 24, velocityX: -1, color: '#ff4444', alive: true},
            {x: 800, y: 320, width: 24, height: 24, velocityX: -1, color: '#ff4444', alive: true},
            {x: 1100, y: 270, width: 24, height: 24, velocityX: -1, color: '#ff4444', alive: true},
            {x: 1400, y: 320, width: 24, height: 24, velocityX: -1, color: '#ff4444', alive: true},
            {x: 1650, y: 170, width: 24, height: 24, velocityX: -1, color: '#ff4444', alive: true}
        ];

        // コイン
        const coins = [
            {x: 280, y: 260, width: 16, height: 16, collected: false},
            {x: 450, y: 210, width: 16, height: 16, collected: false},
            {x: 650, y: 160, width: 16, height: 16, collected: false},
            {x: 1050, y: 260, width: 16, height: 16, collected: false},
            {x: 1230, y: 210, width: 16, height: 16, collected: false},
            {x: 1650, y: 160, width: 16, height: 16, collected: false}
        ];

        // 衝突検出
        function checkCollision(rect1, rect2) {
            return rect1.x < rect2.x + rect2.width &&
                   rect1.x + rect1.width > rect2.x &&
                   rect1.y < rect2.y + rect2.height &&
                   rect1.y + rect1.height > rect2.y;
        }

        // プレイヤー更新
        function updatePlayer() {
            // カオスな動きのための微調整
            player.twitchTimer += 0.3;
            player.eyeOffset = Math.sin(player.twitchTimer * 3) * 2;
            player.mouthOffset = Math.cos(player.twitchTimer * 2.5) * 1.5;

            // 横移動（少し不安定に）
            if (keys['ArrowLeft'] && player.x > gameState.camera.x) {
                player.velocityX = -player.speed + Math.sin(player.twitchTimer) * 0.5;
            } else if (keys['ArrowRight']) {
                player.velocityX = player.speed + Math.cos(player.twitchTimer) * 0.5;
            } else {
                player.velocityX *= 0.8;
            }

            // ジャンプ（時々異常に高く跳ぶ）
            if (keys['Space'] && player.onGround) {
                const chaosJump = Math.random() < 0.1 ? 1.5 : 1;
                player.velocityY = -player.jumpPower * chaosJump;
                player.onGround = false;
            }

            // 重力
            player.velocityY += 0.8;

            // 位置更新
            player.x += player.velocityX;
            player.y += player.velocityY;

            // プラットフォームとの衝突
            player.onGround = false;
            for (let platform of platforms) {
                if (checkCollision(player, platform)) {
                    if (player.velocityY > 0 && player.y < platform.y) {
                        player.y = platform.y - player.height;
                        player.velocityY = 0;
                        player.onGround = true;
                    }
                }
            }

            // 画面下に落ちた場合
            if (player.y > canvas.height) {
                loseLife();
            }
        }

        // 敵更新
        function updateEnemies() {
            for (let enemy of enemies) {
                if (!enemy.alive) continue;

                enemy.x += enemy.velocityX;

                // プラットフォームの端で向きを変える
                let onPlatform = false;
                for (let platform of platforms) {
                    if (enemy.y + enemy.height >= platform.y && 
                        enemy.y + enemy.height <= platform.y + platform.height + 10 &&
                        enemy.x + enemy.width > platform.x && 
                        enemy.x < platform.x + platform.width) {
                        onPlatform = true;
                        break;
                    }
                }

                if (!onPlatform || enemy.x <= 0) {
                    enemy.velocityX *= -1;
                }
            }
        }

        // カメラ更新
        function updateCamera() {
            const targetX = player.x - canvas.width / 3;
            gameState.camera.x = Math.max(0, targetX);
        }

        // 衝突チェック
        function checkCollisions() {
            // 敵との衝突
            for (let enemy of enemies) {
                if (!enemy.alive) continue;
                
                if (checkCollision(player, enemy)) {
                    // 上から踏んだ場合
                    if (player.velocityY > 0 && player.y < enemy.y) {
                        enemy.alive = false;
                        player.velocityY = -8;
                        gameState.score += 100;
                    } else {
                        loseLife();
                    }
                }
            }

            // コインとの衝突
            for (let coin of coins) {
                if (!coin.collected && checkCollision(player, coin)) {
                    coin.collected = true;
                    gameState.score += 50;
                }
            }
        }

        // ライフを失う
        function loseLife() {
            gameState.lives--;
            if (gameState.lives <= 0) {
                gameOver();
            } else {
                // プレイヤーを開始位置にリセット
                player.x = 100;
                player.y = 300;
                player.velocityX = 0;
                player.velocityY = 0;
                gameState.camera.x = 0;
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
            gameState.score = 0;
            gameState.lives = 3;
            gameState.camera.x = 0;
            gameState.gameRunning = true;
            
            player.x = 100;
            player.y = 300;
            player.velocityX = 0;
            player.velocityY = 0;
            player.twitchTimer = 0;
            player.eyeOffset = 0;
            player.mouthOffset = 0;
            
            // 敵をリセット
            for (let enemy of enemies) {
                enemy.alive = true;
            }
            
            // コインをリセット
            for (let coin of coins) {
                coin.collected = false;
            }
            
            document.getElementById('gameOver').style.display = 'none';
        }

        // 描画関数
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // 背景グラデーション
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, '#87CEEB');
            gradient.addColorStop(1, '#98FB98');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // カメラオフセット適用
            ctx.save();
            ctx.translate(-gameState.camera.x, 0);

            // 雲を描画
            ctx.fillStyle = 'white';
            for (let i = 0; i < 10; i++) {
                const x = i * 300 + 100;
                const y = 50 + Math.sin(i) * 20;
                ctx.beginPath();
                ctx.arc(x, y, 20, 0, Math.PI * 2);
                ctx.arc(x + 25, y, 25, 0, Math.PI * 2);
                ctx.arc(x + 50, y, 20, 0, Math.PI * 2);
                ctx.fill();
            }

            // プラットフォーム描画
            for (let platform of platforms) {
                ctx.fillStyle = platform.color;
                ctx.fillRect(platform.x, platform.y, platform.width, platform.height);
                
                // プラットフォームの縁
                ctx.fillStyle = '#654321';
                ctx.fillRect(platform.x, platform.y, platform.width, 5);
            }

            // コイン描画
            for (let coin of coins) {
                if (!coin.collected) {
                    ctx.fillStyle = '#FFD700';
                    ctx.beginPath();
                    ctx.arc(coin.x + coin.width/2, coin.y + coin.height/2, coin.width/2, 0, Math.PI * 2);
                    ctx.fill();
                    
                    // コインの光沢
                    ctx.fillStyle = '#FFF700';
                    ctx.beginPath();
                    ctx.arc(coin.x + coin.width/2 - 3, coin.y + coin.height/2 - 3, 3, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            // 敵描画
            for (let enemy of enemies) {
                if (enemy.alive) {
                    ctx.fillStyle = enemy.color;
                    ctx.fillRect(enemy.x, enemy.y, enemy.width, enemy.height);
                    
                    // 敵の目
                    ctx.fillStyle = 'white';
                    ctx.fillRect(enemy.x + 4, enemy.y + 4, 4, 4);
                    ctx.fillRect(enemy.x + 16, enemy.y + 4, 4, 4);
                    ctx.fillStyle = 'black';
                    ctx.fillRect(enemy.x + 5, enemy.y + 5, 2, 2);
                    ctx.fillRect(enemy.x + 17, enemy.y + 5, 2, 2);
                }
            }

            // プレイヤー描画（カオスなキャラクター）
            ctx.fillStyle = player.color;
            ctx.fillRect(player.x, player.y, player.width, player.height);
            
            // 狂気的な目（大きさと位置が変わる）
            const eyeSize1 = 6 + Math.sin(player.twitchTimer * 4) * 2;
            const eyeSize2 = 6 + Math.cos(player.twitchTimer * 3) * 2;
            
            ctx.fillStyle = '#ff0000';
            ctx.beginPath();
            ctx.arc(player.x + 8 + player.eyeOffset, player.y + 8, eyeSize1, 0, Math.PI * 2);
            ctx.fill();
            ctx.beginPath();
            ctx.arc(player.x + 24 - player.eyeOffset, player.y + 8, eyeSize2, 0, Math.PI * 2);
            ctx.fill();
            
            // 狂った瞳孔
            ctx.fillStyle = 'black';
            ctx.beginPath();
            ctx.arc(player.x + 8 + player.eyeOffset * 2, player.y + 8, 2, 0, Math.PI * 2);
            ctx.fill();
            ctx.beginPath();
            ctx.arc(player.x + 24 - player.eyeOffset * 2, player.y + 8, 2, 0, Math.PI * 2);
            ctx.fill();
            
            // 不気味な笑顔（歪んだ口）
            ctx.strokeStyle = '#ff0000';
            ctx.lineWidth = 3;
            ctx.beginPath();
            const mouthY = player.y + 20 + player.mouthOffset;
            ctx.moveTo(player.x + 6, mouthY);
            ctx.quadraticCurveTo(player.x + 16, mouthY + 6 + Math.sin(player.twitchTimer * 5) * 3, player.x + 26, mouthY);
            ctx.stroke();
            
            // 不規則な歯
            ctx.fillStyle = 'white';
            for (let i = 0; i < 4; i++) {
                const toothX = player.x + 8 + i * 4 + Math.sin(player.twitchTimer + i) * 1;
                const toothY = mouthY - 2 + Math.cos(player.twitchTimer * 2 + i) * 1;
                ctx.fillRect(toothX, toothY, 2, 4);
            }
            
            // 混沌とした帽子
            ctx.fillStyle = '#000000';
            ctx.fillRect(player.x + 2, player.y - 8, 28, 10);
            
            // 帽子の上の奇妙な装飾
            ctx.fillStyle = '#ff00ff';
            for (let i = 0; i < 3; i++) {
                const spikeX = player.x + 6 + i * 8;
                const spikeHeight = 6 + Math.sin(player.twitchTimer + i) * 3;
                ctx.fillRect(spikeX, player.y - 8 - spikeHeight, 4, spikeHeight);
            }
            
            // ランダムな傷跡
            ctx.strokeStyle = '#8B0000';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(player.x + 4, player.y + 12);
            ctx.lineTo(player.x + 12, player.y + 18);
            ctx.stroke();
            
            // 電気のような効果（時々）
            if (Math.random() < 0.1) {
                ctx.strokeStyle = '#00ffff';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(player.x + Math.random() * 32, player.y + Math.random() * 32);
                ctx.lineTo(player.x + Math.random() * 32, player.y + Math.random() * 32);
                ctx.stroke();
            }

            ctx.restore();

            // UI更新
            document.getElementById('score').textContent = gameState.score;
            document.getElementById('lives').textContent = gameState.lives;
        }

        // ゲームループ
        function gameLoop() {
            if (gameState.gameRunning) {
                updatePlayer();
                updateEnemies();
                updateCamera();
                checkCollisions();
            }
            
            draw();
            requestAnimationFrame(gameLoop);
        }

        // ゲーム開始
        gameLoop();
    </script>
</body>
</html>