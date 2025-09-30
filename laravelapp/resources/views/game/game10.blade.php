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
        
        /* モバイル用コントロール */
        #mobileControls {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            display: none;
            z-index: 15;
            user-select: none;
            -webkit-user-select: none;
        }
        
        .control-group {
            position: absolute;
        }
        
        /* 移動ボタン */
        #moveButtons {
            left: 20px;
            bottom: 20px;
        }
        
        .move-btn {
            position: absolute;
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.3);
            border: 2px solid white;
            border-radius: 10px;
            color: white;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            touch-action: none;
        }
        
        .move-btn:active {
            background: rgba(255,255,255,0.5);
        }
        
        #btnUp { top: 0; left: 50px; }
        #btnDown { top: 100px; left: 50px; }
        #btnLeft { top: 50px; left: 0; }
        #btnRight { top: 50px; left: 100px; }
        
        /* ジャンプボタン */
        #jumpButton {
            right: 20px;
            bottom: 20px;
        }
        
        .jump-btn {
            width: 80px;
            height: 80px;
            background: rgba(102, 126, 234, 0.5);
            border: 3px solid white;
            border-radius: 50%;
            color: white;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            touch-action: none;
        }
        
        .jump-btn:active {
            background: rgba(102, 126, 234, 0.8);
            transform: scale(0.95);
        }
        
        /* 視点操作 */
        #viewControls {
            right: 120px;
            bottom: 40px;
        }
        
        .view-btn {
            position: absolute;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            border-radius: 8px;
            color: white;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            touch-action: none;
        }
        
        .view-btn:active {
            background: rgba(255,255,255,0.4);
        }
        
        #viewUp { top: 0; left: 40px; }
        #viewDown { top: 80px; left: 40px; }
        #viewLeft { top: 40px; left: 0; }
        #viewRight { top: 40px; left: 80px; }
        
        /* タッチデバイス判定で表示 */
        @media (hover: none) and (pointer: coarse) {
            #mobileControls {
                display: block;
            }
            
            #controls {
                display: none;
            }
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
            <div>レベル: <span id="level">1</span> | スコア: <span id="score">0</span></div>
            <div>ロボットの視点で漢字の中を探索しよう！</div>
        </div>
        
        <div id="controls">
            <div>移動: W/A/S/D または 矢印キー</div>
            <div>ジャンプ: スペースキー（壁を越えられる高さまで飛べます！）</div>
            <div>視点: マウス移動（上下左右を見回す）</div>
        </div>
        
        <!-- モバイル用コントロール -->
        <div id="mobileControls">
            <!-- 移動ボタン -->
            <div id="moveButtons" class="control-group">
                <div id="btnUp" class="move-btn">↑</div>
                <div id="btnDown" class="move-btn">↓</div>
                <div id="btnLeft" class="move-btn">←</div>
                <div id="btnRight" class="move-btn">→</div>
            </div>
            
            <!-- ジャンプボタン -->
            <div id="jumpButton" class="control-group">
                <div class="jump-btn">ジャンプ</div>
            </div>
            
            <!-- 視点操作 -->
            <div id="viewControls" class="control-group">
                <div id="viewUp" class="view-btn">▲</div>
                <div id="viewDown" class="view-btn">▼</div>
                <div id="viewLeft" class="view-btn">◀</div>
                <div id="viewRight" class="view-btn">▶</div>
            </div>
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
                this.currentLevel = 1;
                this.score = 0;
                
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
                this.mobileInput = {
                    move: { x: 0, z: 0 },
                    view: { x: 0, y: 0 },
                    jump: false
                };
                
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
                
                // Define kanji maze patterns by level
                const kanjiLevels = {
                    1: [ // レベル1：シンプルな漢字
                        {
                            character: '上',
                            walls: [
                                // 横線
                                { x: -8, z: 0, w: 16, d: 1 },
                                // 縦線
                                { x: -0.5, z: -8, w: 1, d: 16 },
                                // 上の短い横線
                                { x: -4, z: 4, w: 8, d: 1 }
                            ],
                            startPos: [4, 1.6, -4]
                        },
                        {
                            character: '下',
                            walls: [
                                // 横線
                                { x: -8, z: 0, w: 16, d: 1 },
                                // 縦線
                                { x: -0.5, z: -8, w: 1, d: 8 },
                                // 下の点
                                { x: -1, z: -4, w: 2, d: 1 }
                            ],
                            startPos: [4, 1.6, 4]
                        },
                        {
                            character: '川',
                            walls: [
                                { x: -8, z: -10, w: 1, d: 20 },
                                { x: 0, z: -10, w: 1, d: 20 },
                                { x: 8, z: -10, w: 1, d: 20 }
                            ],
                            startPos: [-4, 1.6, 0]
                        },
                        {
                            character: '山',
                            walls: [
                                { x: -10, z: -10, w: 20, d: 1 },
                                { x: -10, z: -10, w: 1, d: 10 },
                                { x: -10, z: 0, w: 5, d: 1 },
                                { x: -2.5, z: 0, w: 1, d: 15 },
                                { x: 1.5, z: 0, w: 1, d: 15 },
                                { x: 5, z: 0, w: 5, d: 1 },
                                { x: 9, z: -10, w: 1, d: 10 }
                            ],
                            startPos: [0, 1.6, -5]
                        },
                        {
                            character: '大',
                            walls: [
                                // 横線
                                { x: -10, z: 0, w: 20, d: 1 },
                                // 縦線
                                { x: -0.5, z: -8, w: 1, d: 16 },
                                // 左払い
                                { x: -8, z: -8, w: 1, d: 1 },
                                { x: -6, z: -6, w: 1, d: 1 },
                                { x: -4, z: -4, w: 1, d: 1 },
                                { x: -2, z: -2, w: 1, d: 1 },
                                // 右払い
                                { x: 8, z: -8, w: 1, d: 1 },
                                { x: 6, z: -6, w: 1, d: 1 },
                                { x: 4, z: -4, w: 1, d: 1 },
                                { x: 2, z: -2, w: 1, d: 1 }
                            ],
                            startPos: [0, 1.6, 4]
                        },
                        {
                            character: '犬',
                            walls: [
                                // 大の形
                                { x: -10, z: 0, w: 20, d: 1 },
                                { x: -0.5, z: -8, w: 1, d: 14 },
                                { x: -8, z: -8, w: 1, d: 1 },
                                { x: -6, z: -6, w: 1, d: 1 },
                                { x: -4, z: -4, w: 1, d: 1 },
                                { x: -2, z: -2, w: 1, d: 1 },
                                { x: 8, z: -8, w: 1, d: 1 },
                                { x: 6, z: -6, w: 1, d: 1 },
                                { x: 4, z: -4, w: 1, d: 1 },
                                { x: 2, z: -2, w: 1, d: 1 },
                                // 点
                                { x: 3, z: 3, w: 2, d: 2 }
                            ],
                            startPos: [-5, 1.6, -5]
                        },
                        {
                            character: '田',
                            walls: [
                                { x: -10, z: 10, w: 20, d: 1 },
                                { x: -10, z: -10, w: 20, d: 1 },
                                { x: -10, z: -10, w: 1, d: 20 },
                                { x: 10, z: -10, w: 1, d: 21 },
                                { x: 0, z: -10, w: 1, d: 20 },
                                { x: -10, z: 0, w: 20, d: 1 }
                            ],
                            startPos: [-5, 1.6, -5]
                        }
                    ],
                    2: [ // レベル2：中程度の複雑さ
                        {
                            character: '生',
                            walls: [
                                // 上の横線
                                { x: -6, z: 6, w: 12, d: 1 },
                                // 中央縦線
                                { x: -0.5, z: -10, w: 1, d: 20 },
                                // 中の横線1
                                { x: -8, z: 2, w: 16, d: 1 },
                                // 中の横線2
                                { x: -8, z: -2, w: 16, d: 1 },
                                // 下の横線
                                { x: -10, z: -8, w: 20, d: 1 }
                            ],
                            startPos: [4, 1.6, -5]
                        },
                        {
                            character: '石',
                            walls: [
                                // 上の横線
                                { x: -10, z: 6, w: 20, d: 1 },
                                // 左の口の形
                                { x: -8, z: -8, w: 1, d: 14 },
                                { x: -8, z: -8, w: 7, d: 1 },
                                { x: -1, z: -8, w: 1, d: 14 },
                                // 右の部分
                                { x: 2, z: -2, w: 1, d: 8 },
                                { x: 2, z: -2, w: 6, d: 1 }
                            ],
                            startPos: [5, 1.6, 0]
                        },
                        {
                            character: '行',
                            walls: [
                                // 左側の部分
                                { x: -10, z: 6, w: 4, d: 1 },
                                { x: -8, z: -8, w: 1, d: 14 },
                                { x: -8, z: 0, w: 3, d: 1 },
                                { x: -8, z: -8, w: 3, d: 1 },
                                // 右側の縦線
                                { x: 2, z: -10, w: 1, d: 20 },
                                { x: 2, z: 2, w: 6, d: 1 },
                                { x: 2, z: -4, w: 6, d: 1 }
                            ],
                            startPos: [-2, 1.6, -2]
                        },
                        {
                            character: '米',
                            walls: [
                                // 上の点
                                { x: -1, z: 8, w: 2, d: 1 },
                                // 十字
                                { x: -10, z: 0, w: 20, d: 1 },
                                { x: -0.5, z: -8, w: 1, d: 16 },
                                // 斜め線（左上から右下）
                                { x: -6, z: 6, w: 1, d: 1 },
                                { x: -3, z: 3, w: 1, d: 1 },
                                { x: 3, z: -3, w: 1, d: 1 },
                                { x: 6, z: -6, w: 1, d: 1 },
                                // 斜め線（右上から左下）
                                { x: 6, z: 6, w: 1, d: 1 },
                                { x: 3, z: 3, w: 1, d: 1 },
                                { x: -3, z: -3, w: 1, d: 1 },
                                { x: -6, z: -6, w: 1, d: 1 }
                            ],
                            startPos: [0, 1.6, -4]
                        },
                        {
                            character: '左',
                            walls: [
                                // 上の横線
                                { x: -8, z: 6, w: 6, d: 1 },
                                // 左側縦線
                                { x: -6, z: -2, w: 1, d: 8 },
                                // 工の形
                                { x: -10, z: 0, w: 20, d: 1 },
                                { x: 0, z: -4, w: 1, d: 4 },
                                { x: -4, z: -8, w: 8, d: 1 }
                            ],
                            startPos: [5, 1.6, -5]
                        },
                        {
                            character: '右',
                            walls: [
                                // 上の横線
                                { x: -8, z: 6, w: 6, d: 1 },
                                // 右側縦線
                                { x: -6, z: -2, w: 1, d: 8 },
                                // 口の形
                                { x: -4, z: 2, w: 8, d: 1 },
                                { x: -4, z: -6, w: 8, d: 1 },
                                { x: -4, z: -6, w: 1, d: 8 },
                                { x: 4, z: -6, w: 1, d: 8 }
                            ],
                            startPos: [0, 1.6, -2]
                        }
                    ],
                    3: [ // レベル3：複雑な漢字
                        {
                            character: '戦',
                            walls: [
                                // 左側の単
                                { x: -10, z: 8, w: 6, d: 1 },
                                { x: -10, z: 4, w: 6, d: 1 },
                                { x: -8, z: -1, w: 1, d: 9 },
                                { x: -6, z: -1, w: 1, d: 9 },
                                { x: -10, z: 0, w: 6, d: 1 },
                                { x: -10, z: -4, w: 6, d: 1 },
                                { x: -10, z: -8, w: 6, d: 1 },
                                // 右側の戈
                                { x: 0, z: 6, w: 8, d: 1 },
                                { x: 2, z: -8, w: 1, d: 14 },
                                { x: 2, z: 0, w: 6, d: 1 },
                                { x: 4, z: -6, w: 1, d: 1 },
                                { x: 6, z: -8, w: 1, d: 1 }
                            ],
                            startPos: [-2, 1.6, -2]
                        },
                        {
                            character: '愛',
                            walls: [
                                // 上部
                                { x: -6, z: 10, w: 4, d: 1 },
                                { x: 2, z: 10, w: 4, d: 1 },
                                { x: -10, z: 6, w: 20, d: 1 },
                                // 中央の心
                                { x: -2, z: 2, w: 1, d: 1 },
                                { x: -4, z: 0, w: 1, d: 1 },
                                { x: -6, z: -2, w: 1, d: 1 },
                                { x: 2, z: 2, w: 1, d: 1 },
                                { x: 4, z: 0, w: 1, d: 1 },
                                { x: 6, z: -2, w: 1, d: 1 },
                                { x: 0, z: -4, w: 1, d: 2 },
                                // 下部の夂
                                { x: -8, z: -6, w: 16, d: 1 },
                                { x: -4, z: -10, w: 1, d: 4 },
                                { x: 4, z: -10, w: 1, d: 4 }
                            ],
                            startPos: [0, 1.6, 8]
                        },
                        {
                            character: '楽',
                            walls: [
                                // 上の白の形
                                { x: -6, z: 8, w: 12, d: 1 },
                                { x: -6, z: 4, w: 1, d: 4 },
                                { x: 6, z: 4, w: 1, d: 4 },
                                { x: -6, z: 2, w: 12, d: 1 },
                                // 中央の八
                                { x: -4, z: -2, w: 1, d: 1 },
                                { x: -2, z: -3, w: 1, d: 1 },
                                { x: 4, z: -2, w: 1, d: 1 },
                                { x: 2, z: -3, w: 1, d: 1 },
                                // 下の木
                                { x: -0.5, z: -8, w: 1, d: 6 },
                                { x: -8, z: -6, w: 16, d: 1 },
                                { x: -4, z: -10, w: 1, d: 1 },
                                { x: 4, z: -10, w: 1, d: 1 }
                            ],
                            startPos: [0, 1.6, 0]
                        },
                        {
                            character: '命',
                            walls: [
                                // 上の人
                                { x: -4, z: 8, w: 1, d: 1 },
                                { x: -2, z: 6, w: 1, d: 1 },
                                { x: 0, z: 4, w: 1, d: 1 },
                                { x: 2, z: 6, w: 1, d: 1 },
                                { x: 4, z: 8, w: 1, d: 1 },
                                // 一
                                { x: -8, z: 2, w: 16, d: 1 },
                                // 叩の形
                                { x: -6, z: -2, w: 1, d: 6 },
                                { x: -6, z: -2, w: 4, d: 1 },
                                { x: -6, z: -8, w: 4, d: 1 },
                                { x: -2, z: -8, w: 1, d: 6 },
                                { x: 2, z: -2, w: 1, d: 6 },
                                { x: 2, z: -2, w: 4, d: 1 },
                                { x: 2, z: -5, w: 4, d: 1 },
                                { x: 2, z: -8, w: 4, d: 1 },
                                { x: 6, z: -8, w: 1, d: 6 }
                            ],
                            startPos: [0, 1.6, -5]
                        },
                        {
                            character: '畑',
                            walls: [
                                // 左の火
                                { x: -10, z: 4, w: 1, d: 1 },
                                { x: -8, z: 2, w: 1, d: 1 },
                                { x: -6, z: 0, w: 1, d: 1 },
                                { x: -8, z: -2, w: 1, d: 1 },
                                { x: -10, z: -4, w: 1, d: 1 },
                                { x: -4, z: 4, w: 1, d: 1 },
                                { x: -2, z: 2, w: 1, d: 1 },
                                { x: -2, z: -2, w: 1, d: 1 },
                                { x: -4, z: -4, w: 1, d: 1 },
                                // 右の田
                                { x: 2, z: 8, w: 8, d: 1 },
                                { x: 2, z: -8, w: 8, d: 1 },
                                { x: 2, z: -8, w: 1, d: 16 },
                                { x: 10, z: -8, w: 1, d: 16 },
                                { x: 6, z: -8, w: 1, d: 16 },
                                { x: 2, z: 0, w: 8, d: 1 }
                            ],
                            startPos: [-6, 1.6, -1]
                        }
                    ]
                };
                
                // Select level based on score
                if (this.score >= 5) this.currentLevel = 3;
                else if (this.score >= 2) this.currentLevel = 2;
                else this.currentLevel = 1;
                
                // Randomly select a kanji from current level
                const levelKanjis = kanjiLevels[this.currentLevel];
                const index = Math.floor(Math.random() * levelKanjis.length);
                this.kanjiData = levelKanjis[index];
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
                
                // Mobile controls setup
                this.setupMobileControls();
                
                // Check if mobile and show controls
                if ('ontouchstart' in window) {
                    document.getElementById('mobileControls').style.display = 'block';
                    document.getElementById('controls').style.display = 'none';
                }
            }
            
            setupMobileControls() {
                // Movement buttons
                const moveButtons = {
                    'btnUp': { x: 0, z: -1 },
                    'btnDown': { x: 0, z: 1 },
                    'btnLeft': { x: -1, z: 0 },
                    'btnRight': { x: 1, z: 0 }
                };
                
                for (const [id, dir] of Object.entries(moveButtons)) {
                    const btn = document.getElementById(id);
                    if (!btn) continue;
                    
                    btn.addEventListener('touchstart', (e) => {
                        e.preventDefault();
                        this.mobileInput.move = dir;
                    });
                    
                    btn.addEventListener('touchend', (e) => {
                        e.preventDefault();
                        this.mobileInput.move = { x: 0, z: 0 };
                    });
                    
                    // Mouse support for testing
                    btn.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        this.mobileInput.move = dir;
                    });
                    
                    btn.addEventListener('mouseup', (e) => {
                        e.preventDefault();
                        this.mobileInput.move = { x: 0, z: 0 };
                    });
                }
                
                // Jump button
                const jumpBtn = document.querySelector('.jump-btn');
                if (jumpBtn) {
                    jumpBtn.addEventListener('touchstart', (e) => {
                        e.preventDefault();
                        if (!this.player.isJumping) {
                            this.player.isJumping = true;
                            this.player.velocity.y = this.JUMP_FORCE;
                        }
                    });
                    
                    // Mouse support for testing
                    jumpBtn.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        if (!this.player.isJumping) {
                            this.player.isJumping = true;
                            this.player.velocity.y = this.JUMP_FORCE;
                        }
                    });
                }
                
                // View controls
                const viewButtons = {
                    'viewUp': { x: 0, y: 0.05 },
                    'viewDown': { x: 0, y: -0.05 },
                    'viewLeft': { x: 0.05, y: 0 },
                    'viewRight': { x: -0.05, y: 0 }
                };
                
                for (const [id, rot] of Object.entries(viewButtons)) {
                    const btn = document.getElementById(id);
                    if (!btn) continue;
                    
                    let interval;
                    
                    const startRotation = () => {
                        interval = setInterval(() => {
                            this.cameraAngle += rot.x;
                            this.cameraPitch += rot.y;
                            this.cameraPitch = Math.max(-Math.PI/3, Math.min(Math.PI/3, this.cameraPitch));
                        }, 50);
                    };
                    
                    const stopRotation = () => {
                        if (interval) {
                            clearInterval(interval);
                            interval = null;
                        }
                    };
                    
                    btn.addEventListener('touchstart', (e) => {
                        e.preventDefault();
                        startRotation();
                    });
                    
                    btn.addEventListener('touchend', (e) => {
                        e.preventDefault();
                        stopRotation();
                    });
                    
                    // Mouse support for testing
                    btn.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        startRotation();
                    });
                    
                    btn.addEventListener('mouseup', (e) => {
                        e.preventDefault();
                        stopRotation();
                    });
                    
                    btn.addEventListener('mouseleave', (e) => {
                        stopRotation();
                    });
                }
            }
            
            startGame() {
                document.getElementById('startScreen').style.display = 'none';
                this.isPlaying = true;
                this.timeRemaining = 60;
                this.setupKanjiData();
                
                // Update UI
                document.getElementById('timer').textContent = `時間: ${this.timeRemaining}`;
                document.getElementById('level').textContent = this.currentLevel;
                document.getElementById('score').textContent = this.score;
                
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
                    this.score++;
                    
                    // レベルアップチェック
                    if (this.score === 2) {
                        resultDiv.textContent += ' レベル2へ進みます！';
                    } else if (this.score === 5) {
                        resultDiv.textContent += ' レベル3へ進みます！';
                    }
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
                
                // Keyboard input
                if (this.keys['KeyW'] || this.keys['ArrowUp']) moveZ = -1;
                if (this.keys['KeyS'] || this.keys['ArrowDown']) moveZ = 1;
                if (this.keys['KeyA'] || this.keys['ArrowLeft']) moveX = -1;
                if (this.keys['KeyD'] || this.keys['ArrowRight']) moveX = 1;
                
                // Mobile input override
                if (this.mobileInput.move.x !== 0 || this.mobileInput.move.z !== 0) {
                    moveX = this.mobileInput.move.x;
                    moveZ = this.mobileInput.move.z;
                }
                
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