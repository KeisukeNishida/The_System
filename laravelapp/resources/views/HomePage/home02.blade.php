<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kelotel — Home 02 (Motion Enhanced)</title>
  <meta name="description" content="Kelotel Inc. — 独創的なモーションとインタラクションを備えたデモページ。" />
  
  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {50:'#F5F7FF',100:'#E9EFFF',200:'#CFDAFF',300:'#AABEFF',400:'#7F98FF',500:'#5A76F0',600:'#465FD0',700:'#354AA9',800:'#2B3C86',900:'#24336F'},
            ink: {900:'#0B1020'}
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
    body{cursor:none}
    .will-change-transform{will-change:transform}
    .glow{box-shadow:0 8px 40px rgba(90,118,240,.25)}
    .grain:before{content:"";position:fixed;inset:0;pointer-events:none;background-image:url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"160\" height=\"160\" viewBox=\"0 0 160 160\"><filter id=\"n\"><feTurbulence type=\"fractalNoise\" baseFrequency=\"0.9\" numOctaves=\"2\" stitchTiles=\"stitch\"/></filter><rect width=\"100%\" height=\"100%\" filter=\"url(%23n)\" opacity=\".03\"/></svg>');mix-blend-mode:multiply;}
    /* Reduced motion */
    @media (prefers-reduced-motion: reduce){body{cursor:auto} .no-motion{animation:none!important;transition:none!important}}
  </style>
</head>
<body class="font-jp text-ink-900 bg-white grain">
  <!-- custom cursor -->
  <div id="cursor" class="fixed z-[100] top-0 left-0 -translate-x-1/2 -translate-y-1/2 pointer-events-none w-6 h-6 rounded-full border border-brand-500/70 hidden md:block"></div>

  <!-- ===== Header ===== -->
  <header id="siteHeader" class="sticky top-0 z-50 bg-white/70 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="h-16 flex items-center justify-between">
        <a href="#hero" class="flex items-center gap-3 group">
          <div class="w-9 h-9 rounded-md bg-brand-600 text-white grid place-content-center font-bold group-hover:scale-105 transition">K</div>
          <span class="font-bold tracking-tight">Kelotel</span>
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm">
          <a href="#who" class="hover:text-brand-700">WHO</a>
          <a href="#features" class="hover:text-brand-700">FEATURES</a>
          <a href="#showcase" class="hover:text-brand-700">SHOWCASE</a>
          <a href="#contact" class="px-4 py-2 rounded-lg bg-brand-600 text-white hover:bg-brand-700 shadow-soft" data-magnetic>CONTACT</a>
        </nav>
        <button id="menuBtn" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200" aria-expanded="false" aria-controls="mobileNav">
          <span class="sr-only">メニュー</span>
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
      <div id="mobileNav" class="md:hidden hidden pb-4">
        <div class="grid gap-2 text-sm">
          <a href="#who" class="py-2">WHO</a>
          <a href="#features" class="py-2">FEATURES</a>
          <a href="#showcase" class="py-2">SHOWCASE</a>
          <a href="#contact" class="py-2 font-medium text-brand-700">CONTACT</a>
        </div>
      </div>
    </div>
  </header>

  <!-- ===== Hero ===== -->
  <section id="hero" class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white via-white to-slate-50"></div>

    <!-- morphing blob background -->
    <svg class="absolute -z-10 -top-24 -right-24 opacity-70" width="560" height="560" viewBox="0 0 560 560" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0%" stop-color="#5A76F0"/>
          <stop offset="100%" stop-color="#7F98FF"/>
        </linearGradient>
      </defs>
      <path id="blob" fill="url(#g)" d="M438.5 320.5Q408 401 320.5 435.5T164 419.5Q89 372 78 280T150 126q62-48 148.5-40t148 79Q469 240 438.5 320.5Z"/>
    </svg>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="py-16 lg:py-28 grid lg:grid-cols-12 gap-10 items-end">
        <div class="lg:col-span-7">
          <div class="flex gap-3 items-end text-[9vw] leading-none font-black font-display select-none js-hero-letters" aria-hidden="true">
            <span class="inline-block">A</span>
            <span class="inline-block">C</span>
            <span class="inline-block">T</span>
          </div>
          <h1 class="mt-4 text-4xl sm:text-6xl font-black leading-[1.05] tracking-tight font-display">
            <span class="block js-split">Create Motion,</span>
            <span class="block js-split">Feel Interaction.</span>
          </h1>
          <p class="mt-6 text-slate-600 max-w-prose">jQuery と GSAP / anime.js を組み合わせた、軽快で独創的な体験デモ。スクロール・マウス・クリックに心地よく反応します。</p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#showcase" class="px-5 py-3 rounded-xl bg-brand-600 text-white font-medium hover:bg-brand-700 glow" data-magnetic>ショーケースへ</a>
            <a href="#features" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50" data-magnetic>特徴を見る</a>
          </div>
        </div>
        <div class="lg:col-span-5 relative">
          <div class="aspect-[4/3] rounded-2xl bg-slate-100 border border-slate-200 shadow-soft flex items-center justify-center will-change-transform" id="heroCard">
            <span class="text-slate-400">Interactive Card</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== WHO ===== -->
  <section id="who" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl">
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight js-reveal">WHO WE ARE</h2>
        <p class="mt-3 text-slate-600 js-reveal">デジタルの専門家として、正しいものづくりを通じて価値ある体験を提供します。ビジネス × デザイン × テクノロジーの交差点で成果を最大化。</p>
      </div>
    </div>
  </section>

  <!-- ===== FEATURES (magnetic / tilt / counters) ===== -->
  <section id="features" class="py-16 lg:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-3 gap-6">
        <!-- magnetic buttons showcase -->
        <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft js-reveal">
          <h3 class="font-bold">Magnetic Buttons</h3>
          <p class="mt-2 text-sm text-slate-600">カーソルに吸い付くCTA。jQueryでイベント取得、GSAPで補間。</p>
          <div class="mt-4 flex gap-3">
            <a class="px-4 py-2 rounded-lg bg-brand-600 text-white" data-magnetic>CTA</a>
            <a class="px-4 py-2 rounded-lg border" data-magnetic>More</a>
          </div>
        </article>
        <!-- tilt card -->
        <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft js-reveal">
          <h3 class="font-bold">3D Tilt Card</h3>
          <p class="mt-2 text-sm text-slate-600">カード上のマウス位置で3Dチルト。jQueryで座標→CSS変換。</p>
          <div class="mt-4 perspective-1000">
            <div id="tiltCard" class="h-40 rounded-xl bg-gradient-to-br from-brand-200 to-brand-400 grid place-content-center text-white font-bold select-none">TILT</div>
          </div>
        </article>
        <!-- counter -->
        <article class="p-6 rounded-2xl bg-white border border-slate-200 shadow-soft js-reveal">
          <h3 class="font-bold">Count-up Numbers</h3>
          <p class="mt-2 text-sm text-slate-600">インビューでカウントアップ。jQuery + GSAP で遅延開始。</p>
          <div class="mt-6 grid grid-cols-3 gap-4 text-center">
            <div><div class="text-3xl font-extrabold js-counter" data-to="128">0</div><div class="text-xs text-slate-500 mt-1">PROJECTS</div></div>
            <div><div class="text-3xl font-extrabold js-counter" data-to="42">0</div><div class="text-xs text-slate-500 mt-1">CLIENTS</div></div>
            <div><div class="text-3xl font-extrabold js-counter" data-to="99">0</div><div class="text-xs text-slate-500 mt-1">SCORE</div></div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- ===== SHOWCASE (horizontal pinned) ===== -->
  <section id="showcase" class="py-16 lg:py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">SHOWCASE</h2>
    </div>

    <div id="hWrap" class="mt-10">
      <div id="hPin" class="relative">
        <div id="hTrack" class="flex gap-6 px-4 sm:px-6 lg:px-8 will-change-transform">
          <!-- 6 cards -->
          <template id="cardTpl">
            <article class="min-w-[80vw] sm:min-w-[46vw] lg:min-w-[32vw] rounded-2xl border border-slate-200 overflow-hidden shadow-soft group">
              <div class="aspect-[4/3] bg-slate-100"></div>
              <div class="p-5">
                <h3 class="font-bold group-hover:underline">Motion Project</h3>
                <p class="mt-1 text-sm text-slate-600">独創的な演出のサンプル。</p>
              </div>
            </article>
          </template>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== CONTACT ===== -->
  <section id="contact" class="py-16 lg:py-24 bg-gradient-to-b from-slate-50 to-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">まずはお気軽にご相談ください</h2>
      <p class="mt-3 text-slate-600">要件が固まっていなくてもOK。現状ヒアリングから最適な進め方をご提案します。</p>
      <form class="mt-8 max-w-xl mx-auto grid sm:grid-cols-2 gap-3">
        <input type="text" placeholder="お名前" class="px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-200" />
        <input type="email" placeholder="メールアドレス" class="px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-200" />
        <textarea placeholder="ご相談内容" rows="4" class="sm:col-span-2 px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-200"></textarea>
        <button type="button" class="sm:col-span-2 px-5 py-3 rounded-xl bg-brand-600 text-white font-medium hover:bg-brand-700 glow" data-magnetic>送信</button>
      </form>
    </div>
  </section>

  <!-- ===== Footer ===== -->
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

  <!-- ===== Libraries ===== -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.2/lib/anime.min.js"></script>

  <script>
  (function(){
    const prefersReduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Year
    $('#y').text(new Date().getFullYear());

    // Mobile nav
    $('#menuBtn').on('click', function(){
      const $nav = $('#mobileNav');
      $nav.toggleClass('hidden');
      $(this).attr('aria-expanded', $nav.hasClass('hidden') ? 'false' : 'true');
    });

    // ===== Custom Cursor (jQuery + GSAP) =====
    const $cursor = $('#cursor');
    if(!prefersReduce && window.matchMedia('(pointer:fine)').matches){
      $cursor.show();
      $(window).on('mousemove', function(e){
        gsap.to($cursor[0], {x:e.clientX, y:e.clientY, duration:0.18, ease:'power3.out'});
      });
      $('[data-magnetic], a, button').on('mouseenter', ()=> $cursor.addClass('bg-brand-500/10'))
                                     .on('mouseleave', ()=> $cursor.removeClass('bg-brand-500/10'))
    }

    // ===== Morphing blob (anime.js) =====
    if(!prefersReduce){
      const shapes = [
        'M438.5 320.5Q408 401 320.5 435.5T164 419.5Q89 372 78 280T150 126q62-48 148.5-40t148 79Q469 240 438.5 320.5Z',
        'M450 307Q423 394 338 429T173 420Q90 377 74 284T148 128q71-58 160-44t126 105Q477 220 450 307Z',
        'M476 308Q430 408 325 452.5T168 416Q79 360 73 262T157 122q74-64 165-50t129 111Q522 208 476 308Z'
      ];
      anime({targets:'#blob', d: [{value:shapes[1]},{value:shapes[2]},{value:shapes[0]}], duration:12000, easing:'easeInOutSine', direction:'alternate', loop:true});
    }

    // ===== Hero letters float & headline split =====
    const $letters = $('.js-hero-letters span');
    $letters.css({opacity:0, transform:'translateY(20px)'});
    if(!prefersReduce){
      $letters.each(function(i){ gsap.to(this,{opacity:1,y:0,duration:0.9,delay:0.12*i,ease:'power3.out'}); });
      $letters.each(function(i){ gsap.to(this,{y:'-=8',repeat:-1,yoyo:true,duration:2.2+i*0.2,ease:'sine.inOut'}); });
    } else { $letters.css({opacity:1, transform:'none'}); }

    function splitLines($el){
      const text = $el.text(); $el.text('');
      [...text].forEach(ch=>{ const $s = $('<span/>').text(ch).css({display:'inline-block',transform:'translateY(1em)',opacity:0}); $el.append($s); });
    }
    $('.js-split').each(function(){ splitLines($(this)); });
    if(!prefersReduce){
      $('.js-split').each(function(i){
        gsap.to($(this).find('span').toArray(),{y:0,opacity:1,duration:0.8,ease:'power3.out',stagger:0.03,delay:0.25+i*0.15});
      });
    } else {
      $('.js-split span').css({opacity:1, transform:'none'});
    }

    // ===== Hero card mouse parallax =====
    const $heroCard = $('#heroCard');
    $heroCard.on('mousemove', function(e){
      const r = this.getBoundingClientRect();
      const x = (e.clientX - r.left)/r.width - 0.5;
      const y = (e.clientY - r.top)/r.height - 0.5;
      gsap.to(this, {rotateY:x*8, rotateX:-y*8, translateZ:0, transformPerspective:600, duration:0.3});
    }).on('mouseleave', function(){ gsap.to(this,{rotateY:0, rotateX:0, duration:0.4, ease:'power2.out'}); });

    // ===== Reveal on scroll =====
    gsap.registerPlugin(ScrollTrigger);
    $('.js-reveal').each(function(){
      const el = this; el.style.opacity=0; el.style.transform='translateY(16px)';
      if(!prefersReduce){
        gsap.fromTo(el,{opacity:0,y:16},{opacity:1,y:0,duration:0.7,ease:'power2.out',scrollTrigger:{trigger:el,start:'top 85%'}});
      }else{ el.style.opacity=1; el.style.transform='none'; }
    });

    // ===== Magnetic buttons (jQuery + GSAP) =====
    const strength = 24; // px
    $('[data-magnetic]').on('mousemove', function(e){
      const r = this.getBoundingClientRect();
      const x = e.clientX - (r.left + r.width/2);
      const y = e.clientY - (r.top + r.height/2);
      gsap.to(this, {x:x/6, y:y/6, duration:0.25});
    }).on('mouseleave', function(){ gsap.to(this, {x:0, y:0, duration:0.35, ease:'power3.out'}); });

    // ===== Tilt card (features) =====
    $('#tiltCard').on('mousemove', function(e){
      const r = this.getBoundingClientRect();
      const x = (e.clientX - r.left)/r.width - 0.5;
      const y = (e.clientY - r.top)/r.height - 0.5;
      this.style.transform = `rotateY(${x*12}deg) rotateX(${-y*12}deg)`;
    }).on('mouseleave', function(){ this.style.transform='none'; });

    // ===== Count-up numbers =====
    $('.js-counter').each(function(){
      const el = this; const to = Number(el.getAttribute('data-to')||0);
      if(prefersReduce){ el.textContent = to; return; }
      ScrollTrigger.create({
        trigger: el, start:'top 85%', once:true,
        onEnter: ()=>{
          const obj = {v:0};
          gsap.to(obj, {v:to, duration:1.2, ease:'power1.out', onUpdate:()=>{ el.textContent = Math.round(obj.v); }});
        }
      });
    });

    // ===== Horizontal pinned showcase =====
    // 動的にカードを6枚生成
    const $track = $('#hTrack');
    for(let i=0;i<6;i++){ $track.append($('#cardTpl').prop('content').cloneNode(true)); }
    const totalWidth = ()=> $track[0].scrollWidth - window.innerWidth;

    if(!prefersReduce){
      const st = ScrollTrigger.create({
        trigger:'#hWrap',
        start:'top top', end:()=> `+=${totalWidth()}`, pin:'#hPin', scrub:true, anticipatePin:1
      });
      gsap.to('#hTrack', { x: ()=> -totalWidth(), ease:'none', scrollTrigger: st });
      window.addEventListener('resize', ()=> st.refresh());
    }
  })();
  </script>
</body>
</html>
