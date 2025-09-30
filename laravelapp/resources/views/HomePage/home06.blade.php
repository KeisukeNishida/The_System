<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>株式会社フロントアーク｜デジタルトランスフォーメーション・Web開発なら｜デジタルのことは全部丸投げ</title>
  <meta name="description" content="フロントアーク - 24時間365日サポート体制。Web開発・クラウド構築・デジタルマーケティングまで、貴社のデジタルパートナーとして全面サポート。" />
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            purple: {
              50:'#FAF5FF',100:'#F3E8FF',200:'#E9D5FF',300:'#D8B4FE',
              400:'#C084FC',500:'#A855F7',600:'#9333EA',700:'#7E22CE',
              800:'#6B21A8',900:'#581C87',950:'#3B0764'
            },
            dark: {
              900: '#0F0517',
              800: '#1A0E2E',
              700: '#2D1B4E'
            }
          },
          fontFamily: {
            sans: ['Noto Sans JP', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
            display: ['Oswald', 'Noto Sans JP', 'sans-serif']
          },
          animation: {
            'float': 'float 6s ease-in-out infinite',
            'slide-up': 'slideUp 0.6s ease-out',
            'fade-in': 'fadeIn 0.8s ease-out',
            'scale-in': 'scaleIn 0.5s ease-out'
          },
          backgroundImage: {
            'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700;900&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet" />
  
  <style>
    @keyframes float { 
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes scaleIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }
    
    html { scroll-behavior: smooth; }
    
    .gradient-text {
      background: linear-gradient(135deg, #A855F7 0%, #7E22CE 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .glass-effect {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .service-card {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(168, 85, 247, 0.2);
      transition: all 0.3s ease;
    }
    
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(168, 85, 247, 0.2);
      border-color: rgba(168, 85, 247, 0.4);
    }
    
    .hero-bg {
      background: linear-gradient(180deg, #0F0517 0%, #1A0E2E 50%, #0F0517 100%);
      position: relative;
      overflow: hidden;
    }
    
    .hero-bg::before {
      content: '';
      position: absolute;
      width: 200%;
      height: 200%;
      top: -50%;
      left: -50%;
      background: url('data:image/svg+xml;utf8,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%239333EA" fill-opacity="0.03"><path d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/></g></g></svg>');
      animation: float 20s ease-in-out infinite;
    }
    
    .number-bg {
      font-size: 200px;
      position: absolute;
      opacity: 0.03;
      font-weight: 900;
      z-index: 0;
      user-select: none;
    }
    
    .scroll-hidden {
      opacity: 0;
      transform: translateY(50px);
    }
    
    .scroll-visible {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.8s ease-out;
    }
    
    .hover-lift {
      transition: transform 0.3s ease;
    }
    
    .hover-lift:hover {
      transform: translateY(-8px);
    }
  </style>
</head>
<body class="bg-dark-900 text-white font-sans overflow-x-hidden">

  <!-- Header -->
  <header class="fixed top-0 w-full z-50 glass-effect">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <div class="flex items-center">
          <a href="#" class="flex items-center space-x-3">
            <div class="relative">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-xl">FA</span>
              </div>
              <div class="absolute -inset-1 bg-purple-500 rounded-lg blur-lg opacity-30"></div>
            </div>
            <div>
              <span class="font-bold text-xl">FrontArc</span>
              <span class="block text-xs text-purple-400">Digital Innovation Partner</span>
            </div>
          </a>
        </div>
        
        <nav class="hidden lg:flex items-center space-x-8">
          <a href="#services" class="hover:text-purple-400 transition">サービス</a>
          <a href="#reasons" class="hover:text-purple-400 transition">選ばれる理由</a>
          <a href="#case" class="hover:text-purple-400 transition">導入事例</a>
          <a href="#company" class="hover:text-purple-400 transition">会社情報</a>
          <a href="#contact" class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-3 rounded-full hover:from-purple-700 hover:to-purple-800 transition">
            無料相談はこちら
          </a>
        </nav>
        
        <button id="mobile-menu" class="lg:hidden text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero-bg min-h-screen flex items-center relative pt-20">
    <div class="absolute inset-0 bg-gradient-radial from-purple-900/20 via-transparent to-transparent"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="text-center animate-fade-in">
        <h1 class="text-5xl lg:text-7xl font-display font-bold mb-6 leading-tight">
          <span class="gradient-text">貴社を支える</span><br />
          <span class="text-white">私たちのサービス</span>
        </h1>
        <p class="text-xl lg:text-2xl text-gray-300 mb-4 font-light">
          beyond's service
        </p>
        <p class="text-lg text-gray-400 max-w-3xl mx-auto mb-10">
          Web開発・クラウド構築・デジタルマーケティングまで<br />
          フルスタックでサポートする技術集団
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="#contact" class="bg-gradient-to-r from-purple-600 to-purple-700 px-8 py-4 rounded-full text-lg font-bold hover:from-purple-700 hover:to-purple-800 transition transform hover:scale-105">
            今すぐ無料相談
          </a>
          <a href="#services" class="border-2 border-purple-600 px-8 py-4 rounded-full text-lg font-bold hover:bg-purple-600/20 transition">
            サービスを見る
          </a>
        </div>
      </div>
      
      <!-- Floating elements -->
      <div class="absolute top-20 right-10 w-20 h-20 bg-purple-500/20 rounded-full blur-xl animate-float"></div>
      <div class="absolute bottom-20 left-10 w-32 h-32 bg-purple-600/20 rounded-full blur-xl animate-float" style="animation-delay: 2s;"></div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2">
      <div class="w-6 h-10 border-2 border-purple-500 rounded-full flex justify-center">
        <div class="w-1 h-3 bg-purple-500 rounded-full mt-2 animate-bounce"></div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="py-20 lg:py-32 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16 scroll-hidden">
        <span class="text-purple-400 text-sm font-bold tracking-wider uppercase">Our Services</span>
        <h2 class="text-4xl lg:text-5xl font-display font-bold mt-4 mb-6">
          <span class="gradient-text">デジタルソリューション</span>
        </h2>
        <p class="text-gray-400 max-w-2xl mx-auto">
          最新技術を駆使した包括的なデジタルサービスで、貴社のビジネスを次のステージへ
        </p>
      </div>

      <!-- Service Categories -->
      <div class="space-y-24">
        <!-- Web Development -->
        <div class="scroll-hidden">
          <div class="flex items-center mb-8">
            <div class="w-16 h-1 bg-purple-600 mr-4"></div>
            <h3 class="text-2xl lg:text-3xl font-bold">Web開発 / システム開発</h3>
            <div class="ml-4 text-purple-400 font-display text-sm">Web Development</div>
          </div>
          
          <div class="grid lg:grid-cols-2 gap-8">
            <div class="service-card p-8 rounded-2xl">
              <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
              </div>
              <h4 class="text-xl font-bold mb-3">フルスタック開発</h4>
              <p class="text-gray-400 mb-4">
                React、Vue.js、Next.jsなどのモダンなフレームワークを活用し、高速で拡張性の高いWebアプリケーションを開発。UI/UXデザインから実装まで一貫して対応いたします。
              </p>
              <ul class="text-sm text-gray-500 space-y-1">
                <li>• SPAアプリケーション開発</li>
                <li>• PWA対応</li>
                <li>• レスポンシブデザイン</li>
              </ul>
            </div>
            
            <div class="service-card p-8 rounded-2xl">
              <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
              </div>
              <h4 class="text-xl font-bold mb-3">バックエンド・API開発</h4>
              <p class="text-gray-400 mb-4">
                スケーラブルなマイクロサービスアーキテクチャの設計・実装。RESTful API、GraphQL、WebSocketを活用したリアルタイム通信システムの構築。
              </p>
              <ul class="text-sm text-gray-500 space-y-1">
                <li>• Node.js / Python / Go</li>
                <li>• データベース設計・最適化</li>
                <li>• 大規模トラフィック対応</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Cloud Infrastructure -->
        <div class="scroll-hidden">
          <div class="flex items-center mb-8">
            <div class="w-16 h-1 bg-purple-600 mr-4"></div>
            <h3 class="text-2xl lg:text-3xl font-bold">クラウド / インフラ構築</h3>
            <div class="ml-4 text-purple-400 font-display text-sm">Cloud Infrastructure</div>
          </div>
          
          <div class="grid lg:grid-cols-3 gap-6">
            <div class="service-card p-6 rounded-xl hover-lift">
              <div class="w-10 h-10 bg-purple-600/20 rounded-lg flex items-center justify-center mb-4">
                <span class="text-purple-400 text-xl">☁️</span>
              </div>
              <h5 class="font-bold mb-2">AWS構築・運用</h5>
              <p class="text-sm text-gray-400">
                EC2、S3、Lambda、RDSなどAWSの各種サービスを最適に組み合わせ、コスト効率の高いインフラを構築
              </p>
            </div>
            
            <div class="service-card p-6 rounded-xl hover-lift">
              <div class="w-10 h-10 bg-purple-600/20 rounded-lg flex items-center justify-center mb-4">
                <span class="text-purple-400 text-xl">🔄</span>
              </div>
              <h5 class="font-bold mb-2">CI/CD環境構築</h5>
              <p class="text-sm text-gray-400">
                GitHub Actions、GitLab CI、Jenkins等を活用した自動化パイプラインの構築で開発効率を最大化
              </p>
            </div>
            
            <div class="service-card p-6 rounded-xl hover-lift">
              <div class="w-10 h-10 bg-purple-600/20 rounded-lg flex items-center justify-center mb-4">
                <span class="text-purple-400 text-xl">🐳</span>
              </div>
              <h5 class="font-bold mb-2">コンテナ化・K8s</h5>
              <p class="text-sm text-gray-400">
                Docker、Kubernetesを活用したコンテナオーケストレーション環境の設計・構築・運用
              </p>
            </div>
          </div>
        </div>

        <!-- Digital Marketing -->
        <div class="scroll-hidden">
          <div class="flex items-center mb-8">
            <div class="w-16 h-1 bg-purple-600 mr-4"></div>
            <h3 class="text-2xl lg:text-3xl font-bold">デジタルマーケティング</h3>
            <div class="ml-4 text-purple-400 font-display text-sm">Digital Marketing</div>
          </div>
          
          <div class="grid lg:grid-cols-2 gap-8">
            <div class="service-card p-8 rounded-2xl">
              <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
              </div>
              <h4 class="text-xl font-bold mb-3">SEO・コンテンツマーケティング</h4>
              <p class="text-gray-400 mb-4">
                検索エンジン最適化とコンテンツ戦略で、オーガニックトラフィックを最大化。データドリブンなアプローチで継続的な改善を実現。
              </p>
              <ul class="text-sm text-gray-500 space-y-1">
                <li>• テクニカルSEO</li>
                <li>• コンテンツSEO戦略</li>
                <li>• ローカルSEO最適化</li>
              </ul>
            </div>
            
            <div class="service-card p-8 rounded-2xl">
              <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
              </div>
              <h4 class="text-xl font-bold mb-3">Web広告運用・分析</h4>
              <p class="text-gray-400 mb-4">
                Google Ads、Facebook広告などの運用代行。A/Bテストによる最適化で、ROASを継続的に改善します。
              </p>
              <ul class="text-sm text-gray-500 space-y-1">
                <li>• リスティング広告</li>
                <li>• ディスプレイ広告</li>
                <li>• SNS広告運用</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3 Reasons Section -->
  <section id="reasons" class="py-20 lg:py-32 bg-gradient-to-b from-dark-900 to-dark-800 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16 scroll-hidden">
        <span class="text-purple-400 text-sm font-bold tracking-wider uppercase">Why Choose Us</span>
        <h2 class="text-4xl lg:text-5xl font-display font-bold mt-4 mb-6">
          3つの選ばれる理由
        </h2>
      </div>
      
      <div class="space-y-20">
        <!-- Reason 1 -->
        <div class="grid lg:grid-cols-2 gap-12 items-center scroll-hidden">
          <div class="relative">
            <span class="number-bg text-purple-600/10 -left-10 -top-10">01</span>
            <div class="relative z-10">
              <div class="inline-block px-4 py-2 bg-purple-600/20 rounded-full mb-6">
                <span class="text-purple-400 font-bold">運用保守</span>
                <span class="text-white ml-2">001</span>
              </div>
              <h3 class="text-3xl font-bold mb-4">
                24時間365日<br />
                <span class="gradient-text">フルサポート対応</span>
              </h3>
              <p class="text-gray-400 mb-6">
                専門エンジニアによる、有人対応・技術サポート
              </p>
              <p class="text-gray-300">
                システム障害時におけるトラブル対応を迅速なものにするために、日本・海外の拠点間での24時間365日の連携体制で、常時エンジニアによる運用保守・技術サポートをおこないます。
              </p>
              <p class="text-gray-300 mt-4">
                万が一、サービスがダウンする事態に見舞われたとしても、素早い復旧対応で被害を最小限に抑えます。
              </p>
            </div>
          </div>
          <div class="relative">
            <div class="glass-effect p-8 rounded-2xl">
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-purple-600/10 p-4 rounded-xl text-center">
                  <div class="text-3xl font-bold gradient-text">24/7</div>
                  <div class="text-sm text-gray-400">サポート体制</div>
                </div>
                <div class="bg-purple-600/10 p-4 rounded-xl text-center">
                  <div class="text-3xl font-bold gradient-text">30分</div>
                  <div class="text-sm text-gray-400">平均応答時間</div>
                </div>
              </div>
              <div class="space-y-3">
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-purple-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                  <span class="text-gray-300">専任エンジニア体制</span>
                </div>
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-purple-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                  <span class="text-gray-300">リアルタイム監視</span>
                </div>
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-purple-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                  <span class="text-gray-300">障害予防対策</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Reason 2 -->
        <div class="grid lg:grid-cols-2 gap-12 items-center scroll-hidden">
          <div class="order-2 lg:order-1 relative">
            <div class="glass-effect p-8 rounded-2xl">
              <div class="mb-6">
                <div class="text-sm text-purple-400 mb-2">実績データ</div>
                <div class="grid grid-cols-3 gap-4">
                  <div>
                    <div class="text-2xl font-bold text-white">500+</div>
                    <div class="text-xs text-gray-400">プロジェクト</div>
                  </div>
                  <div>
                    <div class="text-2xl font-bold text-white">50M+</div>
                    <div class="text-xs text-gray-400">月間PV</div>
                  </div>
                  <div>
                    <div class="text-2xl font-bold text-white">99.9%</div>
                    <div class="text-xs text-gray-400">稼働率</div>
                  </div>
                </div>
              </div>
              <div class="space-y-3">
                <div class="bg-purple-600/10 p-3 rounded-lg">
                  <div class="text-sm font-medium mb-1">ECサイト</div>
                  <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-700 h-2 rounded-full" style="width: 85%"></div>
                  </div>
                </div>
                <div class="bg-purple-600/10 p-3 rounded-lg">
                  <div class="text-sm font-medium mb-1">メディアサイト</div>
                  <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-700 h-2 rounded-full" style="width: 90%"></div>
                  </div>
                </div>
                <div class="bg-purple-600/10 p-3 rounded-lg">
                  <div class="text-sm font-medium mb-1">SaaSプラットフォーム</div>
                  <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-700 h-2 rounded-full" style="width: 75%"></div>
                  </div>
                </div>