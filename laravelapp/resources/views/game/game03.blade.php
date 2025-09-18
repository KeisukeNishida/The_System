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
            border: none;
            background: linear-gradient(to bottom, #87CEEB, #98FB98);
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
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
        操作: ←→で移動、スペースでジャンプ、W/Sで飛行、Xでミサイル攻撃
    </div>

    <div class="game-over" id="gameOver">
        <div>ゲームオーバー</div>
        <div>最終スコア: <span id="finalScore">0</span></div>
        <button onclick="restartGame()">もう一度プレイ</button>
    </div>

    <canvas id="gameCanvas"></canvas>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');

        // 全画面設定
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        // 初期設定と画面サイズ変更時の対応
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

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
            y: window.innerHeight - 100, // 画面サイズに応じて調整
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
            twitchTimer: 0,
            isFlying: false,
            flySpeed: 8,
            wingFlap: 0,
            missileTimer: 0
        };

        // キー入力
        const keys = {};
        document.addEventListener('keydown', (e) => keys[e.code] = true);
        document.addEventListener('keyup', (e) => keys[e.code] = false);

        // プラットフォーム（画面サイズに応じて調整）
        function createPlatforms() {
            const groundY = canvas.height - 50;
            return [
                {x: 0, y: groundY, width: 200, height: 50, color: '#8B4513'},
                {x: 250, y: groundY - 50, width: 100, height: 20, color: '#8B4513'},
                {x: 400, y: groundY - 100, width: 150, height: 20, color: '#8B4513'},
                {x: 600, y: groundY - 150, width: 100, height: 20, color: '#8B4513'},
                {x: 750, y: groundY, width: 200, height: 50, color: '#8B4513'},
                {x: 1000, y: groundY - 50, width: 150, height: 20, color: '#8B4513'},
                {x: 1200, y: groundY - 100, width: 100, height: 20, color: '#8B4513'},
                {x: 1350, y: groundY, width: 200, height: 50, color: '#8B4513'},
                {x: 1600, y: groundY - 150, width: 150, height: 20, color: '#8B4513'},
                {x: 1800, y: groundY, width: 200, height: 50, color: '#8B4513'}
            ];
        }

        let platforms = createPlatforms();

        // 敵（マウンティング女子）- 画面サイズに応じて調整
        function createEnemies() {
            const groundY = canvas.height - 50;
            return [
                {x: 300, y: groundY - 80, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
                {x: 500, y: groundY - 130, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
                {x: 800, y: groundY - 30, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
                {x: 1100, y: groundY - 80, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
                {x: 1400, y: groundY - 30, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0},
                {x: 1650, y: groundY - 180, width: 28, height: 32, velocityX: -6, color: '#ff69b4', alive: true, speechTimer: 0, speech: '', hairBounce: 0, missileTimer: 0}
            ];
        }

        let enemies = createEnemies();

        // 空中敵キャラクター - 画面サイズに応じて調整
       // 空中敵キャラクター（追尾用の homingPower / maxSpeed を追加）
        function createFlyingEnemies() {
            const skyArea = canvas.height * 0.3;
            return [
                {x: 400,  y: skyArea * 0.7, width: 28, height: 24, velocityX: 0, velocityY: 0,
                color: '#ff6b9d', alive: true, wingFlap: 0, attackTimer: 0, eyeBlink: 0,
                homingPower: 0.25, maxSpeed: 5},

                {x: 700,  y: skyArea * 0.5, width: 28, height: 24, velocityX: 0, velocityY: 0,
                color: '#ff6b9d', alive: true, wingFlap: 0, attackTimer: 0, eyeBlink: 0,
                homingPower: 0.25, maxSpeed: 5},

                {x: 1000, y: skyArea * 0.8, width: 28, height: 24, velocityX: 0, velocityY: 0,
                color: '#ff6b9d', alive: true, wingFlap: 0, attackTimer: 0, eyeBlink: 0,
                homingPower: 0.25, maxSpeed: 5},

                {x: 1300, y: skyArea * 0.6, width: 28, height: 24, velocityX: 0, velocityY: 0,
                color: '#ff6b9d', alive: true, wingFlap: 0, attackTimer: 0, eyeBlink: 0,
                homingPower: 0.25, maxSpeed: 5},

                {x: 1600, y: skyArea * 0.7, width: 28, height: 24, velocityX: 0, velocityY: 0,
                color: '#ff6b9d', alive: true, wingFlap: 0, attackTimer: 0, eyeBlink: 0,
                homingPower: 0.25, maxSpeed: 5},
            ];
        }


        let flyingEnemies = createFlyingEnemies();

        // ミサイル配列
        const missiles = [];
        
        // 爆発エフェクト配列
        const explosions = [];

        // プレイヤーのミサイル配列
        const playerMissiles = [];

        // コイン
        const coins = [
            {x: 280, y: 260, width: 16, height: 16, collected: false},
            {x: 450, y: 210, width: 16, height: 16, collected: false},
            {x: 650, y: 160, width: 16, height: 16, collected: false},
            {x: 1050, y: 260, width: 16, height: 16, collected: false},
            {x: 1230, y: 210, width: 16, height: 16, collected: false},
            {x: 1650, y: 160, width: 16, height: 16, collected: false}
        ];

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
            player.wingFlap += 0.4;
            player.missileTimer++;

            // 空中飛行制御
            if (keys['KeyW']) {
                player.isFlying = true;
                player.velocityY = -player.flySpeed;
            } else if (keys['KeyS']) {
                player.isFlying = true;
                player.velocityY = player.flySpeed;
            } else if (player.isFlying) {
                player.velocityY *= 0.9;
            }

            // 横移動
            if (keys['ArrowLeft'] && player.x > gameState.camera.x) {
                player.velocityX = -player.speed + Math.sin(player.twitchTimer) * 0.5;
            } else if (keys['ArrowRight']) {
                player.velocityX = player.speed + Math.cos(player.twitchTimer) * 0.5;
            } else {
                player.velocityX *= 0.8;
            }

            // ジャンプ
            if (keys['Space'] && player.onGround && !player.isFlying) {
                const chaosJump = Math.random() < 0.1 ? 1.5 : 1;
                player.velocityY = -player.jumpPower * chaosJump;
                player.onGround = false;
            }

            // ミサイル攻撃
            if (keys['KeyX'] && player.missileTimer % 15 === 0) {
                firePlayerMissile();
            }

            // 重力
            if (!player.isFlying) {
                player.velocityY += 0.8;
            }

            // 位置更新
            player.x += player.velocityX;
            player.y += player.velocityY;

            // 画面上端制限
            if (player.y < 0) {
                player.y = 0;
                player.velocityY = 0;
            }

            // プラットフォームとの衝突
            if (!player.isFlying) {
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
            }

            // 画面下に落ちた場合
            if (player.y > canvas.height) {
                loseLife();
            }
        }

        // プレイヤーミサイル発射
        function firePlayerMissile() {
            const missile = {
                x: player.x + player.width,
                y: player.y + player.height / 2,
                width: 40,  // 大型化
                height: 16, // 大型化
                velocityX: 15, // 高速化
                velocityY: 0,
                active: true,
                trail: []
            };
            playerMissiles.push(missile);
        }

        // プレイヤーミサイル更新
        function updatePlayerMissiles() {
            for (let i = playerMissiles.length - 1; i >= 0; i--) {
                const missile = playerMissiles[i];
                
                if (!missile.active) {
                    playerMissiles.splice(i, 1);
                    continue;
                }
                
                // トレイル効果（大きく）
                missile.trail.push({x: missile.x, y: missile.y});
                if (missile.trail.length > 8) { // より長いトレイル
                    missile.trail.shift();
                }
                
                // 位置更新
                missile.x += missile.velocityX;
                missile.y += missile.velocityY;
                
                // 画面外で削除
                if (missile.x > gameState.camera.x + canvas.width + 100) {
                    playerMissiles.splice(i, 1);
                    continue;
                }
                
                // 敵との衝突
                for (let enemy of enemies) {
                    if (enemy.alive && checkCollision(missile, enemy)) {
                        enemy.alive = false;
                        playerMissiles.splice(i, 1);
                        gameState.score += 200;
                        createExplosion(enemy.x + enemy.width/2, enemy.y + enemy.height/2, 70); // 大爆発
                        break;
                    }
                }

                // 空中敵との衝突
                for (let flyingEnemy of flyingEnemies) {
                    if (flyingEnemy.alive && checkCollision(missile, flyingEnemy)) {
                        flyingEnemy.alive = false;
                        playerMissiles.splice(i, 1);
                        gameState.score += 150;
                        createExplosion(flyingEnemy.x + flyingEnemy.width/2, flyingEnemy.y + flyingEnemy.height/2, 60); // 大爆発
                        break;
                    }
                }
            }
        }

        // 敵更新
        function updateEnemies() {
            for (let enemy of enemies) {
                if (!enemy.alive) continue;

                enemy.x += enemy.velocityX;
                enemy.hairBounce += 0.2;
                enemy.speechTimer++;
                enemy.missileTimer++;
                
                if (enemy.speechTimer % 180 === 0) {
                    enemy.speech = mountingComments[Math.floor(Math.random() * mountingComments.length)];
                }
                
                if (enemy.speechTimer % 180 === 60) {
                    enemy.speech = '';
                }

                const distanceToPlayer = Math.abs(enemy.x - player.x);
                if (distanceToPlayer < 200) {
                    if (enemy.speechTimer % 120 === 0) {
                        enemy.speech = mountingComments[Math.floor(Math.random() * mountingComments.length)];
                    }
                    
                    if (enemy.missileTimer % 60 === 0) {
                        for (let i = 0; i < 5; i++) {
                            setTimeout(() => fireMissile(enemy), i * 80);
                        }
                    }
                }

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

        // 空中敵更新
       // 空中敵更新（プレイヤー追尾＆接触で爆発→消滅）
function updateFlyingEnemies() {
    for (let flyingEnemy of flyingEnemies) {
        if (!flyingEnemy.alive) continue;

        // アニメ用
        flyingEnemy.wingFlap += 0.3;
        flyingEnemy.attackTimer++;
        flyingEnemy.eyeBlink += 0.1;

        // ===== 追尾（ホーミング）=====
        const ex = flyingEnemy.x + flyingEnemy.width  / 2;
        const ey = flyingEnemy.y + flyingEnemy.height / 2;
        const px = player.x      + player.width       / 2;
        const py = player.y      + player.height      / 2;

        const dx = px - ex;
        const dy = py - ey;
        const dist = Math.hypot(dx, dy) || 1;

        // 加速（追尾力）
        flyingEnemy.velocityX += (dx / dist) * flyingEnemy.homingPower;
        flyingEnemy.velocityY += (dy / dist) * flyingEnemy.homingPower;

        // 速度上限制御
        const sp = Math.hypot(flyingEnemy.velocityX, flyingEnemy.velocityY);
        if (sp > flyingEnemy.maxSpeed) {
            flyingEnemy.velocityX = (flyingEnemy.velocityX / sp) * flyingEnemy.maxSpeed;
            flyingEnemy.velocityY = (flyingEnemy.velocityY / sp) * flyingEnemy.maxSpeed;
        }

        // 位置更新
        flyingEnemy.x += flyingEnemy.velocityX;
        flyingEnemy.y += flyingEnemy.velocityY;

        // 画面外に大きく外れたら右端の空に戻す（ゲーム性維持のため）
        if (flyingEnemy.x < gameState.camera.x - 200 ||
            flyingEnemy.x > gameState.camera.x + canvas.width + 200 ||
            flyingEnemy.y < -200 || flyingEnemy.y > canvas.height + 200) {
            flyingEnemy.x = gameState.camera.x + canvas.width + 150;
            flyingEnemy.y = 80 + Math.random() * (canvas.height * 0.5);
            flyingEnemy.velocityX = 0;
            flyingEnemy.velocityY = 0;
        }

        // ===== プレイヤーに当たったら爆発＆ダメージ＆消滅 =====
        if (checkCollision(flyingEnemy, player)) {
            createExplosion(ex, ey, 70); // 爆発
            flyingEnemy.alive = false;   // 消滅（描画・更新対象から外れる）
            loseLife();                  // ダメージ
        }
    }
}


        // ミサイル発射
        function fireMissile(enemy) {
            const missile = {
                x: enemy.x - 35,
                y: enemy.y + enemy.height / 2,
                width: 32,
                height: 12,
                velocityX: -8,
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
                
                if (explosion.timer < 10) {
                    explosion.size = (explosion.timer / 10) * explosion.maxSize;
                } else {
                    explosion.size = explosion.maxSize * (1 - (explosion.timer - 10) / 20);
                }
                
                for (let j = explosion.particles.length - 1; j >= 0; j--) {
                    const particle = explosion.particles[j];
                    particle.x += particle.velocityX;
                    particle.y += particle.velocityY;
                    particle.velocityY += 0.3;
                    particle.life -= 0.03;
                    
                    if (particle.life <= 0) {
                        explosion.particles.splice(j, 1);
                    }
                }
                
                if (explosion.timer >= explosion.maxTimer) {
                    explosions.splice(i, 1);
                }
            }
        }

        // ミサイル更新
        function updateMissiles() {
            for (let i = missiles.length - 1; i >= 0; i--) {
                const missile = missiles[i];
                
                if (!missile.active) {
                    missiles.splice(i, 1);
                    continue;
                }
                
                // ホーミング
                const dx = player.x + player.width/2 - (missile.x + missile.width/2);
                const dy = player.y + player.height/2 - (missile.y + missile.height/2);
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance > 0) {
                    missile.velocityX += (dx / distance) * missile.homingPower;
                    missile.velocityY += (dy / distance) * missile.homingPower;
                    
                    const currentSpeed = Math.sqrt(missile.velocityX * missile.velocityX + missile.velocityY * missile.velocityY);
                    if (currentSpeed > missile.speed) {
                        missile.velocityX = (missile.velocityX / currentSpeed) * missile.speed;
                        missile.velocityY = (missile.velocityY / currentSpeed) * missile.speed;
                    }
                }
                
                missile.trail.push({x: missile.x, y: missile.y});
                if (missile.trail.length > 8) {
                    missile.trail.shift();
                }
                
                missile.x += missile.velocityX;
                missile.y += missile.velocityY;
                
                missile.rotation = Math.atan2(missile.velocityY, missile.velocityX);
                
                if (missile.x < gameState.camera.x - 100 || 
                    missile.x > gameState.camera.x + canvas.width + 100 ||
                    missile.y < -50 || missile.y > canvas.height + 50) {
                    missiles.splice(i, 1);
                    continue;
                }
                
                if (checkCollision(missile, player)) {
                    createExplosion(missile.x + missile.width/2, missile.y + missile.height/2, 80);
                    missiles.splice(i, 1);
                    loseLife();
                    continue;
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
            for (let enemy of enemies) {
                if (!enemy.alive) continue;
                
                if (checkCollision(player, enemy)) {
                    if (player.velocityY > 0 && player.y < enemy.y) {
                        enemy.alive = false;
                        player.velocityY = -8;
                        gameState.score += 100;
                    } else {
                        loseLife();
                    }
                }
            }

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
                player.x = 100;
                player.y = canvas.height - 100;
                player.velocityX = 0;
                player.velocityY = 0;
                player.isFlying = false;
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
            // 画面サイズに応じて再初期化
            resizeCanvas();
            platforms = createPlatforms();
            enemies = createEnemies();
            flyingEnemies = createFlyingEnemies();
            
            gameState.score = 0;
            gameState.lives = 3;
            gameState.camera.x = 0;
            gameState.gameRunning = true;
            
            player.x = 100;
            player.y = canvas.height - 100;
            player.velocityX = 0;
            player.velocityY = 0;
            player.twitchTimer = 0;
            player.eyeOffset = 0;
            player.mouthOffset = 0;
            player.isFlying = false;
            player.wingFlap = 0;
            player.missileTimer = 0;
            
            for (let enemy of enemies) {
                enemy.alive = true;
                enemy.speechTimer = 0;
                enemy.speech = '';
                enemy.hairBounce = 0;
                enemy.missileTimer = 0;
            }
            
            for (let flyingEnemy of flyingEnemies) {
                flyingEnemy.alive = true;
                flyingEnemy.wingFlap = 0;
                flyingEnemy.attackTimer = 0;
                flyingEnemy.eyeBlink = 0;
                flyingEnemy.velocityX = 0;
                flyingEnemy.velocityY = 0;
            }
            
            for (let coin of coins) {
                coin.collected = false;
            }
            
            missiles.length = 0;
            explosions.length = 0;
            playerMissiles.length = 0;
            
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
                    
                    ctx.fillStyle = '#FFF700';
                    ctx.beginPath();
                    ctx.arc(coin.x + coin.width/2 - 3, coin.y + coin.height/2 - 3, 3, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            // ミサイル描画
            for (let missile of missiles) {
                if (missile.active) {
                    for (let i = 0; i < missile.trail.length; i++) {
                        const trailPoint = missile.trail[i];
                        const alpha = (i + 1) / missile.trail.length * 0.4;
                        const size = 8 - (i * 0.8);
                        ctx.fillStyle = `rgba(150, 150, 150, ${alpha})`;
                        ctx.beginPath();
                        ctx.arc(trailPoint.x + missile.width/2, trailPoint.y + missile.height/2, size, 0, Math.PI * 2);
                        ctx.fill();
                    }
                    
                    ctx.fillStyle = '#2c3e50';
                    ctx.fillRect(missile.x + 4, missile.y + 2, missile.width - 8, missile.height - 4);
                    
                    ctx.fillStyle = '#e74c3c';
                    ctx.beginPath();
                    ctx.moveTo(missile.x, missile.y + missile.height/2);
                    ctx.lineTo(missile.x + 8, missile.y + 2);
                    ctx.lineTo(missile.x + 8, missile.y + missile.height - 2);
                    ctx.closePath();
                    ctx.fill();
                    
                    ctx.fillStyle = '#34495e';
                    ctx.fillRect(missile.x + 8, missile.y + 3, missile.width - 16, 2);
                    ctx.fillRect(missile.x + 8, missile.y + missile.height - 5, missile.width - 16, 2);
                    
                    ctx.fillStyle = '#7f8c8d';
                    
                    ctx.beginPath();
                    ctx.moveTo(missile.x + missile.width - 6, missile.y + missile.height - 2);
                    ctx.lineTo(missile.x + missile.width - 2, missile.y + missile.height + 2);
                    ctx.lineTo(missile.x + missile.width - 10, missile.y + missile.height - 1);
                    ctx.closePath();
                    ctx.fill();
                    
                    ctx.fillStyle = '#3498db';
                    ctx.fillRect(missile.x + missile.width, missile.y + 4, 8, 4);
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(missile.x + missile.width + 2, missile.y + 5, 4, 2);
                    
                    ctx.fillStyle = '#ffaa00';
                    ctx.fillRect(missile.x + missile.width + 4, missile.y + 5, 8, 2);
                    ctx.fillStyle = '#ff6600';
                    ctx.fillRect(missile.x + missile.width + 10, missile.y + 5.5, 6, 1);
                    
                    ctx.fillStyle = '#f1c40f';
                    for (let stripe = 0; stripe < 3; stripe++) {
                        ctx.fillRect(missile.x + 12 + stripe * 4, missile.y + 4, 2, 4);
                    }
                }
            }

            // プレイヤーミサイル描画（大型化）
            for (let missile of playerMissiles) {
                if (missile.active) {
                    // トレイル（より大きく長く）
                    for (let i = 0; i < missile.trail.length; i++) {
                        const trailPoint = missile.trail[i];
                        const alpha = (i + 1) / missile.trail.length * 0.8;
                        const size = 16 - (i * 2);
                        ctx.fillStyle = `rgba(0, 255, 0, ${alpha})`;
                        ctx.fillRect(trailPoint.x, trailPoint.y, size, size/2);
                    }
                    
                    // ミサイル本体（大型リアルデザイン）
                    ctx.fillStyle = '#00aa00';
                    ctx.fillRect(missile.x + 4, missile.y + 2, missile.width - 8, missile.height - 4);
                    
                    // ミサイルの先端（鋭い）
                    ctx.fillStyle = '#00ff00';
                    ctx.beginPath();
                    ctx.moveTo(missile.x + missile.width, missile.y + missile.height/2);
                    ctx.lineTo(missile.x + missile.width - 12, missile.y + 2);
                    ctx.lineTo(missile.x + missile.width - 12, missile.y + missile.height - 2);
                    ctx.closePath();
                    ctx.fill();
                    
                    // ミサイルの胴体ディテール
                    ctx.fillStyle = '#008800';
                    ctx.fillRect(missile.x + 8, missile.y + 4, missile.width - 20, 3);
                    ctx.fillRect(missile.x + 8, missile.y + missile.height - 7, missile.width - 20, 3);
                    
                    // フィン（翼）
                    ctx.fillStyle = '#006600';
                    // 上のフィン
                    ctx.beginPath();
                    ctx.moveTo(missile.x + 8, missile.y + 2);
                    ctx.lineTo(missile.x + 4, missile.y - 4);
                    ctx.lineTo(missile.x + 16, missile.y + 1);
                    ctx.closePath();
                    ctx.fill();
                    
                    // 下のフィン
                    ctx.beginPath();
                    ctx.moveTo(missile.x + 8, missile.y + missile.height - 2);
                    ctx.lineTo(missile.x + 4, missile.y + missile.height + 4);
                    ctx.lineTo(missile.x + 16, missile.y + missile.height - 1);
                    ctx.closePath();
                    ctx.fill();
                    
                    // ロケット噴射（後部の炎）
                    ctx.fillStyle = '#00ffff';
                    ctx.fillRect(missile.x - 12, missile.y + 6, 12, 4);
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(missile.x - 8, missile.y + 7, 6, 2);
                    
                    // 高温ジェット炎（大きく）
                    ctx.fillStyle = '#00ff88';
                    ctx.fillRect(missile.x - 20, missile.y + 7, 12, 2);
                    ctx.fillStyle = '#00aa44';
                    ctx.fillRect(missile.x - 24, missile.y + 7.5, 8, 1);
                    
                    // 警告ストライプ（より多く）
                    ctx.fillStyle = '#ffff00';
                    for (let stripe = 0; stripe < 5; stripe++) {
                        ctx.fillRect(missile.x + 15 + stripe * 5, missile.y + 6, 3, 4);
                    }
                    
                    // エネルギー波動エフェクト
                    if (Math.random() < 0.3) {
                        ctx.fillStyle = 'rgba(0, 255, 255, 0.6)';
                        ctx.beginPath();
                        ctx.arc(missile.x + missile.width/2, missile.y + missile.height/2, 8 + Math.random() * 4, 0, Math.PI * 2);
                        ctx.fill();
                    }
                }
            }

            // 爆発エフェクト描画
            for (let explosion of explosions) {
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
                
                for (let particle of explosion.particles) {
                    const alpha = particle.life;
                    ctx.fillStyle = particle.color.replace(')', `, ${alpha})`).replace('rgb', 'rgba');
                    ctx.beginPath();
                    ctx.arc(particle.x, particle.y, particle.size * particle.life, 0, Math.PI * 2);
                    ctx.fill();
                }
                
                if (explosion.timer < 15) {
                    ctx.strokeStyle = `rgba(255, 255, 255, ${0.5 - explosion.timer/30})`;
                    ctx.lineWidth = 3;
                    ctx.beginPath();
                    ctx.arc(explosion.x, explosion.y, explosion.size * 1.5, 0, Math.PI * 2);
                    ctx.stroke();
                }
            }

            // プレイヤー描画
            // 邪悪な羽
            ctx.fillStyle = '#2c2c2c';
            const wingFlap = Math.sin(player.wingFlap) * 10;
            
            // 左の羽
            ctx.beginPath();
            ctx.moveTo(player.x - 5, player.y + 10);
            ctx.lineTo(player.x - 15 + wingFlap, player.y + 5);
            ctx.lineTo(player.x - 20 + wingFlap, player.y + 15);
            ctx.lineTo(player.x - 10, player.y + 20);
            ctx.closePath();
            ctx.fill();
            
            // 右の羽
            ctx.beginPath();
            ctx.moveTo(player.x + player.width + 5, player.y + 10);
            ctx.lineTo(player.x + player.width + 15 - wingFlap, player.y + 5);
            ctx.lineTo(player.x + player.width + 20 - wingFlap, player.y + 15);
            ctx.lineTo(player.x + player.width + 10, player.y + 20);
            ctx.closePath();
            ctx.fill();
            
            // 羽の邪悪な装飾
            ctx.fillStyle = '#8B0000';
            ctx.fillRect(player.x - 12 + wingFlap/2, player.y + 8, 3, 2);
            ctx.fillRect(player.x + player.width + 9 - wingFlap/2, player.y + 8, 3, 2);

            ctx.fillStyle = player.color;
            ctx.fillRect(player.x, player.y, player.width, player.height);
            
            // 狂気的な目
            const eyeSize1 = 6 + Math.sin(player.twitchTimer * 4) * 2;
            const eyeSize2 = 6 + Math.cos(player.twitchTimer * 3) * 2;
            
            ctx.fillStyle = '#ff0000';
            ctx.beginPath();
            ctx.arc(player.x + 8 + player.eyeOffset, player.y + 8, eyeSize1, 0, Math.PI * 2);
            ctx.fill();
            ctx.beginPath();
            ctx.arc(player.x + 24 - player.eyeOffset, player.y + 8, eyeSize2, 0, Math.PI * 2);
            ctx.fill();
            
            ctx.fillStyle = 'black';
            ctx.beginPath();
            ctx.arc(player.x + 8 + player.eyeOffset * 2, player.y + 8, 2, 0, Math.PI * 2);
            ctx.fill();
            ctx.beginPath();
            ctx.arc(player.x + 24 - player.eyeOffset * 2, player.y + 8, 2, 0, Math.PI * 2);
            ctx.fill();
            
            // 不気味な笑顔
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
            
            // 電気のような効果
            if (Math.random() < 0.1) {
                ctx.strokeStyle = '#00ffff';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(player.x + Math.random() * 32, player.y + Math.random() * 32);
                ctx.lineTo(player.x + Math.random() * 32, player.y + Math.random() * 32);
                ctx.stroke();
            }

            // 敵描画
            for (let enemy of enemies) {
                if (enemy.alive) {
                    ctx.fillStyle = enemy.color;
                    ctx.fillRect(enemy.x, enemy.y + 8, enemy.width, enemy.height - 8);
                    
                    ctx.fillStyle = '#ffdbac';
                    ctx.fillRect(enemy.x + 4, enemy.y, 20, 16);
                    
                    ctx.fillStyle = '#ff1493';
                    const hairOffset = Math.sin(enemy.hairBounce) * 2;
                    ctx.fillRect(enemy.x + 2, enemy.y - 4, 24, 12);
                    ctx.fillRect(enemy.x + 21, enemy.y + 4, 6, 8 + hairOffset);
                    ctx.fillRect(enemy.x + 1, enemy.y + 4, 6, 8 - hairOffset);
                    
                    ctx.fillStyle = 'white';
                    ctx.fillRect(enemy.x + 6, enemy.y + 4, 4, 3);
                    ctx.fillRect(enemy.x + 18, enemy.y + 4, 4, 3);
                    ctx.fillStyle = 'black';
                    ctx.fillRect(enemy.x + 6, enemy.y + 5, 2, 2);
                    ctx.fillRect(enemy.x + 18, enemy.y + 5, 2, 2);
                    
                    ctx.strokeStyle = '#8B0000';
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    ctx.moveTo(enemy.x + 6, enemy.y + 2);
                    ctx.lineTo(enemy.x + 10, enemy.y + 3);
                    ctx.moveTo(enemy.x + 18, enemy.y + 3);
                    ctx.lineTo(enemy.x + 22, enemy.y + 2);
                    ctx.stroke();
                    
                    ctx.strokeStyle = '#ff0000';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.arc(enemy.x + 14, enemy.y + 12, 3, 0, Math.PI);
                    ctx.stroke();
                    
                    // バズーカ
                    ctx.fillStyle = '#2c3e50';
                    ctx.fillRect(enemy.x - 25, enemy.y + 10, 20, 12);
                    
                    ctx.fillStyle = '#34495e';
                    ctx.fillRect(enemy.x - 30, enemy.y + 12, 15, 8);
                    
                    ctx.fillStyle = '#8b4513';
                    ctx.fillRect(enemy.x - 16, enemy.y + 18, 8, 6);
                    
                    ctx.fillStyle = '#e74c3c';
                    ctx.fillRect(enemy.x - 33, enemy.y + 14, 3, 4);
                    
                    // スピーチバブル
                    if (enemy.speech) {
                        const bubbleX = enemy.x - 150;
                        const bubbleY = enemy.y - 20;
                        const bubbleWidth = ctx.measureText(enemy.speech).width + 20;
                        const bubbleHeight = 25;
                        
                        ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
                        ctx.fillRect(bubbleX, bubbleY, bubbleWidth, bubbleHeight);
                        ctx.strokeStyle = '#000';
                        ctx.lineWidth = 1;
                        ctx.strokeRect(bubbleX, bubbleY, bubbleWidth, bubbleHeight);
                        
                        ctx.beginPath();
                        ctx.moveTo(bubbleX + bubbleWidth, bubbleY + 15);
                        ctx.lineTo(bubbleX + bubbleWidth + 8, bubbleY + 10);
                        ctx.lineTo(bubbleX + bubbleWidth, bubbleY + 5);
                        ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
                        ctx.fill();
                        ctx.stroke();
                        
                        ctx.fillStyle = '#000';
                        ctx.font = '12px Arial';
                        ctx.fillText(enemy.speech, bubbleX + 10, bubbleY + 15);
                    }
                }
            }

            // 空中敵描画（可愛いデザイン）
            for (let flyingEnemy of flyingEnemies) {
                if (flyingEnemy.alive) {
                    // 可愛い羽（蝶々のような）
                    ctx.fillStyle = '#ffb3d9';
                    const wingMove = Math.sin(flyingEnemy.wingFlap) * 8;
                    
                    // 左の羽（丸くて可愛い）
                    ctx.beginPath();
                    ctx.ellipse(flyingEnemy.x - 8, flyingEnemy.y + 6 + wingMove, 12, 8, 0, 0, Math.PI * 2);
                    ctx.fill();
                    
                    // 右の羽
                    ctx.beginPath();
                    ctx.ellipse(flyingEnemy.x + flyingEnemy.width + 8, flyingEnemy.y + 6 - wingMove, 12, 8, 0, 0, Math.PI * 2);
                    ctx.fill();
                    
                    // 羽の模様（可愛いハート型）
                    ctx.fillStyle = '#ff69b4';
                    // 左の羽のハート
                    ctx.beginPath();
                    ctx.arc(flyingEnemy.x - 10, flyingEnemy.y + 4 + wingMove, 3, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.beginPath();
                    ctx.arc(flyingEnemy.x - 6, flyingEnemy.y + 4 + wingMove, 3, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.fillRect(flyingEnemy.x - 11, flyingEnemy.y + 6 + wingMove, 7, 3);
                    
                    // 右の羽のハート
                    ctx.beginPath();
                    ctx.arc(flyingEnemy.x + flyingEnemy.width + 6, flyingEnemy.y + 4 - wingMove, 3, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.beginPath();
                    ctx.arc(flyingEnemy.x + flyingEnemy.width + 10, flyingEnemy.y + 4 - wingMove, 3, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.fillRect(flyingEnemy.x + flyingEnemy.width + 5, flyingEnemy.y + 6 - wingMove, 7, 3);
                    
                    // 可愛い丸い本体
                    ctx.fillStyle = flyingEnemy.color;
                    ctx.beginPath();
                    ctx.ellipse(flyingEnemy.x + flyingEnemy.width/2, flyingEnemy.y + flyingEnemy.height/2, flyingEnemy.width/2, flyingEnemy.height/2, 0, 0, Math.PI * 2);
                    ctx.fill();
                    
                    // 大きな赤い目（可愛い）
                    const eyeSize = 6 + Math.sin(flyingEnemy.eyeBlink * 4) * 1;
                    const blinkHeight = Math.sin(flyingEnemy.eyeBlink * 8) > 0.8 ? 2 : eyeSize;
                    
                    // 白い目の部分
                    ctx.fillStyle = '#ffffff';
                    ctx.beginPath();
                    ctx.ellipse(flyingEnemy.x + 6, flyingEnemy.y + 6, eyeSize, blinkHeight, 0, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.beginPath();
                    ctx.ellipse(flyingEnemy.x + flyingEnemy.width - 6, flyingEnemy.y + 6, eyeSize, blinkHeight, 0, 0, Math.PI * 2);
                    ctx.fill();
                    
                    // 赤い瞳
                    if (blinkHeight > 3) {
                        ctx.fillStyle = '#ff0000';
                        ctx.beginPath();
                        ctx.arc(flyingEnemy.x + 6, flyingEnemy.y + 6, 4, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.beginPath();
                        ctx.arc(flyingEnemy.x + flyingEnemy.width - 6, flyingEnemy.y + 6, 4, 0, Math.PI * 2);
                        ctx.fill();
                        
                        // 目のハイライト（キラキラ）
                        ctx.fillStyle = '#ffffff';
                        ctx.beginPath();
                        ctx.arc(flyingEnemy.x + 7, flyingEnemy.y + 5, 2, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.beginPath();
                        ctx.arc(flyingEnemy.x + flyingEnemy.width - 5, flyingEnemy.y + 5, 2, 0, Math.PI * 2);
                        ctx.fill();
                    }
                    
                    // 可愛い小さな口
                    ctx.fillStyle = '#ff1493';
                    ctx.beginPath();
                    ctx.arc(flyingEnemy.x + flyingEnemy.width/2, flyingEnemy.y + 16, 2, 0, Math.PI);
                    ctx.fill();
                    
                    // 頬の赤み（可愛さアップ）
                    ctx.fillStyle = 'rgba(255, 105, 180, 0.5)';
                    ctx.beginPath();
                    ctx.arc(flyingEnemy.x + 2, flyingEnemy.y + 12, 3, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.beginPath();
                    ctx.arc(flyingEnemy.x + flyingEnemy.width - 2, flyingEnemy.y + 12, 3, 0, Math.PI * 2);
                    ctx.fill();
                    
                    // 小さなアンテナ（可愛いアクセント）
                    ctx.strokeStyle = '#ff69b4';
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    ctx.moveTo(flyingEnemy.x + flyingEnemy.width/2, flyingEnemy.y);
                    ctx.lineTo(flyingEnemy.x + flyingEnemy.width/2, flyingEnemy.y - 8);
                    ctx.stroke();
                    
                    ctx.fillStyle = '#ffff00';
                    ctx.beginPath();
                    ctx.arc(flyingEnemy.x + flyingEnemy.width/2, flyingEnemy.y - 8, 2, 0, Math.PI * 2);
                    ctx.fill();
                }
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
                updateFlyingEnemies();
                updateMissiles();
                updatePlayerMissiles();
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