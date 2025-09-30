<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FrontArc株式会社 | 担当者を選べるデジタルソリューション企業</title>
  <meta name="description" content="フロントアーク株式会社 - 結果にコミットするデジタルマーケティング・Web開発のプロフェッショナル集団。担当者を選んで最適なソリューションを。" />
  
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
            }
          },
          fontFamily: {
            sans: ['Noto Sans JP', 'system-ui', 'sans-serif'],
          },
          animation: {
            'fade-up': 'fadeUp 0.5s ease-out',
            'fade-in': 'fadeIn 0.3s ease-out',
            'slide-in': 'slideIn 0.4s ease-out',
            'count': 'count 2s ease-out'
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700;900&display=swap" rel="stylesheet" />
  
  <style>
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateX(-20px); }
      to { opacity: 1; transform: translateX(0); }
    }
    .animate-fade-up { animation: fadeUp 0.5s ease-out; }
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    .animate-slide-in { animation: slideIn 0.4s ease-out; }
    
    /* Smooth scroll */
    html { scroll-behavior: smooth; }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #9333EA; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #7E22CE; }
    
    /* Hover effects */
    .hover-lift {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(147, 51, 234, 0.15);
    }
    
    /* Gradient text */
    .gradient-text {
      background: linear-gradient(135deg, #9333EA 0%, #7E22CE 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    /* Section observer */
    .section-hidden {
      opacity: 0;
      transform: translateY(30px);
    }
    .section-show {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.6s ease-out;
    }
  </style>
</head>
<body class="font-sans text-gray-900 bg-white">

  <!-- Header -->
  <header class="fixed top-0 w-full bg-white/95 backdrop-blur-sm border-b border-gray-100 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16 lg:h-20">
        <div class="flex items-center">
          <a href="#" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-purple-700 rounded-lg flex items-center justify-center text-white font-bold text-lg">
              F
            </div>
            <span class="font-bold text-xl text-gray-900">FrontArc</span>
          </a>
        </div>
        
        <nav class="hidden lg:flex items-center space-x-8">
          <a href="#vision" class="text-gray-700 hover:text-purple-600 font-medium transition">Vision</a>
          <a href="#services" class="text-gray-700 hover:text-purple-600 font-medium transition">サービス</a>
          <a href="#specialists" class="text-gray-700 hover:text-purple-600 font-medium transition">スペシャリスト</a>
          <a href="#results" class="text-gray-700 hover:text-purple-600 font-medium transition">実績</a>
          <a href="#company" class="text-gray-700 hover:text-purple-600 font-medium transition">会社概要</a>
          <a href="#contact" class="bg-purple-600 text-white px-6 py-2.5 rounded-full hover:bg-purple-700 transition font-medium">
            無料相談
          </a>
        </nav>
        
        <!-- Mobile menu button -->
        <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-md text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
      
      <!-- Mobile menu -->
      <div id="mobile-menu" class="hidden lg:hidden py-4 border-t border-gray-100">
        <a href="#vision" class="block py-2 text-gray-700 hover:text-purple-600">Vision</a>
        <a href="#services" class="block py-2 text-gray-700 hover:text-purple-600">サービス</a>
        <a href="#specialists" class="block py-2 text-gray-700 hover:text-purple-600">スペシャリスト</a>
        <a href="#results" class="block py-2 text-gray-700 hover:text-purple-600">実績</a>
        <a href="#company" class="block py-2 text-gray-700 hover:text-purple-600">会社概要</a>
        <a href="#contact" class="block mt-4 bg-purple-600 text-white text-center py-3 rounded-full hover:bg-purple-700">
          無料相談
        </a>
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="pt-24 lg:pt-32 pb-16 lg:pb-24 bg-gradient-to-br from-purple-50 via-white to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center animate-fade-up">
        <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 leading-tight">
          <span class="gradient-text">担当者を選べる</span><br />
          デジタルソリューション企業
        </h1>
        <p class="mt-6 text-xl text-gray-600 max-w-3xl mx-auto">
          各分野のスペシャリストから最適な担当者を選択。<br />
          あなたのビジネスに最適なソリューションをご提供します。
        </p>
        
        <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
          <a href="#contact" class="bg-purple-600 text-white px-8 py-4 rounded-full hover:bg-purple-700 transition font-medium text-lg hover-lift">
            無料で相談する
          </a>
          <a href="#download" class="bg-white text-purple-600 px-8 py-4 rounded-full border-2 border-purple-600 hover:bg-purple-50 transition font-medium text-lg">
            資料ダウンロード
          </a>
        </div>
      </div>
      
      <!-- Stats -->
      <div class="mt-20 grid grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="text-center">
          <div class="text-4xl font-bold gradient-text counter" data-target="98">0</div>
          <div class="mt-2 text-gray-600">顧客満足度</div>
        </div>
        <div class="text-center">
          <div class="text-4xl font-bold gradient-text counter" data-target="500">0</div>
          <div class="mt-2 text-gray-600">プロジェクト実績</div>
        </div>
        <div class="text-center">
          <div class="text-4xl font-bold gradient-text counter" data-target="50">0</div>
          <div class="mt-2 text-gray-600">専門スペシャリスト</div>
        </div>
        <div class="text-center">
          <div class="text-4xl font-bold gradient-text counter" data-target="24">0</div>
          <div class="mt-2 text-gray-600">サポート対応時間</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Vision Section -->
  <section id="vision" class="py-16 lg:py-24 bg-gray-50 section-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Vision</h2>
        <div class="mt-4 w-20 h-1 bg-purple-600 mx-auto"></div>
      </div>
      
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div>
          <h3 class="text-2xl font-bold text-gray-900 mb-6">
            Web業務を発注するとき、<br />
            こんな不安はありませんか？
          </h3>
          <div class="space-y-4">
            <div class="flex items-start">
              <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <p class="ml-3 text-gray-700">担当者のスキルにばらつきがある</p>
            </div>
            <div class="flex items-start">
              <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <p class="ml-3 text-gray-700">成果が見えづらく費用対効果が不明瞭</p>
            </div>
            <div class="flex items-start">
              <svg class="w-6 h-6 text-red-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <p class="ml-3 text-gray-700">レスポンスが遅く、プロジェクトが遅延する</p>
            </div>
          </div>
        </div>
        
        <div>
          <h3 class="text-2xl font-bold text-gray-900 mb-6">
            FrontArcの特徴
          </h3>
          <p class="text-gray-700 mb-6">
            顧客満足度に基づくコンサル評価、ランキング制度、社内コンペシステムなど、
            さまざまな競争環境を整備することで、顧客の不安ゼロを目指します。
          </p>
          <div class="space-y-4">
            <div class="flex items-start">
              <svg class="w-6 h-6 text-purple-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <p class="ml-3 text-gray-700">専門分野に特化したスペシャリストを選択可能</p>
            </div>
            <div class="flex items-start">
              <svg class="w-6 h-6 text-purple-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <p class="ml-3 text-gray-700">成果報酬型で透明性の高い料金体系</p>
            </div>
            <div class="flex items-start">
              <svg class="w-6 h-6 text-purple-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <p class="ml-3 text-gray-700">24時間以内の迅速なレスポンス保証</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="py-16 lg:py-24 section-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">サービス</h2>
        <div class="mt-4 w-20 h-1 bg-purple-600 mx-auto"></div>
      </div>
      
      <div class="grid lg:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-lg hover-lift">
          <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">コンサルティング</h3>
          <p class="text-gray-600 mb-6">
            手段としてのWeb集客における設計と結果を出すための伴走支援サービスです
          </p>
          <a href="#" class="text-purple-600 font-medium hover:text-purple-700 transition">
            詳しく見る →
          </a>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-lg hover-lift">
          <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">プロデュース</h3>
          <p class="text-gray-600 mb-6">
            事業として利益を作るための設計〜結果を出すまでの伴走支援サービスです
          </p>
          <a href="#" class="text-purple-600 font-medium hover:text-purple-700 transition">
            詳しく見る →
          </a>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-lg hover-lift">
          <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">実行支援</h3>
          <p class="text-gray-600 mb-6">
            成果を出すための実働を巻き取る実行支援サービスです
          </p>
          <a href="#" class="text-purple-600 font-medium hover:text-purple-700 transition">
            詳しく見る →
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Specialists Section -->
  <section id="specialists" class="py-16 lg:py-24 bg-gray-50 section-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">スペシャリスト</h2>
        <div class="mt-4 w-20 h-1 bg-purple-600 mx-auto"></div>
        <p class="mt-6 text-gray-600 max-w-2xl mx-auto">
          各分野のプロフェッショナルが、あなたのビジネスを成功に導きます
        </p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Specialist Card 1 -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift cursor-pointer">
          <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600"></div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900">山田 太郎</h3>
            <p class="text-purple-600 font-medium mt-1">Webマーケティングの達人</p>
            <p class="text-gray-600 mt-3">SEO・SEM分野で15年の実績。大手企業のコンバージョン率を300%改善。</p>
            <div class="mt-4 flex gap-2">
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">SEO</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">SEM</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Analytics</span>
            </div>
          </div>
        </div>
        
        <!-- Specialist Card 2 -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift cursor-pointer">
          <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600"></div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900">佐藤 花子</h3>
            <p class="text-purple-600 font-medium mt-1">UI/UXデザインのプロ</p>
            <p class="text-gray-600 mt-3">ユーザー中心設計で、利用率を平均150%向上。国際デザイン賞受賞。</p>
            <div class="mt-4 flex gap-2">
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">UI/UX</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Design</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Figma</span>
            </div>
          </div>
        </div>
        
        <!-- Specialist Card 3 -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift cursor-pointer">
          <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600"></div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900">鈴木 一郎</h3>
            <p class="text-purple-600 font-medium mt-1">開発のスペシャリスト</p>
            <p class="text-gray-600 mt-3">フルスタックエンジニア。大規模システムの設計・開発で20年の経験。</p>
            <div class="mt-4 flex gap-2">
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">React</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Node.js</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">AWS</span>
            </div>
          </div>
        </div>
        
        <!-- Specialist Card 4 -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift cursor-pointer">
          <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600"></div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900">田中 美咲</h3>
            <p class="text-purple-600 font-medium mt-1">SNSマーケティングの女王</p>
            <p class="text-gray-600 mt-3">フォロワー合計500万人を運用。バズコンテンツ創出のエキスパート。</p>
            <div class="mt-4 flex gap-2">
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">SNS</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Content</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Growth</span>
            </div>
          </div>
        </div>
        
        <!-- Specialist Card 5 -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift cursor-pointer">
          <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600"></div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900">高橋 健</h3>
            <p class="text-purple-600 font-medium mt-1">データ分析のプロフェッショナル</p>
            <p class="text-gray-600 mt-3">ビッグデータ解析でROIを平均200%改善。AI活用の第一人者。</p>
            <div class="mt-4 flex gap-2">
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Data</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">AI/ML</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Python</span>
            </div>
          </div>
        </div>
        
        <!-- Specialist Card 6 -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift cursor-pointer">
          <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600"></div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900">渡辺 翔</h3>
            <p class="text-purple-600 font-medium mt-1">動画マーケティングのプロ</p>
            <p class="text-gray-600 mt-3">YouTube総再生回数10億回突破。動画戦略で売上を5倍に成長。</p>
            <div class="mt-4 flex gap-2">
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">YouTube</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Video</span>
              <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">編集</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Results Section -->
  <section id="results" class="py-16 lg:py-24 section-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">導入実績</h2>
        <div class="mt-4 w-20 h-1 bg-purple-600 mx-auto"></div>
      </div>
      
      <div class="grid lg:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-lg">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 100 4h2a2 2 0 100-4h-.5a1 1 0 000-2H8a2 2 0 114 0h.5a1 1 0 100 2H14a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 011-1h.5a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h.5a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h.5a1 1 0 110 2H8a1 1 0 01-1-1zm3-6a1 1 0 011-1h.5a1 1 0 110 2H11a1 1 0 01-1-1zm0 3a1 1 0 011-1h.5a1 1 0 110 2H11a1 1 0 01-1-1zm0 3a1 1 0 011-1h.5a1 1 0 110 2H11a1 1 0 01-1-1z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-gray-900">3.5倍</p>
              <p class="text-gray-600">平均売上成長率</p>
            </div>
          </div>
          <p class="text-gray-600">導入企業の平均売上成長率は3.5倍を達成。持続的な成長を実現します。</p>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-lg">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-gray-900">250%</p>
              <p class="text-gray-600">CVR改善率</p>
            </div>
          </div>
          <p class="text-gray-600">Webサイトのコンバージョン率を平均250%改善。売上に直結する成果を提供。</p>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-lg">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-gray-900">180%</p>
              <p class="text-gray-600">ROI向上率</p>
            </div>
          </div>
          <p class="text-gray-600">投資対効果を平均180%向上。最小のコストで最大の成果を実現します。</p>
        </div>
      </div>
      
      <!-- Client Logos -->
      <div class="mt-16">
        <p class="text-center text-gray-600 mb-8">導入企業様（一部）</p>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
          <div class="bg-gray-100 h-20 rounded-lg flex items-center justify-center">
            <span class="text-gray-400 font-bold">Client A</span>
          </div>
          <div class="bg-gray-100 h-20 rounded-lg flex items-center justify-center">
            <span class="text-gray-400 font-bold">Client B</span>
          </div>
          <div class="bg-gray-100 h-20 rounded-lg flex items-center justify-center">
            <span class="text-gray-400 font-bold">Client C</span>
          </div>
          <div class="bg-gray-100 h-20 rounded-lg flex items-center justify-center">
            <span class="text-gray-400 font-bold">Client D</span>
          </div>
          <div class="bg-gray-100 h-20 rounded-lg flex items-center justify-center">
            <span class="text-gray-400 font-bold">Client E</span>
          </div>
          <div class="bg-gray-100 h-20 rounded-lg flex items-center justify-center">
            <span class="text-gray-400 font-bold">Client F</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-16 lg:py-24 bg-gradient-to-br from-purple-600 to-purple-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">
        お仕事のご依頼・ご相談
      </h2>
      <p class="text-xl text-purple-100 mb-10">
        各Web領域に精通したコンサルタントに無料でご相談可能です。<br />
        デジタル支援は「日本一競争が激しいFrontArc」にお任せください。
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="#contact" class="bg-white text-purple-600 px-8 py-4 rounded-full hover:bg-gray-100 transition font-bold text-lg hover-lift">
          無料相談を申し込む
        </a>
        <a href="#download" class="bg-purple-500 text-white px-8 py-4 rounded-full hover:bg-purple-400 transition font-bold text-lg">
          会社資料をダウンロード
        </a>
      </div>
    </div>
  </section>

  <!-- Contact Form -->
  <section id="contact" class="py-16 lg:py-24 bg-gray-50 section-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">お問い合わせ</h2>
        <div class="mt-4 w-20 h-1 bg-purple-600 mx-auto"></div>
        <p class="mt-6 text-gray-600">
          まずはお気軽にご相談ください。24時間以内にご返信いたします。
        </p>
      </div>
      
      <form class="bg-white p-8 rounded-2xl shadow-lg">
        <div class="grid lg:grid-cols-2 gap-6 mb-6">
          <div>
            <label class="block text-gray-700 font-medium mb-2">お名前 <span class="text-red-500">*</span></label>
            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition" placeholder="山田 太郎">
          </div>
          <div>
            <label class="block text-gray-700 font-medium mb-2">会社名</label>
            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition" placeholder="株式会社〇〇">
          </div>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-6 mb-6">
          <div>
            <label class="block text-gray-700 font-medium mb-2">メールアドレス <span class="text-red-500">*</span></label>
            <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition" placeholder="example@company.com">
          </div>
          <div>
            <label class="block text-gray-700 font-medium mb-2">電話番号</label>
            <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition" placeholder="03-1234-5678">
          </div>
        </div>
        
        <div class="mb-6">
          <label class="block text-gray-700 font-medium mb-2">ご相談内容 <span class="text-red-500">*</span></label>
          <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition">
            <option>選択してください</option>
            <option>Webサイト制作について</option>
            <option>マーケティング支援について</option>
            <option>システム開発について</option>
            <option>その他</option>
          </select>
        </div>
        
        <div class="mb-6">
          <label class="block text-gray-700 font-medium mb-2">詳細内容</label>
          <textarea rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition" placeholder="ご相談内容の詳細をご記入ください"></textarea>
        </div>
        
        <div class="mb-6">
          <label class="flex items-start">
            <input type="checkbox" class="mt-1 mr-3">
            <span class="text-gray-600 text-sm">
              <a href="#" class="text-purple-600 hover:underline">プライバシーポリシー</a>に同意する
            </span>
          </label>
        </div>
        
        <button type="submit" class="w-full bg-purple-600 text-white py-4 rounded-full hover:bg-purple-700 transition font-bold text-lg">
          送信する
        </button>
      </form>
    </div>
  </section>

  <!-- Download Section -->
  <section id="download" class="py-16 lg:py-24 section-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white p-8 lg:p-12 rounded-2xl shadow-lg">
        <div class="text-center mb-8">
          <h2 class="text-3xl font-bold text-gray-900">会社資料のダウンロード</h2>
          <p class="mt-4 text-gray-600">
            まずは社内で検討したい方、情報収集段階の方はご自由にダウンロードください。<br />
            非常識な営業等はございませんのでご安心ください。
          </p>
        </div>
        
        <form class="max-w-md mx-auto">
          <div class="mb-4">
            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition" placeholder="お名前">
          </div>
          <div class="mb-4">
            <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition" placeholder="メールアドレス">
          </div>
          <div class="mb-4">
            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition" placeholder="会社名">
          </div>
          <button type="submit" class="w-full bg-purple-600 text-white py-4 rounded-full hover:bg-purple-700 transition font-bold">
            資料をダウンロード
          </button>
        </form>
      </div>
    </div>
  </section>

  <!-- Company Info -->
  <section id="company" class="py-16 lg:py-24 bg-gray-50 section-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">会社概要</h2>
        <div class="mt-4 w-20 h-1 bg-purple-600 mx-auto"></div>
      </div>
      
      <div class="bg-white p-8 rounded-2xl shadow-lg">
        <table class="w-full">
          <tbody class="divide-y divide-gray-200">
            <tr>
              <td class="py-4 text-gray-700 font-medium w-1/3">会社名</td>
              <td class="py-4 text-gray-900">フロントアーク株式会社</td>
            </tr>
            <tr>
              <td class="py-4 text-gray-700 font-medium">設立</td>
              <td class="py-4 text-gray-900">2020年4月</td>
            </tr>
            <tr>
              <td class="py-4 text-gray-700 font-medium">代表取締役</td>
              <td class="py-4 text-gray-900">前田 健太</td>
            </tr>
            <tr>
              <td class="py-4 text-gray-700 font-medium">資本金</td>
              <td class="py-4 text-gray-900">3,000万円</td>
            </tr>
            <tr>
              <td class="py-4 text-gray-700 font-medium">従業員数</td>
              <td class="py-4 text-gray-900">50名</td>
            </tr>
            <tr>
              <td class="py-4 text-gray-700 font-medium">所在地</td>
              <td class="py-4 text-gray-900">〒150-0001 東京都渋谷区神宮前1-2-3 フロントアークビル</td>
            </tr>
            <tr>
              <td class="py-4 text-gray-700 font-medium">事業内容</td>
              <td class="py-4 text-gray-900">
                デジタルマーケティング支援<br />
                Webサイト・アプリケーション開発<br />
                システムコンサルティング<br />
                DX推進支援
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-4 gap-8">
        <div>
          <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center font-bold text-lg">
              F
            </div>
            <span class="font-bold text-xl">FrontArc</span>
          </div>
          <p class="text-gray-400">
            デジタルイノベーションで<br />
            ビジネスの未来を創造
          </p>
        </div>
        
        <div>
          <h3 class="font-bold mb-4">サービス</h3>
          <ul class="space-y-2 text-gray-400">
            <li><a href="#" class="hover:text-white transition">コンサルティング</a></li>
            <li><a href="#" class="hover:text-white transition">プロデュース</a></li>
            <li><a href="#" class="hover:text-white transition">実行支援</a></li>
            <li><a href="#" class="hover:text-white transition">教育・研修</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="font-bold mb-4">企業情報</h3>
          <ul class="space-y-2 text-gray-400">
            <li><a href="#" class="hover:text-white transition">会社概要</a></li>
            <li><a href="#" class="hover:text-white transition">採用情報</a></li>
            <li><a href="#" class="hover:text-white transition">ニュース</a></li>
            <li><a href="#" class="hover:text-white transition">お問い合わせ</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="font-bold mb-4">フォロー</h3>
          <div class="flex space-x-4">
            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
              </svg>
            </a>
            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
              </svg>
            </a>
            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
      
      <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-400">
        <p>&copy; 2024 FrontArc Inc. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function() {
      // Mobile menu toggle
      $('#mobile-menu-btn').click(function() {
        $('#mobile-menu').toggleClass('hidden');
      });
      
      // Counter animation
      function animateCounter($el, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
          current += increment;
          if (current >= target) {
            current = target;
            clearInterval(timer);
          }
          $el.text(Math.floor(current));
        }, 20);
      }
      
      // Intersection Observer for animations
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('section-show');
            
            // Trigger counter animation
            $(entry.target).find('.counter').each(function() {
              const target = parseInt($(this).data('target'));
              if (!$(this).data('animated')) {
                $(this).data('animated', true);
                animateCounter($(this), target);
              }
            });
          }
        });
      }, observerOptions);
      
      // Observe all sections
      document.querySelectorAll('.section-hidden').forEach(el => {
        observer.observe(el);
      });
      
      // Smooth scroll for anchor links
      $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
          $('html, body').animate({
            scrollTop: target.offset().top - 80
          }, 800);
        }
      });
      
      // Header scroll effect
      $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
          $('header').addClass('shadow-lg');
        } else {
          $('header').removeClass('shadow-lg');
        }
      });
    });
  </script>
</body>
</html>