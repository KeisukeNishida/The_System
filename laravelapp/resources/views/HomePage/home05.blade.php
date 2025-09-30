<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D漢字迷路ゲーム</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        
        #gameContainer {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        canvas {
            width: 100%;
            height: 100%;
            display: block;
        }
        
        #ui {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            z-index: 10;
        }
        
        #timer {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        #controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            text-align: center;
            background: rgba(0,0,0,0.5);
            padding: 15px;
            border-radius: 10px;
            z-index: 10;
        }
        
        #answerModal {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            z-index: 20;
        }
        
        #answerModal h2 {
            margin-bottom: 20px;
            color: #333;
        }
        
        #answerInput {
            padding: 10px;
            font-size: 24px;
            border: 2px solid #667eea;
            border-radius: 5px;
            width: 200px;
            text-align: center;
            margin-right: 10px;
        }
        
        #submitAnswer, #nextGame {
            padding: 10px 20px;
            font-size: 18px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            margin: 5px;
        }
        
        #submitAnswer:hover, #nextGame:hover {
            background: #764ba2;
        }
        
        #result {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        
        .correct {
            color: #4caf50;
        }
        
        .incorrect {
            color: #f44336;
        }
        
        #startScreen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 30;
            color: white;
        }
        
        #startScreen h1 {
            margin-bottom: 20px;
            font-size: 48px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        #startScreen p {
            margin-bottom: 30px;
            font-size: 20px;
        }
        
        #startButton {
            padding: 20px 40px;
            font-size: 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        #startButton:hover {
            background: #764ba2;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div id="gameContainer">
        <canvas id="canvas"></canvas>
        
        <div id="ui">
            <div id="timer">時間: 60</div>
            <div>ロボットの視点で漢字の中を探索しよう！</div>
        </div>
        
        <div id="controls">
            <div>移動: W/A/S/D または 矢印キー</div>
            <div>ジャンプ: スペースキー（壁を越えられる高さまで飛べます！）</div>
            <div>視点: マウス移動（上下左右を見回す）</div>
        </div>
        
        <div id="answerModal">
            <h2>何の文字だった？</h2>
            <input type="text" id="answerInput" placeholder="漢字を入力" maxlength="1">
            <button id="submitAnswer">回答</button>
            <div id="result"></div>
            <button id="nextGame" style="display:none;">次のゲーム</button>
        </div>
        
        <div id="startScreen">
            <h1>3D 漢字迷路</h1>
            <p>巨大な漢字の中を探索して、何の文字か当てよう！</p>
            <button id="startButton">ゲームスタート</button>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        class KanjiMazeGame {
            constructor() {
                this.scene = new THREE.Scene();
                this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
                this.renderer = new THREE.WebGLRenderer({ 
                    canvas: document.getElementById('canvas'),
                    antialias: true 
                });
                
                this.isPlaying = false;
                this.timeRemaining = 60;
                this.currentKanji = null;
                this.kanjiData = null;
                
                // Player state
                this.player = {
                    mesh: null,
                    position: new THREE.Vector3(0, 1, 0),
                    velocity: new THREE.Vector3(0, 0, 0),
                    isJumping: false
                };
                
                // Camera settings
                this.cameraAngle = 0;
                this.cameraPitch = 0;  // For looking up/down
                
                // Input state
                this.keys = {};
                this.mouseX = 0;
                this.mouseY = 0;
                
                // Constants
                this.MOVE_SPEED = 0.15;
                this.JUMP_FORCE = 0.5;  // ジャンプ力を増加
                this.GRAVITY = -0.015;  // 重力を調整
                this.WALL_HEIGHT = 8;
                this.MAX_JUMP_HEIGHT = 10;  // 最大ジャンプ高さ
                
                this.walls = [];
                this.clock = new THREE.Clock();
                
                this.init();
            }
            
            init() {
                // Setup renderer
                this.renderer.setSize(window.innerWidth, window.innerHeight);
                this.renderer.shadowMap.enabled = true;
                this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
                this.renderer.setClearColor(0x87CEEB);
                
                // Setup scene
                this.scene.fog = new THREE.Fog(0x87CEEB, 10, 50);
                
                // Lighting
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
                this.scene.add(ambientLight);
                
                const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
                directionalLight.position.set(10, 20, 5);
                directionalLight.castShadow = true;
                directionalLight.shadow.camera.near = 0.1;
                directionalLight.shadow.camera.far = 50;
                directionalLight.shadow.camera.left = -30;
                directionalLight.shadow.camera.right = 30;
                directionalLight.shadow.camera.top = 30;
                directionalLight.shadow.camera.bottom = -30;
                this.scene.add(directionalLight);
                
                // Create ground
                const groundGeometry = new THREE.BoxGeometry(100, 0.2, 100);
                const groundMaterial = new THREE.MeshLambertMaterial({ color: 0x3a7c3a });
                const ground = new THREE.Mesh(groundGeometry, groundMaterial);
                ground.position.y = -0.1;
                ground.receiveShadow = true;
                this.scene.add(ground);
                
                // Grid helper
                const gridHelper = new THREE.GridHelper(100, 50, 0x000000, 0x000000);
                gridHelper.material.opacity = 0.1;
                gridHelper.material.transparent = true;
                this.scene.add(gridHelper);
                
                this.createPlayer();
                this.setupEventListeners();
                
                // Window resize handler
                window.addEventListener('resize', () => {
                    this.camera.aspect = window.innerWidth / window.innerHeight;
                    this.camera.updateProjectionMatrix();
                    this.renderer.setSize(window.innerWidth, window.innerHeight);
                });
            }
            
            createPlayer() {
                // In FPS mode, we don't need to create a visible player model
                // Just set the initial position
                this.player.position = new THREE.Vector3(0, 1.6, 0); // Eye level height
                
                // Create a simple collision box for the player (invisible)
                const playerGeometry = new THREE.BoxGeometry(0.8, 1.8, 0.8);
                const playerMaterial = new THREE.MeshBasicMaterial({ 
                    visible: false 
                });
                const playerMesh = new THREE.Mesh(playerGeometry, playerMaterial);
                playerMesh.position.copy(this.player.position);
                this.scene.add(playerMesh);
                this.player.mesh = playerMesh;
            }
            
            setupKanjiData() {
                // Clear existing walls
                this.walls.forEach(wall => this.scene.remove(wall));
                this.walls = [];
                
                // Define kanji maze patterns
                const kanjiPatterns = [
                    {
                        character: '山',
                        walls: [
                            // Base
                            { x: -10, z: -10, w: 20, d: 1 },
                            // Left peak
                            { x: -10, z: -10, w: 1, d: 10 },
                            { x: -10, z: 0, w: 5, d: 1 },
                            // Center peak (taller)
                            { x: -2.5, z: 0, w: 1, d: 15 },
                            { x: 1.5, z: 0, w: 1, d: 15 },
                            // Right peak
                            { x: 5, z: 0, w: 5, d: 1 },
                            { x: 9, z: -10, w: 1, d: 10 }
                        ],
                        startPos: [0, 1.6, -5]  // 壁から離れた中央の空間
                    },
                    {
                        character: '川',
                        walls: [
                            // Left line
                            { x: -8, z: -12, w: 1, d: 24 },
                            // Center line
                            { x: 0, z: -12, w: 1, d: 24 },
                            // Right line
                            { x: 8, z: -12, w: 1, d: 24 }
                        ],
                        startPos: [-4, 1.6, 0]  // 左と中央の壁の間
                    },
                    {
                        character: '口',
                        walls: [
                            // Top
                            { x: -8, z: 8, w: 16, d: 1 },
                            // Bottom
                            { x: -8, z: -8, w: 16, d: 1 },
                            // Left
                            { x: -8, z: -8, w: 1, d: 16 },
                            // Right
                            { x: 8, z: -8, w: 1, d: 17 }
                        ],
                        startPos: [0, 1.6, 0]  // 中央の安全な空間
                    },
                    {
                        character: '田',
                        walls: [
                            // Outer frame
                            { x: -10, z: 10, w: 20, d: 1 },
                            { x: -10, z: -10, w: 20, d: 1 },
                            { x: -10, z: -10, w: 1, d: 20 },
                            { x: 10, z: -10, w: 1, d: 21 },
                            // Cross lines
                            { x: 0, z: -10, w: 1, d: 20 },
                            { x: -10, z: 0, w: 20, d: 1 }
                        ],
                        startPos: [-5, 1.6, -5]  // 左上の区画の中央
                    }
                ];
                
                // Randomly select a kanji
                const index = Math.floor(Math.random() * kanjiPatterns.length);
                this.kanjiData = kanjiPatterns[index];
                this.currentKanji = this.kanjiData.character;
                
                // Create wall meshes with proper collision boundaries
                const wallMaterial = new THREE.MeshLambertMaterial({ 
                    color: 0x8B4513,
                    side: THREE.DoubleSide 
                });
                
                this.kanjiData.walls.forEach(wallData => {
                    const geometry = new THREE.BoxGeometry(wallData.w, this.WALL_HEIGHT, wallData.d);
                    const wall = new THREE.Mesh(geometry, wallMaterial);
                    wall.position.set(
                        wallData.x + wallData.w / 2,
                        this.WALL_HEIGHT / 2,
                        wallData.z + wallData.d / 2
                    );
                    wall.castShadow = true;
                    wall.receiveShadow = true;
                    this.scene.add(wall);
                    this.walls.push(wall);
                    
                    // Store precise collision data
                    wall.userData = {
                        minX: wallData.x - 0.05,  // Small buffer for precision
                        maxX: wallData.x + wallData.w + 0.05,
                        minZ: wallData.z - 0.05,
                        maxZ: wallData.z + wallData.d + 0.05
                    };
                });
                
                // Set player start position - verify it's safe
                this.player.position.set(...this.kanjiData.startPos);
                
                // 安全確認：スタート位置が壁の中でないか確認
                if (this.checkCollision(this.player.position.x, this.player.position.z)) {
                    console.warn('Start position is inside a wall! Adjusting...');
                    // 壁の外の安全な位置を探す
                    const safePositions = [
                        [0, 1.6, 0],
                        [3, 1.6, 3],
                        [-3, 1.6, -3],
                        [5, 1.6, 0],
                        [-5, 1.6, 0]
                    ];
                    
                    for (const pos of safePositions) {
                        if (!this.checkCollision(pos[0], pos[2])) {
                            this.player.position.set(...pos);
                            console.log('Found safe position:', pos);
                            break;
                        }
                    }
                }
                
                if (this.player.mesh) {
                    this.player.mesh.position.copy(this.player.position);
                }
            }
            
            setupEventListeners() {
                // Start button
                document.getElementById('startButton').addEventListener('click', () => {
                    this.startGame();
                });
                
                // Submit answer
                document.getElementById('submitAnswer').addEventListener('click', () => {
                    this.checkAnswer();
                });
                
                document.getElementById('answerInput').addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        this.checkAnswer();
                    }
                });
                
                // Next game button
                document.getElementById('nextGame').addEventListener('click', () => {
                    document.getElementById('answerModal').style.display = 'none';
                    document.getElementById('nextGame').style.display = 'none';
                    document.getElementById('answerInput').value = '';
                    document.getElementById('result').textContent = '';
                    this.startGame();
                });
                
                // Keyboard controls
                window.addEventListener('keydown', (e) => {
                    this.keys[e.code] = true;
                    
                    if (e.code === 'Space' && !this.player.isJumping) {
                        this.player.isJumping = true;
                        this.player.velocity.y = this.JUMP_FORCE;
                        e.preventDefault();
                    }
                });
                
                window.addEventListener('keyup', (e) => {
                    this.keys[e.code] = false;
                });
                
                // Mouse controls - FPS style look around
                document.addEventListener('mousemove', (e) => {
                    if (this.isPlaying) {
                        const deltaX = e.movementX || 0;
                        const deltaY = e.movementY || 0;
                        
                        // Horizontal rotation (look left/right)
                        this.cameraAngle -= deltaX * 0.003;
                        
                        // Vertical rotation (look up/down)
                        this.cameraPitch -= deltaY * 0.003;
                        this.cameraPitch = Math.max(-Math.PI/3, Math.min(Math.PI/3, this.cameraPitch));
                    }
                });
                
                // Touch controls for mobile
                let touchStartX = 0;
                let touchStartY = 0;
                
                document.addEventListener('touchstart', (e) => {
                    touchStartX = e.touches[0].clientX;
                    touchStartY = e.touches[0].clientY;
                });
                
                document.addEventListener('touchmove', (e) => {
                    if (this.isPlaying && e.touches.length === 1) {
                        const deltaX = e.touches[0].clientX - touchStartX;
                        const deltaY = e.touches[0].clientY - touchStartY;
                        
                        // Look around (FPS style)
                        this.cameraAngle -= deltaX * 0.01;
                        this.cameraPitch -= deltaY * 0.01;
                        this.cameraPitch = Math.max(-Math.PI/3, Math.min(Math.PI/3, this.cameraPitch));
                        
                        touchStartX = e.touches[0].clientX;
                        touchStartY = e.touches[0].clientY;
                    }
                });
            }
            
            startGame() {
                document.getElementById('startScreen').style.display = 'none';
                this.isPlaying = true;
                this.timeRemaining = 60;
                this.setupKanjiData();
                
                // Reset timer display
                document.getElementById('timer').textContent = `時間: ${this.timeRemaining}`;
                
                // Start timer
                if (this.timerInterval) {
                    clearInterval(this.timerInterval);
                }
                
                this.timerInterval = setInterval(() => {
                    this.timeRemaining--;
                    document.getElementById('timer').textContent = `時間: ${this.timeRemaining}`;
                    
                    if (this.timeRemaining <= 0) {
                        this.endGame();
                    }
                }, 1000);
                
                this.animate();
            }
            
            endGame() {
                this.isPlaying = false;
                clearInterval(this.timerInterval);
                document.getElementById('answerModal').style.display = 'block';
                document.getElementById('answerInput').focus();
            }
            
            checkAnswer() {
                const answer = document.getElementById('answerInput').value;
                const resultDiv = document.getElementById('result');
                
                if (answer === this.currentKanji) {
                    resultDiv.textContent = '正解！🎉';
                    resultDiv.className = 'correct';
                } else {
                    resultDiv.textContent = `不正解... 正解は「${this.currentKanji}」でした`;
                    resultDiv.className = 'incorrect';
                }
                
                document.getElementById('nextGame').style.display = 'inline-block';
            }
            
            updatePlayer(deltaTime) {
                if (!this.isPlaying) return;
                
                // Movement
                let moveX = 0, moveZ = 0;
                
                if (this.keys['KeyW'] || this.keys['ArrowUp']) moveZ = -1;
                if (this.keys['KeyS'] || this.keys['ArrowDown']) moveZ = 1;
                if (this.keys['KeyA'] || this.keys['ArrowLeft']) moveX = -1;
                if (this.keys['KeyD'] || this.keys['ArrowRight']) moveX = 1;
                
                // Apply movement relative to camera angle
                const moveAngle = Math.atan2(moveX, moveZ) + this.cameraAngle;
                const moveSpeed = Math.sqrt(moveX * moveX + moveZ * moveZ) * this.MOVE_SPEED;
                
                if (moveSpeed > 0) {
                    const deltaX = Math.sin(moveAngle) * moveSpeed;
                    const deltaZ = Math.cos(moveAngle) * moveSpeed;
                    
                    // 壁の高さを超えている場合は衝突判定をスキップ
                    const isAboveWalls = this.player.position.y > this.WALL_HEIGHT;
                    
                    if (isAboveWalls) {
                        // 壁の上を飛んでいる場合は自由に移動
                        this.player.position.x += deltaX;
                        this.player.position.z += deltaZ;
                    } else {
                        // 通常の衝突判定
                        const newX = this.player.position.x + deltaX;
                        if (!this.checkCollision(newX, this.player.position.z)) {
                            this.player.position.x = newX;
                        }
                        
                        const newZ = this.player.position.z + deltaZ;
                        if (!this.checkCollision(this.player.position.x, newZ)) {
                            this.player.position.z = newZ;
                        }
                    }
                }
                
                // Apply gravity and jumping
                if (this.player.isJumping || this.player.position.y > 1.6) {
                    this.player.velocity.y += this.GRAVITY;
                    this.player.position.y += this.player.velocity.y;
                    
                    // 最大ジャンプ高さの制限
                    if (this.player.position.y > this.MAX_JUMP_HEIGHT) {
                        this.player.position.y = this.MAX_JUMP_HEIGHT;
                        if (this.player.velocity.y > 0) {
                            this.player.velocity.y = 0;
                        }
                    }
                    
                    if (this.player.position.y <= 1.6) {
                        this.player.position.y = 1.6;
                        this.player.velocity.y = 0;
                        this.player.isJumping = false;
                    }
                }
                
                // Update player mesh position
                if (this.player.mesh) {
                    this.player.mesh.position.copy(this.player.position);
                }
            }
            
            checkCollision(x, z) {
                const playerSize = 0.5;
                
                for (const wall of this.walls) {
                    const data = wall.userData;
                    
                    // 壁との衝突判定
                    if (x - playerSize < data.maxX && 
                        x + playerSize > data.minX &&
                        z - playerSize < data.maxZ && 
                        z + playerSize > data.minZ) {
                        return true;
                    }
                }
                return false;
            }
            
            updateCamera() {
                // First-person camera - position at player's eye level
                this.camera.position.copy(this.player.position);
                
                // Calculate look direction based on angles
                const lookX = Math.sin(this.cameraAngle) * Math.cos(this.cameraPitch);
                const lookY = Math.sin(this.cameraPitch);
                const lookZ = Math.cos(this.cameraAngle) * Math.cos(this.cameraPitch);
                
                // Set camera to look in the direction
                const lookTarget = new THREE.Vector3(
                    this.player.position.x + lookX,
                    this.player.position.y + lookY,
                    this.player.position.z + lookZ
                );
                
                this.camera.lookAt(lookTarget);
            }
            
            animate() {
                if (!this.isPlaying) return;
                
                requestAnimationFrame(() => this.animate());
                
                const deltaTime = this.clock.getDelta();
                
                this.updatePlayer(deltaTime);
                this.updateCamera();
                
                this.renderer.render(this.scene, this.camera);
            }
        }
        
        // Initialize game when page loads
        window.addEventListener('load', () => {
            const game = new KanjiMazeGame();
        });
    </script>
</body>
</html>