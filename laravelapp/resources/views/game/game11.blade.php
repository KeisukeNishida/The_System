<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>3D漢字地下迷宮（60秒タイマー付き・堅牢版）</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- Webフォント（任意） -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@700&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{overflow:hidden;font-family:'Noto Sans JP',system-ui,-apple-system,"Hiragino Kaku Gothic ProN",Meiryo,Arial,sans-serif;background:#000}
    #container{width:100vw;height:100vh;position:relative}
    #menu,#answer,#errorBox{
      position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
      text-align:center;background:rgba(30,20,10,.95);padding:40px;border-radius:20px;
      box-shadow:0 10px 30px rgba(255,136,0,.5);z-index:10;border:2px solid #ff8800
    }
    #menu h1{font-size:36px;margin-bottom:10px;color:#ff8800}
    #menu p{font-size:16px;margin-bottom:30px;color:#ffaa44}
    #score{font-size:20px;margin-bottom:30px;font-weight:bold;color:#ffd700}
    .btn{padding:15px 30px;font-size:18px;border:none;border-radius:10px;cursor:pointer;font-weight:bold;margin:10px 0;display:block;width:100%}
    .btn-level1{background:#90ee90}.btn-level2{background:#ffd700}.btn-level3{background:#ff6b6b;color:#fff}
    #timer{
      position:absolute;top:20px;left:50%;transform:translateX(-50%);
      background:rgba(30,20,10,.9);color:#ff8800;padding:10px 18px;border-radius:10px;
      font-size:22px;font-weight:bold;z-index:10;border:2px solid #ff8800;display:none
    }
    #answer h2{margin-bottom:20px;font-size:28px;color:#ff8800}
    #answerInput{font-size:48px;width:100px;text-align:center;padding:10px;border-radius:10px;border:3px solid #ff8800;margin-bottom:20px;background:#1a0f0a;color:#ffd700}
    #submitBtn{padding:15px 40px;font-size:20px;background:#ff8800;color:#fff;border:none;border-radius:10px;cursor:pointer;font-weight:bold}
    #message{margin-top:20px;font-size:24px;font-weight:bold}
    .hidden{display:none!important}
    #controls{margin-top:30px;font-size:14px;color:#ffaa44;text-align:left}
    #hint{position:absolute;bottom:10px;left:50%;transform:translateX(-50%);color:#ffaa44;background:rgba(0,0,0,.4);padding:6px 10px;border-radius:8px;font-size:12px;border:1px solid #ff8800}
    #errorBox{color:#ffd7d7;border-color:#ff6b6b}
    #errorBox h2{color:#ff6b6b;margin-bottom:10px}
    #errorText{font-size:14px;white-space:pre-wrap;text-align:left;max-width:70vw;max-height:40vh;overflow:auto;background:rgba(0,0,0,.4);padding:10px;border-radius:8px}
  </style>
