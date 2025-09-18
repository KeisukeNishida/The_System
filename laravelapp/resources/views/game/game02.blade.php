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

        // 敵（マウンティング女子）
        const enemies = [
            {x: 300, y: 270, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
            {x: 500, y: 220, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
            {x: 800, y: 320, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
            {x: 1100, y: 270, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
            {x: 1400, y: 320, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
            {x: 1650, y: 170, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0}
        ];

        // ミサイル配列
        const missiles = [];
        
        // 爆発エフェクト配列
        const explosions = [];

        // マウント発言集
        const mountingComments = [
            "えー、それだけ？w",
            "私の方が上手だけど？",
            "センスないね〜",
            "まだそんなレベル？",
            "私なら余裕だわ",
            "下手すぎて草",
            "私の足元にも及ばない",
            "努力が足りないんじゃない？",
            "才能ないのかも",
            "私を見習いなよ",
            "レベルが違うわ",
            "もっと頑張れば？",
            "私の方が可愛いし",
            "センスって大事よね〜"
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

        // 敵更新（マウンティング女子）
        function updateEnemies() {
            for (let enemy of enemies) {
                if (!enemy.alive) continue;

                // 高速移動
                enemy.x += enemy.velocityX;
                enemy.hairBounce += 0.2;

                // スピーチタイマー更新
                enemy.speechTimer++;
                enemy.missileTimer++;
                
                // 定期的にマウント発言
                if (enemy.speechTimer % 180 === 0) {
                    enemy.speech = mountingComments[Math.floor(Math.random() * mountingComments.length)];
                }
                
                // 発言を消す
                if (enemy.speechTimer % 180 === 60) {
                    enemy.speech = '';
                }

                // プレイヤーが近くにいるとより頻繁に発言
                const distanceToPlayer = Math.abs(enemy.x - player.x);
                if (distanceToPlayer < 200) {
                    if (enemy.speechTimer % 120 === 0) {
                        enemy.speech = mountingComments[Math.floor(Math.random() * mountingComments.length)];
                    }
                    
                    // ミサイル攻撃 - 1秒ごとに5発連続
                    if (enemy.missileTimer % 60 === 0) { // 1秒 = 60フレーム
                        // 5発連続発射
                        for (let i = 0; i < 5; i++) {
                            setTimeout(() => fireMissile(enemy), i * 80); // 0.08秒間隔で5発
                        }
                    }
                }

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

        // ミサイル発射
        function fireMissile(enemy) {
            const missile = {
                x: enemy.x - 35, // 敵の左側から発射
                y: enemy.y + enemy.height / 2,
                width: 32,
                height: 12,
                velocityX: -8, // 左向きに初期速度
                velocityY: 0,
                active: true,
                trail: [],
                rotation: 0,
                homingPower: 0.15,
                speed: 10
            };
            
            missiles.push(missile);
        }

        // 爆発エフェクト作成
        function createExplosion(x, y, size = 60) {
            const explosion = {
                x: x,
                y: y,
                size: 0,
                maxSize: size,
                particles: [],
                timer: 0,
                maxTimer: 30
            };
            
            // パーティクルを作成
            for (let i = 0; i < 15; i++) {
                explosion.particles.push({
                    x: x,
                    y: y,
                    velocityX: (Math.random() - 0.5) * 10,
                    velocityY: (Math.random() - 0.5) * 10,
                    size: Math.random() * 6 + 2,
                    life: 1.0,
                    color: Math.random() < 0.5 ? '#ff6600' : '#ffaa00'
                });
            }
            
            explosions.push(explosion);
        }

        // 爆発エフェクト更新
        function updateExplosions() {
            for (let i = explosions.length - 1; i >= 0; i--) {
                const explosion = explosions[i];
                explosion.timer++;
                
                // 爆発の大きさを変化
                if (explosion.timer < 10) {
                    explosion.size = (explosion.timer / 10) * explosion.maxSize;
                } else {
                    explosion.size = explosion.maxSize * (1 - (explosion.timer - 10) / 20);
                }
                
                // パーティクル更新
                for (let j = explosion.particles.length - 1; j >= 0; j--) {
                    const particle = explosion.particles[j];
                    particle.x += particle.velocityX;
                    particle.y += particle.velocityY;
                    particle.velocityY += 0.3; // 重力
                    particle.life -= 0.03;
                    
                    if (particle.life <= 0) {
                        explosion.particles.splice(j, 1);
                    }
                }
                
                // 爆発を削除
                if (explosion.timer >= explosion.maxTimer) {
                    explosions.splice(i, 1);
                }
            }
        }

        // ミサイル更新（ホーミング機能付き）
        function updateMissiles() {
            for (let i = missiles.length - 1; i >= 0; i--) {
                const missile = missiles[i];
                
                if (!missile.active) {
                    missiles.splice(i, 1);
                    continue;
                }
                
                // ホーミング（プレイヤーを追尾）
                const dx = player.x + player.width/2 - (missile.x + missile.width/2);
                const dy = player.y + player.height/2 - (missile.y + missile.height/2);
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance > 0) {
                    // 現在の速度に追尾力を加える
                    missile.velocityX += (dx / distance) * missile.homingPower;
                    missile.velocityY += (dy / distance) * missile.homingPower;
                    
                    // 速度を制限
                    const currentSpeed = Math.sqrt(missile.velocityX * missile.velocityX + missile.velocityY * missile.velocityY);
                    if (currentSpeed > missile.speed) {
                        missile.velocityX = (missile.velocityX / currentSpeed) * missile.speed;
                        missile.velocityY = (missile.velocityY / currentSpeed) * missile.speed;
                    }
                }
                
                // トレイル効果（大きくした）
                missile.trail.push({x: missile.x, y: missile.y});
                if (missile.trail.length > 8) {
                    missile.trail.shift();
                }
                
                // 位置更新
                missile.x += missile.velocityX;
                missile.y += missile.velocityY;
                
                // 回転角度を計算（ミサイルが向いている方向）
                missile.rotation = Math.atan2(missile.velocityY, missile.velocityX);
                
                // 画面外で削除
                if (missile.x < gameState.camera.x - 100 || 
                    missile.x > gameState.camera.x + canvas.width + 100 ||
                    missile.y < -50 || missile.y > canvas.height + 50) {
                    missiles.splice(i, 1);
                    continue;
                }
                
                // プレイヤーとの衝突のみチェック（プラットフォームはすり抜け）
                if (checkCollision(missile, player)) {
                    createExplosion(missile.x + missile.width/2, missile.y + missile.height/2, 80);
                    missiles.splice(i, 1);
                    loseLife();
                    continue;
                }
                
                // プラットフォームとの衝突は削除（すり抜け）
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
                enemy.speechTimer = 0;
                enemy.speech = '';
                enemy.hairBounce = 0;
                enemy.missileTimer = 0;
            }
            
            // ミサイルをクリア
            missiles.length = 0;
            
            // 爆発をクリア
            explosions.length = 0;
            
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

            // ミサイル描画（リアルなデザイン・左向き）
            for (let missile of missiles) {
                if (missile.active) {
                    // トレイル（軌跡）- 煙のような効果
                    for (let i = 0; i < missile.trail.length; i++) {
                        const trailPoint = missile.trail[i];
                        const alpha = (i + 1) / missile.trail.length * 0.4;
                        const size = 8 - (i * 0.8);
                        ctx.fillStyle = `rgba(150, 150, 150, ${alpha})`;
                        ctx.beginPath();
                        ctx.arc(trailPoint.x + missile.width/2, trailPoint.y + missile.height/2, size, 0, Math.PI * 2);
                        ctx.fill();
                    }
                    
                    // ミサイル本体（リアルなロケット形状・左向き）
                    ctx.fillStyle = '#2c3e50';
                    ctx.fillRect(missile.x + 4, missile.y + 2, missile.width - 8, missile.height - 4);
                    
                    // ミサイルの先端（円錐形・左向き）
                    ctx.fillStyle = '#e74c3c';
                    ctx.beginPath();
                    ctx.moveTo(missile.x, missile.y + missile.height/2); // 左端の尖った先端
                    ctx.lineTo(missile.x + 8, missile.y + 2);
                    ctx.lineTo(missile.x + 8, missile.y + missile.height - 2);
                    ctx.closePath();
                    ctx.fill();
                    
                    // ミサイルの胴体のディテール
                    ctx.fillStyle = '#34495e';
                    ctx.fillRect(missile.x + 8, missile.y + 3, missile.width - 16, 2);
                    ctx.fillRect(missile.x + 8, missile.y + missile.height - 5, missile.width - 16, 2);
                    
                    // フィン（翼）・左向き
                    ctx.fillStyle = '#7f8c8d';
                    // 上のフィン
                    ctx.beginPath();
                    ctx.moveTo(missile.x + missile.width - 6, missile.y + 2);
                    ctx.lineTo(missile.x + missile.width - 2, missile.y - 2);
                    ctx.lineTo(missile.x + missile.width - 10, missile.y + 1);
                    ctx.closePath();
                    ctx.fill();
                    
                    // 下のフィン
                    ctx.beginPath();
                    ctx.moveTo(missile.x + missile.width - 6, missile.y + missile.height - 2);
                    ctx.lineTo(missile.x + missile.width - 2, missile.y + missile.height + 2);
                    ctx.lineTo(missile.x + missile.width - 10, missile.y + missile.height - 1);
                    ctx.closePath();
                    ctx.fill();
                    
                    // ロケット噴射（後部の炎・右側）
                    ctx.fillStyle = '#3498db';
                    ctx.fillRect(missile.x + missile.width, missile.y + 4, 8, 4);
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(missile.x + missile.width + 2, missile.y + 5, 4, 2);
                    
                    // 高温ジェット炎
                    ctx.fillStyle = '#ffaa00';
                    ctx.fillRect(missile.x + missile.width + 4, missile.y + 5, 8, 2);
                    ctx.fillStyle = '#ff6600';
                    ctx.fillRect(missile.x + missile.width + 10, missile.y + 5.5, 6, 1);
                    
                    // 警告ストライプ
                    ctx.fillStyle = '#f1c40f';
                    for (let stripe = 0; stripe < 3; stripe++) {
                        ctx.fillRect(missile.x + 12 + stripe * 4, missile.y + 4, 2, 4);
                    }
                }
            }

            // 爆発エフェクト描画
            for (let explosion of explosions) {
                // メインの爆発
                const gradient = ctx.createRadialGradient(
                    explosion.x, explosion.y, 0,
                    explosion.x, explosion.y, explosion.size
                );
                gradient.addColorStop(0, 'rgba(255, 255, 255, 0.8)');
                gradient.addColorStop(0.3, 'rgba(255, 200, 0, 0.6)');
                gradient.addColorStop(0.7, 'rgba(255, 100, 0, 0.4)');
                gradient.addColorStop(1, 'rgba(255, 0, 0, 0)');
                
                ctx.fillStyle = gradient;
                ctx.beginPath();
                ctx.arc(explosion.x, explosion.y, explosion.size, 0, Math.PI * 2);
                ctx.fill();
                
                // パーティクル
                for (let particle of explosion.particles) {
                    const alpha = particle.life;
                    ctx.fillStyle = particle.color.replace(')', `, ${alpha})`).replace('rgb', 'rgba');
                    ctx.beginPath();
                    ctx.arc(particle.x, particle.y, particle.size * particle.life, 0, Math.PI * 2);
                    ctx.fill();
                }
                
                // 衝撃波
                if (explosion.timer < 15) {
                    ctx.strokeStyle = `rgba(255, 255, 255, ${0.5 - explosion.timer/30})`;
                    ctx.lineWidth = 3;
                    ctx.beginPath();
                    ctx.arc(explosion.x, explosion.y, explosion.size * 1.5, 0, Math.PI * 2);
                    ctx.stroke();
                }
            }

            // 敵描画（マウンティング女子）
            for (let enemy of enemies) {
                if (enemy.alive) {
                    // 体
                    ctx.fillStyle = enemy.color;
                    ctx.fillRect(enemy.x, enemy.y + 8, enemy.width, enemy.height - 8);
                    
                    // 頭
                    ctx.fillStyle = '#ffdbac';
                    ctx.fillRect(enemy.x + 4, enemy.y, 20, 16);
                    
                    // 髪（ピンク、揺れる）- 左向き
                    ctx.fillStyle = '#ff1493';
                    const hairOffset = Math.sin(enemy.hairBounce) * 2;
                    ctx.fillRect(enemy.x + 2, enemy.y - 4, 24, 12);
                    ctx.fillRect(enemy.x + 21, enemy.y + 4, 6, 8 + hairOffset); // 右側の髪
                    ctx.fillRect(enemy.x + 1, enemy.y + 4, 6, 8 - hairOffset);  // 左側の髪
                    
                    // 目（きつい目つき）- 左向き
                    ctx.fillStyle = 'white';
                    ctx.fillRect(enemy.x + 6, enemy.y + 4, 4, 3);
                    ctx.fillRect(enemy.x + 18, enemy.y + 4, 4, 3);
                    ctx.fillStyle = 'black';
                    ctx.fillRect(enemy.x + 6, enemy.y + 5, 2, 2);  // 左向きの瞳
                    ctx.fillRect(enemy.x + 18, enemy.y + 5, 2, 2); // 左向きの瞳
                    
                    // 眉毛（怒った表情）- 左向き
                    ctx.strokeStyle = '#8B0000';
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    ctx.moveTo(enemy.x + 6, enemy.y + 2);
                    ctx.lineTo(enemy.x + 10, enemy.y + 3);  // 左眉を調整
                    ctx.moveTo(enemy.x + 18, enemy.y + 3);
                    ctx.lineTo(enemy.x + 22, enemy.y + 2); // 右眉を調整
                    ctx.stroke();
                    
                    // 口（嫌な笑み）
                    ctx.strokeStyle = '#ff0000';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.arc(enemy.x + 14, enemy.y + 12, 3, 0, Math.PI);
                    ctx.stroke();
                    
                    // 武器（バズーカ）- 左向き
                    ctx.fillStyle = '#2c3e50';
                    ctx.fillRect(enemy.x - 25, enemy.y + 10, 20, 12); // 左側に配置
                    
                    // バズーカの筒 - 左向き
                    ctx.fillStyle = '#34495e';
                    ctx.fillRect(enemy.x - 30, enemy.y + 12, 15, 8); // 左側に配置
                    
                    // バズーカのグリップ - 左向き
                    ctx.fillStyle = '#8b4513';
                    ctx.fillRect(enemy.x - 16, enemy.y + 18, 8, 6); // 左側に配置
                    
                    // バズーカの照準 - 左向き
                    ctx.fillStyle = '#e74c3c';
                    ctx.fillRect(enemy.x - 33, enemy.y + 14, 3, 4); // 左側に配置
                    
                    // スピーチバブル描画
                    if (enemy.speech) {
                        const bubbleX = enemy.x - 150; // 左側に配置
                        const bubbleY = enemy.y - 20;
                        const bubbleWidth = ctx.measureText(enemy.speech).width + 20;
                        const bubbleHeight = 25;
                        
                        // 吹き出し背景
                        ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
                        ctx.fillRect(bubbleX, bubbleY, bubbleWidth, bubbleHeight);
                        ctx.strokeStyle = '#000';
                        ctx.lineWidth = 1;
                        ctx.strokeRect(bubbleX, bubbleY, bubbleWidth, bubbleHeight);
                        
                        // 吹き出しの尻尾 - 左向き用
                        ctx.beginPath();
                        ctx.moveTo(bubbleX + bubbleWidth, bubbleY + 15);
                        ctx.lineTo(bubbleX + bubbleWidth + 8, bubbleY + 10);
                        ctx.lineTo(bubbleX + bubbleWidth, bubbleY + 5);
                        ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
                        ctx.fill();
                        ctx.stroke();
                        
                        // テキスト
                        ctx.fillStyle = '#000';
                        ctx.font = '12px Arial';
                        ctx.fillText(enemy.speech, bubbleX + 10, bubbleY + 15);
                    }
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
                updateMissiles();
                updateExplosions();
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