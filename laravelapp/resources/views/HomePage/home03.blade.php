<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FrontArc Inc. — デジタルイノベーションの最前線へ</title>
  <meta name="description" content="フロントアーク株式会社 — 最先端のテクノロジーとクリエイティブで、ビジネスの未来を創造します。" />
  
  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            purple: {
              50:'#FAF5FF',100:'#F3E8FF',200:'#E9D5FF',300:'#D8B4FE',400:'#C084FC',
              500:'#A855F7',600:'#9333EA',700:'#7E22CE',800:'#6B21A8',900:'#581C87'
            },
            violet: {
              50:'#F5F3FF',100:'#EDE9FE',200:'#DDD6FE',300:'#C4B5FD',400:'#A78BFA',
              500:'#8B5CF6',600:'#7C3AED',700:'#6D28D9',800:'#5B21B6',900:'#4C1D95'
            },
            ink: {900:'#0F0817'}
          },
          fontFamily: {
            jp:["Noto Sans JP","system-ui","-apple-system","Segoe UI","Hiragino Kaku Gothic ProN","Meiryo","sans-serif"],
            display:["BIZ UDPGothic","Noto Sans JP","system-ui","sans-serif"]
          },
          boxShadow:{
            soft:'0 20px 60px rgba(139,92,246,.15)',
            glow:'0 0 60px rgba(168,85,247,.4)',
            'inner-glow':'inset 0 0 30px rgba(168,85,247,.1)'
          },
          animation: {
            'float': 'float 6s ease-in-out infinite',
            'pulse-glow': 'pulseGlow 3s ease-in-out infinite',
            'gradient': 'gradient 8s ease infinite',
            'shimmer': 'shimmer 3s ease-out infinite'
          },
          backgroundImage: {
            'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
            'gradient-conic': 'conic-gradient(var(--tw-gradient-stops))'
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet" />
  
  <style>
    html{scroll-behavior:smooth}
    body{cursor:none}
    .will-change-transform{will-change:transform}
    
    /* Enhanced glow effects */
    .glow-purple{box-shadow:0 8px 40px rgba(168,85,247,.35), 0 0 80px rgba(139,92,246,.2)}
    .text-glow{text-shadow:0 0 40px rgba(168,85,247,.5)}
    
    /* Animated gradient backgrounds */
    .gradient-animate{
      background: linear-gradient(-45deg, #A855F7, #8B5CF6, #7C3AED, #9333EA);
      background-size: 400% 400%;
      animation: gradient 15s ease infinite;
    }
    
    @keyframes gradient {
      0%{background-position:0% 50%}
      50%{background-position:100% 50%}
      100%{background-position:0% 50%}
    }
    
    @keyframes float {
      0%,100%{transform:translateY(0)}
      50%{transform:translateY(-20px)}
    }
    
    @keyframes pulseGlow {
      0%,100%{opacity:0.5;filter:blur(20px)}
      50%{opacity:0.8;filter:blur(30px)}
    }
    
    @keyframes shimmer {
      0%{transform:translateX(-100%) translateY(-100%) rotate(35deg)}
      100%{transform:translateX(100%) translateY(100%) rotate(35deg)}
    }
    
    /* Glassmorphism effect */
    .glass{
      background:rgba(255,255,255,0.05);
      backdrop-filter:blur(20px);
      -webkit-backdrop-filter:blur(20px);
      border:1px solid rgba(255,255,255,0.1);
    }
    
    /* Noise texture overlay */
    .noise:before{
      content:"";position:fixed;inset:0;pointer-events:none;
      background-image:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><filter id="n"><feTurbulence type="fractalNoise" baseFrequency="0.95" numOctaves="3" stitchTiles="stitch"/></filter><rect width="100%" height="100%" filter="url(%23n)" opacity=".02"/></svg>');
      mix-blend-mode:overlay;
    }
    
    /* Perspective for 3D effects */
    .perspective-1000{perspective:1000px}
    .transform-3d{transform-style:preserve-3d}
    
    /* Custom scrollbar */
    ::-webkit-scrollbar{width:12px}
    ::-webkit-scrollbar-track{background:#1a0e2e}
    ::-webkit-scrollbar-thumb{background:linear-gradient(#A855F7,#7C3AED);border-radius:6px}
    
    /* Reduced motion */
    @media (prefers-reduced-motion: reduce){
      body{cursor:auto}
      *{animation:none!important;transition:none!important}
    }
  </style>
</head>
<body class="font-jp text-white bg-gradient-to-br from-ink-900 via-purple-900/20 to-ink-900 noise min-h-screen">
  
  <!-- Custom animated cursor -->
  <div id="cursor" class="fixed z-[200] top-0 left-0 -translate-x-1/2 -translate-y-1/2 pointer-events-none hidden md:block">
    <div class="w-8 h-8 rounded-full bg-purple-500/30 backdrop-blur-sm border border-purple-400/50"></div>
    <div class="absolute inset-0 w-8 h-8 rounded-full bg-purple-500/20 animate-ping"></div>
  </div>
  
  <!-- Floating particles background -->
  <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-violet-500/20 rounded-full blur-3xl animate-float" style="animation-delay:2s"></div>
    <div class="absolute top-3/4 left-3/4 w-64 h-64 bg-purple-400/20 rounded-full blur-3xl animate-float" style="animation-delay:4s"></div>
  </div>

  <!-- ===== Header ===== -->
  <header id="siteHeader" class="fixed top-0 z-50 w-full glass border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="h-20 flex items-center justify-between">
        <a href="#hero" class="flex items-center gap-3 group">
          <div class="relative">
            <div class="w-12 h-12 rounded-xl gradient-animate text-white grid place-content-center font-bold text-xl group-hover:scale-110 transition-transform glow-purple">F</div>
            <div class="absolute inset-0 w-12 h-12 rounded-xl bg-purple-500 animate-pulse-glow"></div>
          </div>
          <span class="font-bold tracking-tight text-lg bg-gradient-to-r from-purple-300 to-violet-300 bg-clip-text text-transparent">FrontArc</span>
        </a>
        
        <nav class="hidden md:flex items-center gap-8 text-sm">
          <a href="#about" class="hover:text-purple-300 transition-colors">ABOUT</a>
          <a href="#services" class="hover:text-purple-300 transition-colors">SERVICES</a>
          <a href="#works" class="hover:text-purple-300 transition-colors">WORKS</a>
          <a href="#technology" class="hover:text-purple-300 transition-colors">TECHNOLOGY</a>
          <a href="#contact" class="relative px-6 py-3 rounded-full glass border border-purple-400/50 hover:border-purple-300 transition-all group overflow-hidden" data-magnetic>
            <span class="relative z-10">CONTACT</span>
            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-violet-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          </a>
        </nav>
        
        <button id="menuBtn" class="md:hidden w-12 h-12 rounded-lg glass border border-white/10 grid place-content-center">
          <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
      
      <div id="mobileNav" class="md:hidden hidden pb-4">
        <div class="grid gap-2 text-sm">
          <a href="#about" class="py-2 hover:text-purple-300">ABOUT</a>
          <a href="#services" class="py-2 hover:text-purple-300">SERVICES</a>
          <a href="#works" class="py-2 hover:text-purple-300">WORKS</a>
          <a href="#technology" class="py-2 hover:text-purple-300">TECHNOLOGY</a>
          <a href="#contact" class="py-2 font-medium text-purple-300">CONTACT</a>
        </div>
      </div>
    </div>
  </header>

  <!-- ===== Hero ===== -->
  <section id="hero" class="relative min-h-screen flex items-center overflow-hidden">
    <!-- Animated background shapes -->
    <div class="absolute inset-0 -z-10">
      <svg class="absolute -top-32 -right-32 w-[800px] h-[800px] opacity-30" viewBox="0 0 800 800">
        <defs>
          <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#A855F7;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#8B5CF6;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#7C3AED;stop-opacity:1" />
          </linearGradient>
        </defs>
        <path id="morphBlob" fill="url(#grad1)" d="M400 300Q450 400 350 450T150 400Q50 300 150 150T400 100Q550 200 400 300Z"/>
      </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20">
      <div class="grid lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-7">
          <!-- Animated letters -->
          <div class="flex gap-4 mb-8 js-hero-letters">
            <span class="text-7xl lg:text-8xl font-black gradient-animate bg-clip-text text-transparent opacity-0">F</span>
            <span class="text-7xl lg:text-8xl font-black gradient-animate bg-clip-text text-transparent opacity-0">R</span>
            <span class="text-7xl lg:text-8xl font-black gradient-animate bg-clip-text text-transparent opacity-0">O</span>
            <span class="text-7xl lg:text-8xl font-black gradient-animate bg-clip-text text-transparent opacity-0">N</span>
            <span class="text-7xl lg:text-8xl font-black gradient-animate bg-clip-text text-transparent opacity-0">T</span>
          </div>
          
          <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight">
            <span class="block js-split">Digital Innovation</span>
            <span class="block js-split bg-gradient-to-r from-purple-300 via-violet-300 to-purple-300 bg-clip-text text-transparent">Starts Here.</span>
          </h1>
          
          <p class="mt-8 text-lg text-purple-100/80 max-w-2xl leading-relaxed">
            最先端のテクノロジーと革新的なデザインで、ビジネスの可能性を無限に広げます。デジタルトランスフォーメーションのパートナーとして、未来への架け橋を創造します。
          </p>
          
          <div class="mt-10 flex flex-wrap gap-4">
            <a href="#works" class="group relative px-8 py-4 rounded-full overflow-hidden" data-magnetic>
              <div class="absolute inset-0 gradient-animate"></div>
              <span class="relative z-10 font-bold">プロジェクトを見る</span>
            </a>
            <a href="#contact" class="px-8 py-4 rounded-full glass border border-purple-400/50 hover:border-purple-300 transition-all" data-magnetic>
              <span class="font-bold">お問い合わせ</span>
            </a>
          </div>
          
          <!-- Stats -->
          <div class="mt-16 grid grid-cols-3 gap-8">
            <div class="text-center lg:text-left">
              <div class="text-3xl font-black text-purple-300 js-counter" data-to="250">0</div>
              <div class="text-sm text-purple-200/60 mt-1">Projects</div>
            </div>
            <div class="text-center lg:text-left">
              <div class="text-3xl font-black text-purple-300 js-counter" data-to="98">0</div>
              <div class="text-sm text-purple-200/60 mt-1">満足度 %</div>
            </div>
            <div class="text-center lg:text-left">
              <div class="text-3xl font-black text-purple-300 js-counter" data-to="15">0</div>
              <div class="text-sm text-purple-200/60 mt-1">Awards</div>
            </div>
          </div>
        </div>
        
        <!-- 3D Card -->
        <div class="lg:col-span-5 relative perspective-1000">
          <div id="heroCard" class="relative transform-3d">
            <div class="aspect-[4/5] rounded-3xl glass border border-purple-400/30 overflow-hidden glow-purple">
              <div class="absolute inset-0 gradient-animate opacity-20"></div>
              <div class="relative p-8 h-full flex flex-col justify-between">
                <div class="space-y-4">
                  <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                    <div class="h-full w-3/4 rounded-full gradient-animate"></div>
                  </div>
                  <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                    <div class="h-full w-1/2 rounded-full gradient-animate" style="animation-delay:0.5s"></div>
                  </div>
                  <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                    <div class="h-full w-2/3 rounded-full gradient-animate" style="animation-delay:1s"></div>
                  </div>
                </div>
                <div class="text-center">
                  <div class="text-6xl font-black text-glow mb-2">∞</div>
                  <p class="text-sm text-purple-200/80">Infinite Possibilities</p>
                </div>
              </div>
              <div class="absolute inset-0 bg-gradient-to-t from-purple-500/20 to-transparent pointer-events-none"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2">
      <div class="w-6 h-10 rounded-full border-2 border-purple-400/50 flex items-start justify-center p-1">
        <div class="w-1 h-3 bg-purple-400 rounded-full animate-bounce"></div>
      </div>
    </div>
  </section>

  <!-- ===== About ===== -->
  <section id="about" class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <div class="js-reveal">
          <span class="text-purple-400 font-medium">ABOUT US</span>
          <h2 class="mt-4 text-4xl sm:text-5xl font-black">
            デジタルの<br/>
            <span class="bg-gradient-to-r from-purple-300 to-violet-300 bg-clip-text text-transparent">最前線</span>を切り拓く
          </h2>
          <p class="mt-6 text-purple-100/70 leading-relaxed">
            フロントアークは、最新のテクノロジーとクリエイティビティを融合させ、企業のデジタルトランスフォーメーションを加速させます。私たちは単なるベンダーではなく、お客様の成長を共に実現するパートナーです。
          </p>
          <p class="mt-4 text-purple-100/70 leading-relaxed">
            AI、ブロックチェーン、クラウド技術を駆使し、業界の常識を覆すソリューションを提供。未来のビジネスモデルを、今ここで実現します。
          </p>
        </div>
        <div class="relative js-reveal">
          <div class="aspect-square rounded-3xl glass border border-purple-400/30 overflow-hidden">
            <div class="absolute inset-0 gradient-animate opacity-10"></div>
            <div class="p-12 h-full flex items-center justify-center">
              <div class="text-8xl font-black text-glow animate-pulse-glow">FA</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== Services ===== -->
  <section id="services" class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="text-purple-400 font-medium">OUR SERVICES</span>
        <h2 class="mt-4 text-4xl sm:text-5xl font-black">提供サービス</h2>
      </div>
      
      <div class="grid lg:grid-cols-3 gap-8">
        <!-- Service 1 -->
        <article class="group relative rounded-2xl glass border border-purple-400/30 p-8 hover:border-purple-300 transition-all js-reveal">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-violet-600/10 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
          <div class="relative">
            <div class="w-16 h-16 rounded-xl gradient-animate grid place-content-center text-2xl mb-6">💡</div>
            <h3 class="text-xl font-bold mb-3">Digital Strategy</h3>
            <p class="text-purple-100/70 text-sm leading-relaxed">
              ビジネス目標を実現するデジタル戦略を設計。市場分析から実装まで一貫してサポートします。
            </p>
            <div class="mt-6 flex items-center text-purple-400 text-sm font-medium group-hover:text-purple-300 transition-colors">
              <span>詳しく見る</span>
              <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </div>
          </div>
        </article>
        
        <!-- Service 2 -->
        <article class="group relative rounded-2xl glass border border-purple-400/30 p-8 hover:border-purple-300 transition-all js-reveal">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-violet-600/10 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
          <div class="relative">
            <div class="w-16 h-16 rounded-xl gradient-animate grid place-content-center text-2xl mb-6">🚀</div>
            <h3 class="text-xl font-bold mb-3">Web Development</h3>
            <p class="text-purple-100/70 text-sm leading-relaxed">
              最新技術を活用した高速・高品質なWebアプリケーション開発。パフォーマンスとUXを両立。
            </p>
            <div class="mt-6 flex items-center text-purple-400 text-sm font-medium group-hover:text-purple-300 transition-colors">
              <span>詳しく見る</span>
              <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </div>
          </div>
        </article>
        
        <!-- Service 3 -->
        <article class="group relative rounded-2xl glass border border-purple-400/30 p-8 hover:border-purple-300 transition-all js-reveal">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-violet-600/10 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
          <div class="relative">
            <div class="w-16 h-16 rounded-xl gradient-animate grid place-content-center text-2xl mb-6">🤖</div>
            <h3 class="text-xl font-bold mb-3">AI Integration</h3>
            <p class="text-purple-100/70 text-sm leading-relaxed">
              AIとMLを活用した次世代ソリューション。業務の自動化と最適化を実現します。
            </p>
            <div class="mt-6 flex items-center text-purple-400 text-sm font-medium group-hover:text-purple-300 transition-colors">
              <span>詳しく見る</span>
              <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- ===== Technology Stack ===== -->
  <section id="technology" class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="text-purple-400 font-medium">TECHNOLOGY</span>
        <h2 class="mt-4 text-4xl sm:text-5xl font-black">使用技術</h2>
      </div>
      
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="tech-card rounded-xl glass border border-purple-400/30 p-6 text-center hover:scale-105 transition-transform cursor-pointer">
          <div class="text-3xl mb-2">⚛️</div>
          <div class="font-medium">React</div>
        </div>
        <div class="tech-card rounded-xl glass border border-purple-400/30 p-6 text-center hover:scale-105 transition-transform cursor-pointer">
          <div class="text-3xl mb-2">📱</div>
          <div class="font-medium">Next.js</div>
        </div>
        <div class="tech-card rounded-xl glass border border-purple-400/30 p-6 text-center hover:scale-105 transition-transform cursor-pointer">
          <div class="text-3xl mb-2">🎨</div>
          <div class="font-medium">Tailwind</div>
        </div>
        <div class="tech-card rounded-xl glass border border-purple-400/30 p-6 text-center hover:scale-105 transition-transform cursor-pointer">
          <div class="text-3xl mb-2">🤖</div>
          <div class="font-medium">AI/ML</div>
        </div>
        <div class="tech-card rounded-xl glass border border-purple-400/30 p-6 text-center hover:scale-105 transition-transform cursor-pointer">
          <div class="text-3xl mb-2">☁️</div>
          <div class="font-medium">AWS</div>
        </div>
        <div class="tech-card rounded-xl glass border border-purple-400/30 p-6 text-center hover:scale-105 transition-transform cursor-pointer">
          <div class="text-3xl mb-2">🔗</div>
          <div class="font-medium">Blockchain</div>
        </div>
        <div class="tech-card rounded-xl glass border border-purple-400/30 p-6 text-center hover:scale-105 transition-transform cursor-pointer">
          <div class="text-3xl mb-2">📊</div>
          <div class="font-medium">Analytics</div>
        </div>
        <div class="tech-card rounded-xl glass border border-purple-400/30 p-6 text-center hover:scale-105 transition-transform cursor-pointer">
          <div class="text-3xl mb-2">🔒</div>
          <div class="font-medium">Security</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== Works (Horizontal Scroll) ===== -->
  <section id="works" class="py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
      <span class="text-purple-400 font-medium">PORTFOLIO</span>
      <h2 class="mt-4 text-4xl sm:text-5xl font-black">制作実績</h2>
    </div>
    
    <div id="hWrap">
      <div id="hPin" class="relative">
        <div id="hTrack" class="flex gap-8 px-4 sm:px-6 lg:px-8 will-change-transform">
          <!-- Dynamic cards will be inserted here -->
          <template id="workCardTemplate">
            <article class="work-card min-w-[85vw] sm:min-w-[50vw] lg:min-w-[35vw] rounded-3xl glass border border-purple-400/30 overflow-hidden group hover:border-purple-300 transition-all">
              <div class="aspect-[16/10] bg-gradient-to-br from-purple-600/20 to-violet-600/20 relative overflow-hidden">
                <div class="absolute inset-0 gradient-animate opacity-20"></div>
                <div class="absolute bottom-4 left-4 right-4">
                  <span class="inline-block px-3 py-1 rounded-full glass text-xs font-medium mb-2">Web Design</span>
                  <h3 class="text-xl font-bold">Project Title</h3>
                </div>
              </div>
              <div class="p-6">
                <p class="text-purple-100/70 text-sm">革新的なデザイン