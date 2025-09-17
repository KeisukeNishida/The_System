<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kelotel — Home 02</title>
  <meta name="description" content="Kelotel Inc. — デジタル×デザインで価値ある体験をつくる。" />
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50:'#f5f7ff',100:'#eaefff',200:'#cfd9ff',300:'#a9bbff',400:'#7f97ff',
              500:'#5a76f0',600:'#465fd0',700:'#3a4ea9',800:'#2f3f86',900:'#27356e'
            }
          },
          fontFamily: {
            jp:["Noto Sans JP","system-ui","-apple-system","Segoe UI","Hiragino Kaku Gothic ProN","Meiryo","sans-serif"],
            display:["BIZ UDPGothic","Noto Sans JP","system-ui","sans-serif"]
          },
          boxShadow:{soft:'0 10px 30px rgba(2,6,23,.06)'}
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet" />
  <style>
    html{scroll-behavior:smooth}
    .will-change-transform{will-change:transform}
    .mask-b{mask-image:linear-gradient(to bottom,rgba(0,0,0,1),rgba(0,0,0,.1))}
    @media (prefers-reduced-motion: reduce){.motion-ok{animation:none!important;transition:none!important}}
  </style>
</head>
<body class="font-jp text-slate-900 bg-white">
  <!-- ================= Header ================= -->
  <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="h-16 flex items-center justify-between">
        <a href="#hero" class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-md bg-brand-600 text-white grid place-content-center font-bold">K</div>
          <span class="font-bold tracking-tight">Kelotel</span>
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm">
          <a href="#who" class="hover:text-brand-700">WHO</a>
          <a href="#business" class="hover:text-brand-700">BUSINESS</a>
          <a href="#projects" class="hover:text-brand-700">PROJECT</a>
          <a href="#news" class="hover:text-brand-700">NEWS</a>
          <a href="#contact" class="px-4 py-2 rounded-lg bg-brand-600 text-white hover:bg-brand-700 shadow-soft">CONTACT</a>
        </nav>
        <button id="menuBtn" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200" aria-expanded="false" aria-controls="mobileNav">
          <span class="sr-only">メニュー</span>
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
      <div id="mobileNav" class="md:hidden hidden pb-4">
        <div class="grid gap-2 text-sm">
          <a href="#who" class="py-2">WHO</a>
          <a href="#business" class="py-2">BUSINESS</a>
          <a href="#projects" class="py-2">PROJECT</a>
          <a href="#news" class="py-2">NEWS</a>
          <a href="#contact" class="py-2 font-medium text-brand-700">CONTACT</a>
        </div>
      </div>
    </div>
  </header>

  <!-- ================= Hero ================= -->
  <section id="hero" class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white via-white to-slate-50"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="py-14 lg:py-24 grid lg:grid-cols-12 gap-8 items-end">
        <div class="lg:col-span-7">
          <!-- ACT / TOGETHER, BE / INDEPENDENT のタイポグラフィ構成を意識した見出し（自作） -->
          <div class="flex gap-3 items-end text-[9vw] leading-none font-black font-display select-none js-hero-letters" aria-hidden="true">
            <span class="inline-block">A</span>
            <span class="inline-block">C</span>
            <span class="inline-block">T</span>
          </div>
          <h1 class="mt-4 text-4xl sm:text-6xl font-black leading-[1.05] tracking-tight font-display">
            <span class="block js-split">TOGETHER,</span>
            <span class="block js-split">BE INDEPENDENT</span>
          </h1>
          <p class="mt-6 text-slate-600 max-w-prose">テクノロジーとデザインで、ビジネスの本質課題を解決する。戦略から実装、運用、グロースまで一気通貫で伴走します。</p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#contact" class="px-5 py-3 rounded-xl bg-brand-600 text-white font-medium hover:bg-brand-700 shadow-soft">相談する</a>
            <a href="#projects" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">事例を見る</a>
          </div>
        </div>
        <div class="lg:col-span-5 relative">
          <div class="aspect-[4/3] rounded-2xl bg-slate-100 border border-slate-200 shadow-soft flex items-center justify-center will-change-transform" data-parallax data-speed="0.15">
            <span class="text-slate-400">Hero Image</span>
          </div>
        </div>
      </div>
    </div>
    <div class="absolute -bottom-10 -left-10 w-48 h-48 rounded-3xl bg-brand-100 blur-2xl opacity-70"></div>
  </section>

  <!-- ================= WHO ================= -->
  <section id="who" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl">
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight js-reveal">WHO WE ARE</h2>
        <p class="mt-3 text-slate-600 js-reveal">デジタルの専門家として、正しいものづくりを通じて本質課題の解決に向き合います。ビジネス × デザイン × テクノロジーの交差点で価値を最大化します。</p>
      </div>
    </div>
  </section>

  <!-- ================= BUSINESS（カード→モーダル説明） ================= -->
  <section id="business" class="py-16 lg:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="lg:grid lg:grid-cols-12 lg:gap-10">
        <div class="lg:col-span-4 lg:sticky lg:top-24 h-max"><h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">BUSINESS</h2><p class="mt-3 text-slate-600">ニーズに応じて横断チームで伴走します。</p></div>
        <div class="lg:col-span-8 grid gap-6 mt-8 lg:mt-0">
          <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft js-reveal" data-modal-target="#modal-sd">
            <h3 class="font-bold">System / Design Partner</h3>
            <p class="mt-2 text-sm text-slate-600">新規事業〜既存改善まで、企画・設計・実装を一気通貫。</p>
          </article>
          <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft js-reveal" data-modal-target="#modal-gs">
            <h3 class="font-bold">Growth Strategy Partner</h3>
            <p class="mt-2 text-sm text-slate-600">グロース設計 / 施策実行 / 計測と改善のPDCA。</p>
          </article>
          <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft js-reveal" data-modal-target="#modal-bi">
            <h3 class="font-bold">Business Intelligence Partner</h3>
            <p class="mt-2 text-sm text-slate-600">業務可視化・データ活用・ツール選定と運用設計。</p>
          </article>
          <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft js-reveal" data-modal-target="#modal-wd">
            <h3 class="font-bold">Work Design Partner</h3>
            <p class="mt-2 text-sm text-slate-600">内製化支援 / 技術顧問 / 組織のデジタル力を底上げ。</p>
          </article>
        </div>
      </div>
    </div>
  </section>

  <!-- モーダル群（クリックで説明を出す。閉じるときは背景クリック or ×） -->
  <div id="modal-root"></div>
  <template id="modal-tpl">
    <div class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur will-change-transform" data-modal-close></div>
      <div class="relative z-10 max-w-2xl mx-auto my-16 bg-white rounded-2xl shadow-soft border border-slate-200">
        <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
          <h3 class="text-lg font-bold" data-modal-title>Title</h3>
          <button class="w-9 h-9 inline-grid place-content-center rounded-lg border border-slate-200" data-modal-close aria-label="閉じる">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="p-6 text-sm text-slate-700" data-modal-body>...</div>
      </div>
    </div>
  </template>

  <!-- ================= PROJECT ================= -->
  <section id="projects" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-end justify-between gap-6">
        <div>
          <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">PROJECT</h2>
          <p class="mt-3 text-slate-600">一例のモックカード（実案件は自社素材を配置してください）。</p>
        </div>
        <a href="#contact" class="hidden sm:inline-block px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-50">すべてを見る</a>
      </div>
      <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <article class="rounded-2xl border border-slate-200 overflow-hidden shadow-soft group js-reveal">
          <div class="aspect-[4/3] bg-slate-100"></div>
          <div class="p-5">
            <h3 class="font-bold group-hover:underline">ブランドサイト刷新</h3>
            <p class="mt-1 text-sm text-slate-600">UX再設計と高速化、CMS移行まで伴走。</p>
          </div>
        </article>
        <article class="rounded-2xl border border-slate-200 overflow-hidden shadow-soft group js-reveal">
          <div class="aspect-[4/3] bg-slate-100"></div>
          <div class="p-5">
            <h3 class="font-bold group-hover:underline">SaaSダッシュボード</h3>
            <p class="mt-1 text-sm text-slate-600">データモデルとUI設計、オンボーディング最適化。</p>
          </div>
        </article>
        <article class="rounded-2xl border border-slate-200 overflow-hidden shadow-soft group js-reveal">
          <div class="aspect-[4/3] bg-slate-100"></div>
          <div class="p-5">
            <h3 class="font-bold group-hover:underline">EC改善とCRO</h3>
            <p class="mt-1 text-sm text-slate-600">計測〜改善の仕組み化で継続成長を支援。</p>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- ================= NEWS（タブ切り替え） ================= -->
  <section id="news" class="py-16 lg:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl">
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">NEWS</h2>
        <div class="mt-6 flex items-center gap-2 text-xs">
          <button class="px-3 py-1 rounded-full border border-slate-300 bg-white data-[active=true]:bg-brand-600 data-[active=true]:text-white" data-news-tab="all">ALL</button>
          <button class="px-3 py-1 rounded-full border border-slate-300 bg-white" data-news-tab="news">NEWS</button>
          <button class="px-3 py-1 rounded-full border border-slate-300 bg-white" data-news-tab="info">INFORMATION</button>
        </div>
        <ul id="newsList" class="mt-6 space-y-4">
          <li class="flex items-start gap-3 js-reveal" data-cat="news"><span class="mt-1 inline-flex h-2 w-2 rounded-full bg-brand-600"></span><span class="text-sm text-slate-700">2025-09-01 NEWS ダミー見出し（プレスリリース）。</span></li>
          <li class="flex items-start gap-3 js-reveal" data-cat="info"><span class="mt-1 inline-flex h-2 w-2 rounded-full bg-brand-600"></span><span class="text-sm text-slate-700">2025-07-28 INFORMATION ダミー見出し（メディア掲載）。</span></li>
          <li class="flex items-start gap-3 js-reveal" data-cat="info"><span class="mt-1 inline-flex h-2 w-2 rounded-full bg-brand-600"></span><span class="text-sm text-slate-700">2025-07-24 INFORMATION ダミー（夏期休業）。</span></li>
        </ul>
      </div>
    </div>
  </section>

  <!-- ================= Contact ================= -->
  <section id="contact" class="py-16 lg:py-24 bg-gradient-to-b from-slate-50 to-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">まずはお気軽にご相談ください</h2>
      <p class="mt-3 text-slate-600">要件が固まっていなくてもOK。現状ヒアリングから最適な進め方をご提案します。</p>
      <form class="mt-8 max-w-xl mx-auto grid sm:grid-cols-2 gap-3">
        <input type="text" placeholder="お名前" class="px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-200" />
        <input type="email" placeholder="メールアドレス" class="px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-200" />
        <textarea placeholder="ご相談内容" rows="4" class="sm:col-span-2 px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-200"></textarea>
        <button type="button" class="sm:col-span-2 px-5 py-3 rounded-xl bg-brand-600 text-white font-medium hover:bg-brand-700 shadow-soft">送信</button>
      </form>
    </div>
  </section>

  <!-- ================= Footer ================= -->
  <footer class="py-10 bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-md bg-brand-600 text-white grid place-content-center font-bold">K</div>
          <span>© <span id="y"></span> Kelotel</span>
        </div>
        <nav class="flex items-center gap-4">
          <a href="#" class="hover:text-slate-700">会社概要</a>
          <a href="#" class="hover:text-slate-700">プライバシー</a>
          <a href="#" class="hover:text-slate-700">特商法</a>
        </nav>
      </div>
    </div>
  </footer>

  <!-- ===== JS libs: GSAP + ScrollTrigger + Lenis ===== -->
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.36/bundled/lenis.min.js"></script>

  <script>
  (function(){
    const prefersReduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Mobile nav
    const btn = document.getElementById('menuBtn');
    const nav = document.getElementById('mobileNav');
    if(btn){ btn.addEventListener('click', ()=>{ const e = nav.classList.toggle('hidden'); btn.setAttribute('aria-expanded', String(!e)); }); }

    // Year
    document.getElementById('y').textContent = new Date().getFullYear();

    // Smooth scroll (Lenis)
    let lenis = null;
    if(!prefersReduce){
      lenis = new Lenis({ lerp: 0.12, smoothWheel: true });
      function raf(time){ lenis.raf(time); requestAnimationFrame(raf); }
      requestAnimationFrame(raf);
      // integrate with GSAP ScrollTrigger
      lenis.on('scroll', ScrollTrigger.update);
      gsap.ticker.add((time)=>{ lenis.raf(time * 1000); });
      gsap.ticker.lagSmoothing(0);
    }

    // Hero "ACT" letters slight float
    const heroLetters = document.querySelectorAll('.js-hero-letters span');
    heroLetters.forEach((el,i)=>{
      el.style.opacity = 0; el.style.transform = 'translateY(20px)';
      if(!prefersReduce){
        gsap.to(el, {opacity:1, y:0, duration:0.9, delay:0.15*i, ease:'power3.out'});
        gsap.to(el, {y:-8, repeat:-1, yoyo:true, duration:2.2 + i*0.2, ease:'sine.inOut'});
      }else{ el.style.opacity = 1; el.style.transform = 'none'; }
    });

    // Split headline
    function splitLines(el){ const t = el.textContent; el.textContent=''; const frag = document.createDocumentFragment(); t.split('').forEach(ch=>{ const s=document.createElement('span'); s.textContent=ch; s.style.display='inline-block'; s.style.transform='translateY(1em)'; s.style.opacity=0; frag.appendChild(s); }); el.appendChild(frag); }
    document.querySelectorAll('.js-split').forEach(splitLines);
    if(!prefersReduce){
      gsap.utils.toArray('.js-split').forEach((el,i)=>{
        gsap.to(el.querySelectorAll('span'),{y:0,opacity:1,duration:0.8,ease:'power3.out',stagger:0.03,delay:0.25 + i*0.15});
      });
    } else { document.querySelectorAll('.js-split span').forEach(s=>{ s.style.opacity=1; s.style.transform='none'; }); }

    // Reveal on scroll
    const reveals = document.querySelectorAll('.js-reveal');
    reveals.forEach(el=>{ el.style.opacity=0; el.style.transform='translateY(16px)'; });
    if(!prefersReduce){
      reveals.forEach((el)=>{
        gsap.fromTo(el,{opacity:0,y:16},{opacity:1,y:0,duration:0.7,ease:'power2.out',scrollTrigger:{trigger:el,start:'top 85%'}});
      });
    } else {
      const io = new IntersectionObserver((ents)=>{ ents.forEach(e=>{ if(e.isIntersecting){ e.target.style.opacity=1; e.target.style.transform='none'; io.unobserve(e.target);} }); },{threshold:0.1});
      reveals.forEach(el=> io.observe(el));
    }

    // Parallax blocks
    const parallaxEls = document.querySelectorAll('[data-parallax]');
    if(!prefersReduce){
      parallaxEls.forEach((el)=>{
        const speed = parseFloat(el.getAttribute('data-speed')||'0.15');
        gsap.to(el,{ yPercent: speed*100, ease:'none', scrollTrigger:{ trigger: el, scrub: true } });
      });
    }

    // Business card -> modal
    const modalRoot = document.getElementById('modal-root');
    const modalTpl = document.getElementById('modal-tpl');
    const modalData = {
      '#modal-sd': {title:'System / Design Partner', body:'事業開発・既存改善において、企画・要件定義・設計・実装を横断して支援します。UXリサーチ/IA/UI、API/バックエンド、運用まで伴走。'},
      '#modal-gs': {title:'Growth Strategy Partner', body:'ビジネスデザイン/マーケ/計測/実験設計でグロースを推進。KPI設計〜改善の仕組み化まで支援します。'},
      '#modal-bi': {title:'Business Intelligence Partner', body:'業務可視化・データ収集と基盤設計、ツール選定/運用設計による意思決定の高度化を支援します。'},
      '#modal-wd': {title:'Work Design Partner', body:'技術顧問・内製化支援・育成により、組織のデジタル推進力を底上げします。'}
    };
    function openModal(key){
      const inst = modalTpl.content.cloneNode(true);
      const host = inst.querySelector('div[role="dialog"]');
      inst.querySelector('[data-modal-title]').textContent = modalData[key].title;
      inst.querySelector('[data-modal-body]').textContent = modalData[key].body;
      host.classList.remove('hidden');
      host.querySelectorAll('[data-modal-close]').forEach(b=> b.addEventListener('click', ()=> host.remove()));
      modalRoot.appendChild(inst);
    }
    document.querySelectorAll('[data-modal-target]').forEach(card=>{
      card.addEventListener('click', ()=>{
        const key = card.getAttribute('data-modal-target');
        if(modalData[key]) openModal(key);
      });
      card.style.cursor = 'pointer';
    });

    // News tabs
    const tabs = document.querySelectorAll('[data-news-tab]');
    const list = document.getElementById('newsList');
    function applyTab(kind){
      tabs.forEach(t=> t.dataset.active = (t.getAttribute('data-news-tab')===kind || kind==='all'));
      list.querySelectorAll('li').forEach(li=>{
        const show = (kind==='all') || (li.getAttribute('data-cat')===kind);
        li.classList.toggle('hidden', !show);
      });
    }
    tabs.forEach(t=> t.addEventListener('click', ()=> applyTab(t.getAttribute('data-news-tab'))));
    applyTab('all');
  })();
  </script>
</body>
</html>