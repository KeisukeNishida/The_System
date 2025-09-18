<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D English Adventure</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Arial', sans-serif;
        }
        
        #gameContainer {
            position: relative;
            width: 100vw;
            height: 100vh;
        }
        
        #ui {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 100;
            color: white;
            font-size: 18px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        #instructions {
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 100;
            color: white;
            font-size: 14px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        #questionPanel {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            z-index: 200;
            display: none;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        
        .answer-btn {
            margin: 10px;
            padding: 12px 25px;
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .answer-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        .answer-btn.correct {
            background: linear-gradient(45deg, #2196F3, #1976D2);
        }
        
        .answer-btn.wrong {
            background: linear-gradient(45deg, #f44336, #d32f2f);
        }
        
        #gameOver {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            z-index: 300;
            display: none;
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
        }
        
        #restartBtn {
            margin-top: 20px;
            padding: 15px 30px;
            background: linear-gradient(45deg, #FF6B6B, #EE5A52);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        
        #restartBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div id="gameContainer">
        <div id="ui">
            <div>Score: <span id="score">0</span></div>
            <div>Time: <span id="timer">60</span>s</div>
            <div>Words Collected: <span id="wordsCollected">0</span>/10</div>
        </div>
        
        <div id="instructions">
            Use WASD or Arrow Keys to move • Collect floating words • Answer questions correctly!
        </div>
        
        <div id="questionPanel">
            <h2>What does this word mean?</h2>
            <div id="currentWord"></div>
            <div id="answers"></div>
        </div>
        
        <div id="gameOver">
            <h1>Game Over!</h1>
            <div id="finalScore"></div>
            <button id="restartBtn">Play Again</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        // Game variables
        let scene, camera, renderer, player, words = [], gameActive = true;
        let score = 0, timeLeft = 60, wordsCollected = 0, currentQuestionWord = null;
        
        // Word database
        const wordDatabase = [
            { word: "ADVENTURE", meaning: "An exciting or unusual experience", options: ["A journey", "A book", "A game", "A movie"], correct: 0 },
            { word: "BRILLIANT", meaning: "Very bright or intelligent", options: ["Very smart", "Very dark", "Very slow", "Very quiet"], correct: 0 },
            { word: "CHALLENGE", meaning: "A difficult task", options: ["Easy work", "Hard task", "Simple game", "Quick job"], correct: 1 },
            { word: "DISCOVER", meaning: "To find something new", options: ["To lose", "To find", "To break", "To sell"], correct: 1 },
            { word: "EXPLORE", meaning: "To travel and investigate", options: ["To sleep", "To eat", "To investigate", "To run"], correct: 2 },
            { word: "FANTASTIC", meaning: "Extremely good", options: ["Very bad", "Very good", "Very old", "Very new"], correct: 1 },
            { word: "GENUINE", meaning: "Real and authentic", options: ["Fake", "Real", "Broken", "Expensive"], correct: 1 },
            { word: "HARMONY", meaning: "Peace and agreement", options: ["War", "Agreement", "Noise", "Confusion"], correct: 1 },
            { word: "INSPIRE", meaning: "To motivate someone", options: ["To motivate", "To discourage", "To ignore", "To forget"], correct: 0 },
            { word: "JOURNEY", meaning: "A trip or travel", options: ["A house", "A trip", "A book", "A friend"], correct: 1 }
        ];
        
        // Input handling
        const keys = {};
        document.addEventListener('keydown', (event) => {
            keys[event.code] = true;
        });
        document.addEventListener('keyup', (event) => {
            keys[event.code] = false;
        });
        
        function init() {
            // Create scene
            scene = new THREE.Scene();
            scene.background = new THREE.Color(0x87CEEB);
            
            // Create camera
            camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            camera.position.set(0, 2, 5);
            
            // Create renderer
            renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.shadowMap.enabled = true;
            renderer.shadowMap.type = THREE.PCFSoftShadowMap;
            document.getElementById('gameContainer').appendChild(renderer.domElement);
            
            // Add lighting
            const ambientLight = new THREE.AmbientLight(0x404040, 0.4);
            scene.add(ambientLight);
            
            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(10, 20, 5);
            directionalLight.castShadow = true;
            scene.add(directionalLight);
            
            // Create ground
            const groundGeometry = new THREE.PlaneGeometry(50, 50);
            const groundMaterial = new THREE.MeshLambertMaterial({ color: 0x90EE90 });
            const ground = new THREE.Mesh(groundGeometry, groundMaterial);
            ground.rotation.x = -Math.PI / 2;
            ground.receiveShadow = true;
            scene.add(ground);
            
            // Create player
            const playerGeometry = new THREE.SphereGeometry(0.5);
            const playerMaterial = new THREE.MeshLambertMaterial({ color: 0x4169E1 });
            player = new THREE.Mesh(playerGeometry, playerMaterial);
            player.position.set(0, 0.5, 0);
            player.castShadow = true;
            scene.add(player);
            
            // Create floating words
            createWords();
            
            // Start game timer
            startTimer();
            
            // Start game loop
            animate();
        }
        
        function createWords() {
            words = [];
            for (let i = 0; i < 10; i++) {
                const wordData = wordDatabase[i];
                
                // Create word mesh
                const geometry = new THREE.BoxGeometry(1, 1, 1);
                const material = new THREE.MeshLambertMaterial({ color: Math.random() * 0xffffff });
                const wordMesh = new THREE.Mesh(geometry, material);
                
                // Position randomly
                wordMesh.position.set(
                    (Math.random() - 0.5) * 40,
                    Math.random() * 3 + 2,
                    (Math.random() - 0.5) * 40
                );
                
                wordMesh.castShadow = true;
                wordMesh.userData = { wordData: wordData, collected: false };
                
                // Create floating text
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = 256;
                canvas.height = 128;
                context.font = 'bold 24px Arial';
                context.fillStyle = 'white';
                context.textAlign = 'center';
                context.fillText(wordData.word, 128, 64);
                
                const texture = new THREE.CanvasTexture(canvas);
                const spriteMaterial = new THREE.SpriteMaterial({ map: texture });
                const sprite = new THREE.Sprite(spriteMaterial);
                sprite.scale.set(2, 1, 1);
                sprite.position.copy(wordMesh.position);
                sprite.position.y += 1.5;
                
                scene.add(wordMesh);
                scene.add(sprite);
                
                words.push({ mesh: wordMesh, sprite: sprite });
            }
        }
        
        function animate() {
            if (!gameActive) return;
            
            requestAnimationFrame(animate);
            
            // Handle player movement
            const moveSpeed = 0.1;
            if (keys['KeyW'] || keys['ArrowUp']) {
                player.position.z -= moveSpeed;
            }
            if (keys['KeyS'] || keys['ArrowDown']) {
                player.position.z += moveSpeed;
            }
            if (keys['KeyA'] || keys['ArrowLeft']) {
                player.position.x -= moveSpeed;
            }
            if (keys['KeyD'] || keys['ArrowRight']) {
                player.position.x += moveSpeed;
            }
            
            // Update camera to follow player
            camera.position.x = player.position.x;
            camera.position.z = player.position.z + 5;
            camera.lookAt(player.position);
            
            // Animate words
            words.forEach(wordObj => {
                if (!wordObj.mesh.userData.collected) {
                    wordObj.mesh.rotation.y += 0.02;
                    wordObj.sprite.position.y = wordObj.mesh.position.y + 1.5 + Math.sin(Date.now() * 0.003) * 0.2;
                    
                    // Check collision
                    const distance = player.position.distanceTo(wordObj.mesh.position);
                    if (distance < 1.5) {
                        collectWord(wordObj);
                    }
                }
            });
            
            renderer.render(scene, camera);
        }
        
        function collectWord(wordObj) {
            wordObj.mesh.userData.collected = true;
            scene.remove(wordObj.mesh);
            scene.remove(wordObj.sprite);
            
            currentQuestionWord = wordObj.mesh.userData.wordData;
            showQuestion();
        }
        
        function showQuestion() {
            const questionPanel = document.getElementById('questionPanel');
            const currentWord = document.getElementById('currentWord');
            const answers = document.getElementById('answers');
            
            currentWord.innerHTML = `<h3>"${currentQuestionWord.word}"</h3>`;
            
            answers.innerHTML = '';
            currentQuestionWord.options.forEach((option, index) => {
                const btn = document.createElement('button');
                btn.className = 'answer-btn';
                btn.textContent = option;
                btn.onclick = () => answerQuestion(index);
                answers.appendChild(btn);
            });
            
            questionPanel.style.display = 'block';
            gameActive = false;
        }
        
        function answerQuestion(selectedIndex) {
            const buttons = document.querySelectorAll('.answer-btn');
            const correct = selectedIndex === currentQuestionWord.correct;
            
            buttons[selectedIndex].className = correct ? 'answer-btn correct' : 'answer-btn wrong';
            if (!correct) {
                buttons[currentQuestionWord.correct].className = 'answer-btn correct';
            }
            
            setTimeout(() => {
                if (correct) {
                    score += 100;
                    wordsCollected++;
                    updateUI();
                }
                
                document.getElementById('questionPanel').style.display = 'none';
                gameActive = true;
                
                if (wordsCollected >= 10 || timeLeft <= 0) {
                    endGame();
                } else {
                    animate();
                }
            }, 2000);
        }
        
        function startTimer() {
            const timer = setInterval(() => {
                timeLeft--;
                document.getElementById('timer').textContent = timeLeft;
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    endGame();
                }
            }, 1000);
        }
        
        function updateUI() {
            document.getElementById('score').textContent = score;
            document.getElementById('wordsCollected').textContent = wordsCollected;
        }
        
        function endGame() {
            gameActive = false;
            const gameOver = document.getElementById('gameOver');
            const finalScore = document.getElementById('finalScore');
            
            let performance = '';
            if (score >= 800) performance = 'Excellent!';
            else if (score >= 600) performance = 'Great job!';
            else if (score >= 400) performance = 'Good work!';
            else performance = 'Keep practicing!';
            
            finalScore.innerHTML = `
                <h3>${performance}</h3>
                <p>Final Score: ${score}</p>
                <p>Words Collected: ${wordsCollected}/10</p>
                <p>Correct Answers: ${Math.floor(score/100)}</p>
            `;
            
            gameOver.style.display = 'block';
        }
        
        function restartGame() {
            // Reset game variables
            score = 0;
            timeLeft = 60;
            wordsCollected = 0;
            gameActive = true;
            currentQuestionWord = null;
            
            // Clear scene
            words.forEach(wordObj => {
                scene.remove(wordObj.mesh);
                scene.remove(wordObj.sprite);
            });
            
            // Reset UI
            updateUI();
            document.getElementById('timer').textContent = timeLeft;
            document.getElementById('gameOver').style.display = 'none';
            document.getElementById('questionPanel').style.display = 'none';
            
            // Reset player position
            player.position.set(0, 0.5, 0);
            
            // Recreate words
            createWords();
            
            // Restart timer
            startTimer();
            
            // Restart animation
            animate();
        }
        
        // Event listeners
        document.getElementById('restartBtn').addEventListener('click', restartGame);
        
        // Handle window resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });
        
        // Initialize game
        init();
    </script>
</body>
</html>