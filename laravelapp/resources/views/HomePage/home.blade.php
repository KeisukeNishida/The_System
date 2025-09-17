<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>（あなたの社名）| デジタルマーケティング支援</title>
  <meta name="description" content="結果にこだわるデジタルマーケティング支援。担当者の顔が見える透明な体制で、戦略から実行まで伴走します。" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#F4F6FB',
              100: '#E9EEF8',
              200: '#D3DCF0',
              300: '#B4C1E3',
              400: '#7D92CC',
              500: '#506AB3', // メイン（お好みで変えてください）
              600: '#3D5499',
              700: '#33467E',
              800: '#2B3A66',
              900: '#1E2846'
            },
            accent: '#10b981'
          },
          fontFamily: {
            jp: ["Noto Sans JP", "system-ui", "-apple-system", "Segoe UI", "Hiragino Kaku Gothic ProN", "Meiryo", "sans-serif"]
          },
          boxShadow: {
            soft: '0 10px 30px rgba(2, 6, 23, 0.06)'
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet" />
  <style>html{scroll-behavior:smooth}</style>
</head>
<body class="font-jp text-slate-900 bg-white">
  <!-- ====== Header ====== -->
  <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="h-16 flex items-center justify-between">
        <a href="#" class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-md bg-brand-600 flex items-center justify-center text-white font-bold">A</div>
          <span class="font-bold tracking-tight text-slate-900">（あなたの社名）</span>
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm">
          <a href="#services" class="hover:text-brand-600">サービス</a>
          <a href="#features" class="hover:text-brand-600">特徴</a>
          <a href="#cases" class="hover:text-brand-600">実績</a>
          <a href="#resources" class="hover:text-brand-600">資料</a>
          <a href="#contact" class="px-4 py-2 rounded-lg bg-brand-600 text-white hover:bg-brand-700 shadow-soft">無料相談</a>
        </nav>
        <button id="menuBtn" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200">
          <span class="sr-only">メニュー</span>
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
      <div id="mobileNav" class="md:hidden hidden pb-4">
        <div class="grid gap-2 text-sm">
          <a href="#services" class="py-2">サービス</a>
          <a href="#features" class="py-2">特徴</a>
          <a href="#cases" class="py-2">実績</a>
          <a href="#resources" class="py-2">資料</a>
          <a href="#contact" class="py-2 font-medium text-brand-700">無料相談</a>
        </div>
      </div>
    </div>
  </header>

  <!-- ====== Hero ====== -->
  <section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-white via-white to-slate-50"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="py-16 lg:py-24 grid lg:grid-cols-2 gap-10 items-center">
        <div>
          <p class="inline-flex items-center gap-2 text-xs font-medium text-brand-700 bg-brand-50 ring-1 ring-brand-100 px-3 py-1 rounded-full">透明性 × 伴走支援</p>
          <h1 class="mt-4 text-3xl sm:text-5xl font-black leading-tight tracking-tight">
            結果にこだわる、<br class="hidden sm:block" />
            デジタルマーケティング支援
          </h1>
          <p class="mt-5 text-slate-600">担当者の顔が見える体制で、戦略設計から実装・運用・改善までワンストップ。貴社の課題に合わせて最適なチームで伴走します。</p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#contact" class="px-5 py-3 rounded-xl bg-brand-600 text-white font-medium hover:bg-brand-700 shadow-soft">無料相談する</a>
            <a href="#resources" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">会社資料をダウンロード</a>
          </div>
        </div>
        <div class="relative">
          <div class="aspect-[4/3] rounded-2xl bg-slate-100 border border-slate-200 shadow-soft flex items-center justify-center">
            <span class="text-slate-400">（ヒーロー画像 or 実績サムネイル）</span>
          </div>
          <div class="absolute -bottom-6 -left-6 hidden sm:block w-40 h-40 rounded-3xl bg-brand-100 blur-2xl opacity-70"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== Client Logos ====== -->
  <section aria-labelledby="trusted-by" class="py-10 border-y border-slate-200 bg-white/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <p id="trusted-by" class="text-center text-sm text-slate-500">ご利用企業（一例）</p>
      <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 items-center">
        <!-- ロゴは自社/許諾済みのものを置いてください -->
        <div class="h-10 bg-slate-100 rounded"></div>
        <div class="h-10 bg-slate-100 rounded"></div>
        <div class="h-10 bg-slate-100 rounded"></div>
        <div class="h-10 bg-slate-100 rounded"></div>
        <div class="h-10 bg-slate-100 rounded"></div>
        <div class="h-10 bg-slate-100 rounded"></div>
      </div>
    </div>
  </section>

  <!-- ====== Features ====== -->
  <section id="features" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl">
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">選ばれる理由</h2>
        <p class="mt-3 text-slate-600">担当者指名制・公開プロフィール・広範な支援領域・成果に直結する改善サイクルで、安心して任せられる体制を構築します。</p>
      </div>
      <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 rounded-2xl border border-slate-200 shadow-soft">
          <div class="text-brand-600 font-semibold">担当者を選べる</div>
          <p class="mt-2 text-sm text-slate-600">スキル・実績・相性から最適な担当を指名可能。稼働状況も可視化。</p>
        </div>
        <div class="p-6 rounded-2xl border border-slate-200 shadow-soft">
          <div class="text-brand-600 font-semibold">透明な情報開示</div>
          <p class="mt-2 text-sm text-slate-600">顔出し/実績/料金をオープンに。意思決定を支える材料を提供。</p>
        </div>
        <div class="p-6 rounded-2xl border border-slate-200 shadow-soft">
          <div class="text-brand-600 font-semibold">網羅的な支援</div>
          <p class="mt-2 text-sm text-slate-600">広告・SEO・SNS・制作・分析まで、必要な領域を横断で伴走。</p>
        </div>
        <div class="p-6 rounded-2xl border border-slate-200 shadow-soft">
          <div class="text-brand-600 font-semibold">継続的な改善</div>
          <p class="mt-2 text-sm text-slate-600">仮説→実行→検証→改善を高速回転。成果から逆算して運用。</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== Services ====== -->
  <section id="services" class="py-16 lg:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl">
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">提供サービス</h2>
        <p class="mt-3 text-slate-600">戦略から実装・運用まで、課題に合わせて必要なピースを提供します。</p>
      </div>
      <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- カード例：必要に応じて増減 -->
        <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft">
          <h3 class="font-bold">Webコンサルティング</h3>
          <p class="mt-2 text-sm text-slate-600">目的・KPI設計から戦略立案、体制づくりまで伴走支援。</p>
          <a href="#contact" class="mt-4 inline-block text-brand-700 hover:underline">相談する →</a>
        </article>
        <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft">
          <h3 class="font-bold">広告運用</h3>
          <p class="mt-2 text-sm text-slate-600">主要プラットフォームの運用とクリエイティブ最適化を一気通貫。</p>
          <a href="#contact" class="mt-4 inline-block text-brand-700 hover:underline">相談する →</a>
        </article>
        <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft">
          <h3 class="font-bold">サイト制作/改善</h3>
          <p class="mt-2 text-sm text-slate-600">HP/LP/ECの制作からCRO改善まで。解析に基づいた改修。</p>
          <a href="#contact" class="mt-4 inline-block text-brand-700 hover:underline">相談する →</a>
        </article>
        <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft">
          <h3 class="font-bold">SEO コンテンツ</h3>
          <p class="mt-2 text-sm text-slate-600">KW戦略、構成、制作、内外部対策まで成果重視で支援。</p>
          <a href="#contact" class="mt-4 inline-block text-brand-700 hover:underline">相談する →</a>
        </article>
        <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft">
          <h3 class="font-bold">SNS/動画</h3>
          <p class="mt-2 text-sm text-slate-600">YouTube/TikTok/Instagram運用、撮影・編集・分析まで。</p>
          <a href="#contact" class="mt-4 inline-block text-brand-700 hover:underline">相談する →</a>
        </article>
        <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft">
          <h3 class="font-bold">データ分析/基盤</h3>
          <p class="mt-2 text-sm text-slate-600">計測設計、ダッシュボード、データ活用まで仕組み化。</p>
          <a href="#contact" class="mt-4 inline-block text-brand-700 hover:underline">相談する →</a>
        </article>
      </div>
    </div>
  </section>

  <!-- ====== Resources / Download ====== -->
  <section id="resources" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">無料資料をダウンロード</h2>
        <p class="mt-3 text-slate-600">支援内容・実績・料金の目安を1冊にまとめました。施策検討の比較表としてもご活用ください。</p>
        <form class="mt-6 grid sm:grid-cols-2 gap-3 max-w-xl">
          <input type="text" placeholder="お名前" class="px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-200" />
          <input type="email" placeholder="メールアドレス" class="px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-200" />
          <input type="text" placeholder="会社名（任意）" class="px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-200 sm:col-span-2" />
          <button type="button" class="px-5 py-3 rounded-xl bg-brand-600 text-white font-medium hover:bg-brand-700 shadow-soft sm:col-span-2">資料を受け取る</button>
        </form>
      </div>
      <div class="aspect-[4/3] rounded-2xl bg-slate-50 border border-slate-200 shadow-soft flex items-center justify-center">
        <span class="text-slate-400">（資料プレビュー画像）</span>
      </div>
    </div>
  </section>

  <!-- ====== Contact CTA ====== -->
  <section id="contact" class="py-16 lg:py-24 bg-gradient-to-b from-slate-50 to-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">まずはお気軽にご相談ください</h2>
      <p class="mt-3 text-slate-600">要件が固まっていなくてもOK。現状ヒアリングから最適な進め方をご提案します。</p>
      <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="#" class="px-5 py-3 rounded-xl bg-brand-600 text-white font-medium hover:bg-brand-700 shadow-soft">無料相談フォーム</a>
        <a href="#" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">メールで相談</a>
      </div>
    </div>
  </section>

  <!-- ====== Footer ====== -->
  <footer class="py-10 bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-md bg-brand-600 text-white flex items-center justify-center font-bold">A</div>
          <span>© <span id="y"></span> （あなたの社名）</span>
        </div>
        <nav class="flex items-center gap-4">
          <a href="#" class="hover:text-slate-700">会社概要</a>
          <a href="#" class="hover:text-slate-700">プライバシーポリシー</a>
          <a href="#" class="hover:text-slate-700">特商法に基づく表記</a>
        </nav>
      </div>
    </div>
  </footer>

  <script>
    document.getElementById('y').textContent = new Date().getFullYear();
    const btn = document.getElementById('menuBtn');
    const nav = document.getElementById('mobileNav');
    if (btn) btn.addEventListener('click', () => nav.classList.toggle('hidden'));
  </script>
</body>
</html>