</head>
<body>
  <div id="container">
    <div id="menu">
      <h1>3D漢字地下迷宮</h1>
      <p>暗い地下通路を探索して漢字を推測!</p>
      <div id="score">スコア: 0点</div>
      <button class="btn btn-level1" onclick="safeStartGame(1)">レベル1 (上・下・山)</button>
      <button class="btn btn-level2" onclick="safeStartGame(2)">レベル2 (西・東・水)</button>
      <button class="btn btn-level3" onclick="safeStartGame(3)">レベル3 (命・恋・頭)</button>
      <div id="controls">
        <p><strong>操作方法:</strong></p>
        <p>WASD: 移動 / マウス: 視点移動（画面クリックでロック）</p>
        <p>地下通路を歩き回って漢字の形を推測しよう!</p>
      </div>
    </div>

    <div id="timer" class="hidden">残り時間: 01:00</div>

    <div id="answer" class="hidden">
      <h2>この漢字は何?</h2>
      <input type="text" id="answerInput" maxlength="1" placeholder="漢字1文字">
      <br>
      <button id="submitBtn" onclick="submitAnswer()">回答する</button>
      <div id="message"></div>
    </div>

    <div id="hint" class="hidden">クリックで操作ロック（Escで解除）</div>

    <div id="errorBox" class="hidden">
      <h2>起動エラー</h2>
      <div id="errorText"></div>
      <button class="btn" style="background:#90ee90" onclick="dismissError()">OK（メニューに戻る）</button>
    </div>
  </div>

  <!-- three.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script>
  /* ================== グローバル状態 ================== */
  let scene, camera, renderer, player;
  let gameState = 'menu';
  let level = 1, timeLeft = 60, score = 0, currentKanji = '';
  let timerInterval = null, rafId = null;
  const keys = {};
  let velocity = new THREE.Vector3();
  let mouseX = 0, mouseY = 0, rotationY = 0, rotationX = 0;
  let listenersBound = false;

  const LIMIT_SECONDS = 60;
  function formatTime(sec){
    const m = Math.floor(sec/60);
    const s = sec % 60;
    return `${m}:${String(s).padStart(2,'0')}`;
  }

  /* ================== 出題データ ================== */
  const kanjiData = {
    1: ['上','下','山'],
    2: ['西','東','水'],
    3: ['命','恋','頭']
  };

  /* ================== グリッド設定 ================== */
  const gridBase = {
    canvasSize: 256,
    fontSize: 200,
    cellSize: 0.6,
    wallHeight: 2.6,
    wallThickness: 0.08,
    threshold: 64,
    thickenIterations: 0
  };
  let gridConf = {...gridBase};

  let walkGrid = null;
  let gridW = 0, gridH = 0;
  let worldOrigin = new THREE.Vector3(0,0,0);

  const clamp = (v,min,max)=>Math.max(min,Math.min(max,v));

  function showError(msg, err){
    const box = document.getElementById('errorBox');
    const t = document.getElementById('errorText');
    t.textContent = (msg||'') + (err?('\n\n[詳細]\n'+(err.stack||err.message||String(err))):'');
    box.classList.remove('hidden');
    document.getElementById('menu').classList.add('hidden');
    document.getElementById('timer').classList.add('hidden');
    document.getElementById('answer').classList.add('hidden');
    document.getElementById('hint').classList.add('hidden');
    console.error(msg, err);
  }
  function dismissError(){
    document.getElementById('errorBox').classList.add('hidden');
    document.getElementById('menu').classList.remove('hidden');
  }

  async function waitFontsReadyWithTimeout(ms=800){
    if (!(document.fonts && document.fonts.ready)) return;
    try{
      await Promise.race([
        document.fonts.ready,
        new Promise((resolve)=>setTimeout(resolve, ms))
      ]);
    }catch(_){}
  }

  function rasterizeKanjiToGrid(char){
    const size = gridConf.canvasSize;
    const cv = document.createElement('canvas');
    cv.width = size; cv.height = size;
    const ctx = cv.getContext('2d');
    ctx.clearRect(0,0,size,size);
    ctx.fillStyle = '#fff';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.font = `700 ${gridConf.fontSize}px "Noto Sans JP",system-ui,"Hiragino Kaku Gothic ProN",Meiryo,Arial,sans-serif`;
    ctx.fillText(char, size/2, size/2);

    let src = ctx.getImageData(0,0,size,size);

    if (gridConf.thickenIterations>0){
      for(let iter=0; iter<gridConf.thickenIterations; iter++){
        const d = src.data;
        const out = new Uint8ClampedArray(d.length);
        const w=size,h=size;
        const idx=(x,y)=>((y*w+x)<<2);
        for(let y=0;y<h;y++){
          for(let x=0;x<w;x++){
            let aMax=0;
            for(let dy=-1;dy<=1;dy++){
              for(let dx=-1;dx<=1;dx++){
                const nx=clamp(x+dx,0,w-1), ny=clamp(y+dy,0,h-1);
                const a = d[idx(nx,ny)+3];
                if (a>aMax) aMax=a;
              }
            }
            const o=idx(x,y);
            out[o]=255; out[o+1]=255; out[o+2]=255; out[o+3]=aMax;
          }
        }
        src = new ImageData(out, w, h);
      }
    }

    gridW = size; gridH = size;
    walkGrid = new Uint8Array(gridW*gridH);
    const d = src.data;
    let count=0;
    for(let y=0;y<size;y++){
      for(let x=0;x<size;x++){
        const a = d[((y*size + x)<<2)+3];
        const val = (a >= gridConf.threshold) ? 1 : 0;
        walkGrid[y*gridW + x] = val;
        count += val;
      }
    }
    return count;
  }

  function ensureGridHasEnoughCells(char){
    const minCells = Math.floor(gridConf.canvasSize*gridConf.canvasSize*0.01);
    let tries = 0;
    while (true){
      const cells = rasterizeKanjiToGrid(char);
      if (cells >= minCells || tries>=3) return cells;
      gridConf.thickenIterations += 1;
      gridConf.fontSize += 20;
      tries++;
    }
  }

  function buildMazeMeshes(){
    const cell = gridConf.cellSize;
    const halfW = gridW * cell * 0.5;
    const halfH = gridH * cell * 0.5;
    worldOrigin.set(-halfW, 0, -halfH);

    scene.fog = new THREE.Fog(0x87ceeb, 25, 220);

    const skyGeo = new THREE.SphereGeometry(400, 32, 32);
    const skyMat = new THREE.MeshBasicMaterial({ color: 0x87ceeb, side: THREE.BackSide });
    scene.add(new THREE.Mesh(skyGeo, skyMat));

    scene.add(new THREE.AmbientLight(0x666666, 1.2));
    const dir = new THREE.DirectionalLight(0xffffff, 0.9);
    dir.position.set(30,50,10);
    dir.castShadow = true;
    scene.add(dir);

    const groundSize = Math.max(gridW, gridH) * cell + 10;
    const ground = new THREE.Mesh(
      new THREE.PlaneGeometry(groundSize, groundSize),
      new THREE.MeshStandardMaterial({color:0x1a1a1a})
    );
    ground.rotation.x = -Math.PI/2;
    ground.position.y = -1.5;
    ground.receiveShadow = true;
    scene.add(ground);

    const floorGeom = new THREE.BoxGeometry(cell, 0.2, cell);
    const floorMat  = new THREE.MeshStandardMaterial({color:0x2c1810});
    const floorCount = walkGrid.reduce((a,b)=>a+(b?1:0),0);
    const floorInst = new THREE.InstancedMesh(floorGeom, floorMat, floorCount);
    floorInst.instanceMatrix.setUsage(THREE.DynamicDrawUsage);

    const wallH = gridConf.wallHeight;
    const t = gridConf.wallThickness;
    const wallMat = new THREE.MeshStandardMaterial({ color:0x234a2a, roughness:0.9, metalness:0.1 });

    const wallNGeom = new THREE.BoxGeometry(cell, wallH, t);
    const wallSGeom = wallNGeom.clone();
    const wallEGeom = new THREE.BoxGeometry(t, wallH, cell);
    const wallWGeom = wallEGeom.clone();

    function countWalls(){
      let n=0;
      for(let y=0;y<gridH;y++){
        for(let x=0;x<gridW;x++){
          if (!walkGrid[y*gridW+x]) continue;
          if (y===0 || !walkGrid[(y-1)*gridW+x]) n++;
          if (y===gridH-1 || !walkGrid[(y+1)*gridW+x]) n++;
          if (x===gridW-1 || !walkGrid[y*gridW+(x+1)]) n++;
          if (x===0 || !walkGrid[y*gridW+(x-1)]) n++;
        }
      }
      return n;
    }
    const wn = countWalls();
    const wallsN = new THREE.InstancedMesh(wallNGeom, wallMat, wn);
    const wallsS = new THREE.InstancedMesh(wallSGeom, wallMat, wn);
    const wallsE = new THREE.InstancedMesh(wallEGeom, wallMat, wn);
    const wallsW = new THREE.InstancedMesh(wallWGeom, wallMat, wn);

    const m4 = new THREE.Matrix4();
    const v3 = new THREE.Vector3();
    let fi=0,iN=0,iS=0,iE=0,iW=0;

    for(let y=0;y<gridH;y++){
      for(let x=0;x<gridW;x++){
        if (!walkGrid[y*gridW+x]) continue;
        const wx = worldOrigin.x + x*cell + cell*0.5;
        const wz = worldOrigin.z + y*cell + cell*0.5;

        v3.set(wx, -1.5, wz);
        m4.makeTranslation(v3.x, v3.y, v3.z);
        floorInst.setMatrixAt(fi++, m4);

        if (y===0 || !walkGrid[(y-1)*gridW+x]){ v3.set(wx, -1.5 + wallH/2, wz - cell/2); m4.makeTranslation(v3.x, v3.y, v3.z); wallsN.setMatrixAt(iN++, m4); }
        if (y===gridH-1 || !walkGrid[(y+1)*gridW+x]){ v3.set(wx, -1.5 + wallH/2, wz + cell/2); m4.makeTranslation(v3.x, v3.y, v3.z); wallsS.setMatrixAt(iS++, m4); }
        if (x===gridW-1 || !walkGrid[y*gridW+(x+1)]){ v3.set(wx + cell/2, -1.5 + wallH/2, wz); m4.makeTranslation(v3.x, v3.y, v3.z); wallsE.setMatrixAt(iE++, m4); }
        if (x===0 || !walkGrid[y*gridW+(x-1)]){ v3.set(wx - cell/2, -1.5 + wallH/2, wz); m4.makeTranslation(v3.x, v3.y, v3.z); wallsW.setMatrixAt(iW++, m4); }
      }
    }

    scene.add(floorInst, wallsN, wallsS, wallsE, wallsW);
  }

  function computeStartPosition(){
    let sx=0, sy=0, c=0;
    for(let y=0;y<gridH;y++){
      for(let x=0;x<gridW;x++){
        if (walkGrid[y*gridW+x]){ sx+=x; sy+=y; c++; }
      }
    }
    if (c===0) return new THREE.Vector3(0,0,0);
    sx = Math.round(sx/c); sy = Math.round(sy/c);
    const cell = gridConf.cellSize;
    return new THREE.Vector3(
      worldOrigin.x + sx*cell + cell*0.5,
      0,
      worldOrigin.z + sy*cell + cell*0.5
    );
  }

  function init(){
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 2000);
    scene.background = new THREE.Color(0x87ceeb);

    renderer = new THREE.WebGLRenderer({antialias:true});
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.shadowMap.enabled = true;
    document.getElementById('container').appendChild(renderer.domElement);

    buildMazeMeshes();

    player = new THREE.Group();
    player.visible = false;
    const startPos = computeStartPosition();
    player.position.copy(startPos);
    scene.add(player);

    rotationX = 0; rotationY = 0; mouseX = 0; mouseY = 0;
    camera.position.set(startPos.x, startPos.y + 0.6, startPos.z);
    camera.lookAt(startPos.x, startPos.y + 0.6, startPos.z + 1);

    if (!listenersBound){
      window.addEventListener('keydown', (e)=>{
        keys[e.key]=true;
        if(['ArrowUp','ArrowDown','ArrowLeft','ArrowRight',' '].includes(e.key)) e.preventDefault();
      });
      window.addEventListener('keyup', (e)=>{ keys[e.key]=false; });
      window.addEventListener('resize', ()=>{
        camera.aspect = window.innerWidth/window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
      });
      renderer.domElement.addEventListener('mousemove', (e)=>{
        mouseX = e.movementX||0; mouseY = e.movementY||0;
      });
      renderer.domElement.addEventListener('click', ()=>{
        renderer.domElement.requestPointerLock?.();
      });
      document.addEventListener('pointerlockchange', ()=>{
        const hint = document.getElementById('hint');
        if (document.pointerLockElement === renderer.domElement){
          hint.classList.add('hidden');
        } else {
          if (gameState === 'playing') hint.classList.remove('hidden');
        }
      });
      listenersBound = true;
    }
  }

  function posIsWalkable(pos){
    const cell = gridConf.cellSize;
    const gx = Math.floor((pos.x - worldOrigin.x) / cell);
    const gy = Math.floor((pos.z - worldOrigin.z) / cell);
    if (gx<0 || gy<0 || gx>=gridW || gy>=gridH) return false;
    return walkGrid[gy*gridW + gx] === 1;
  }

  function animate(){
    rafId = requestAnimationFrame(animate);
    if (gameState !== 'playing') return;

    const moveSpeed = 0.12;
    const forward = new THREE.Vector3(Math.sin(rotationY),0,Math.cos(rotationY));
    const right   = new THREE.Vector3(Math.cos(rotationY),0,-Math.sin(rotationY));
    velocity.set(0,0,0);

    if (keys['ArrowUp']||keys['w']) velocity.add(forward.clone().multiplyScalar(moveSpeed));
    if (keys['ArrowDown']||keys['s']) velocity.add(forward.clone().multiplyScalar(-moveSpeed));
    if (keys['ArrowLeft']||keys['a']) velocity.add(right.clone().multiplyScalar(-moveSpeed));
    if (keys['ArrowRight']||keys['d']) velocity.add(right.clone().multiplyScalar(moveSpeed));

    const newPos = player.position.clone();
    newPos.x += velocity.x; newPos.z += velocity.z;
    if (posIsWalkable(newPos)) player.position.copy(newPos);

    rotationY -= mouseX*0.002;
    rotationX -= mouseY*0.002;
    rotationX = clamp(rotationX, -Math.PI/2, Math.PI/2);
    mouseX *= 0.9; mouseY *= 0.9;

    const lookAtDistance = 10;
    const lookAtPos = new THREE.Vector3(
      player.position.x + Math.sin(rotationY)*lookAtDistance*Math.cos(rotationX),
      player.position.y + 0.5 + Math.sin(rotationX)*lookAtDistance,
      player.position.z + Math.cos(rotationY)*lookAtDistance*Math.cos(rotationX)
    );
    camera.position.set(player.position.x, player.position.y + 0.6, player.position.z);
    camera.lookAt(lookAtPos);

    renderer.render(scene, camera);
  }

  async function startGame(selectedLevel){
    level = selectedLevel;

    // UI
    document.getElementById('menu').classList.add('hidden');
    document.getElementById('answer').classList.add('hidden');
    document.getElementById('timer').classList.remove('hidden');
    document.getElementById('hint').classList.remove('hidden');

    // クリーンアップ
    if (rafId){ cancelAnimationFrame(rafId); rafId = null; }
    if (renderer){
      try{ renderer.dispose(); }catch(e){}
      try{ renderer.domElement?.remove(); }catch(e){}
    }
    scene = null; camera = null; player = null;

    // フォント待機（最大800ms）
    await waitFontsReadyWithTimeout(800);

    // 出題
    const list = kanjiData[selectedLevel] || ['山'];
    currentKanji = list[Math.floor(Math.random()*list.length)] || '山';

    // グリッド生成（必要なら自動調整）
    gridConf = {...gridBase};
    let cells = ensureGridHasEnoughCells(currentKanji);
    if (!cells || cells < 10){
      gridConf.canvasSize = 384;
      gridConf.fontSize += 60;
      gridConf.thickenIterations += 1;
      cells = ensureGridHasEnoughCells(currentKanji);
    }

    // 初期化 & ループ
    init();
    gameState = 'playing';

    // タイマー初期化（mm:ss で表示）
    timeLeft = LIMIT_SECONDS;
    const timerEl = document.getElementById('timer');
    timerEl.classList.remove('hidden');
    timerEl.textContent = `残り時間: ${formatTime(timeLeft)}`;

    clearInterval(timerInterval);
    timerInterval = setInterval(()=>{
      timeLeft = Math.max(0, timeLeft - 1);
      timerEl.textContent = `残り時間: ${formatTime(timeLeft)}`;
      if (timeLeft <= 0){
        clearInterval(timerInterval);
        gameState='answer';
        document.exitPointerLock?.();
        timerEl.classList.add('hidden');
        document.getElementById('answer').classList.remove('hidden');
        document.getElementById('answerInput').value='';
        document.getElementById('message').textContent='';
        document.getElementById('answerInput').focus();
        document.getElementById('hint').classList.add('hidden');
      }
    }, 1000);

    animate();
  }

  async function safeStartGame(lv){
    try{
      await startGame(lv);
    }catch(err){
      showError('ゲームの開始に失敗しました。', err);
      if (rafId){ cancelAnimationFrame(rafId); rafId = null; }
    }
  }

  function submitAnswer(){
    const userAnswer = document.getElementById('answerInput').value.trim();
    const messageEl = document.getElementById('message');
    if (userAnswer === currentKanji){
      score += level*10;
      messageEl.textContent = '正解です!';
      messageEl.style.color = '#90ee90';
    } else {
      messageEl.textContent = `不正解... 正解は「${currentKanji}」でした`;
      messageEl.style.color = '#ff6b6b';
    }
    document.getElementById('score').textContent = `スコア: ${score}点`;
    setTimeout(()=>{
      gameState='menu';
      document.getElementById('answer').classList.add('hidden');
      document.getElementById('menu').classList.remove('hidden');
    }, 2000);
  }
  document.getElementById('answerInput').addEventListener('keypress',(e)=>{ if(e.key==='Enter') submitAnswer(); });

  // デバッグ用
  window.__kanjiMazeConfig = gridConf;

  </script>
</body>
</html>
