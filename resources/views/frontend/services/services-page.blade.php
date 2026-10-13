@extends('layouts.frontend')

@section('title', 'Services | Sortiq Solutions')
@section('body_attributes') data-route="/services" class="bg-slate-950 text-white min-h-screen" @endsection

@section('content')
<main class="relative overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,106,0,0.2),_transparent_38%),radial-gradient(circle_at_bottom_right,_rgba(37,99,235,0.18),_transparent_32%)]"></div>
      <section class="relative max-w-7xl mx-auto px-6 py-20 md:py-28">
        <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-orange-300 hover:text-white transition-colors">
          <span>←</span>
          <span>Back to home</span>
        </a>

        <div class="max-w-3xl mt-8">
          <p class="text-orange-300 uppercase tracking-[0.35em] text-xs font-bold">Service Directory</p>
          <h1 class="mt-5 text-4xl md:text-6xl font-[800] leading-tight">All service pages are now organized under one clean folder.</h1>
          <p class="mt-6 text-slate-300 text-lg leading-8">
            This route exists so the repository structure stays tidy for GitHub and Vercel while each service still keeps its own dedicated page.
          </p>
        </div>

        <div class="grid gap-4 mt-14 md:grid-cols-2 xl:grid-cols-3">
          <a href="/website-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Development</p>
            <h2 class="mt-2 text-2xl font-bold">Web Development</h2>
          </a>
          <a href="/website-designing-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Design</p>
            <h2 class="mt-2 text-2xl font-bold">Website Designing</h2>
          </a>
          <a href="/laravel-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Backend</p>
            <h2 class="mt-2 text-2xl font-bold">Laravel Development</h2>
          </a>
          <a href="/wordpress-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">CMS</p>
            <h2 class="mt-2 text-2xl font-bold">WordPress Development</h2>
          </a>
          <a href="/ecommerce-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Commerce</p>
            <h2 class="mt-2 text-2xl font-bold">eCommerce Development</h2>
          </a>
          <a href="/digital-marketing-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Growth</p>
            <h2 class="mt-2 text-2xl font-bold">Digital Marketing</h2>
          </a>
          <a href="/seo-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Visibility</p>
            <h2 class="mt-2 text-2xl font-bold">SEO</h2>
          </a>
          <a href="/smo-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Social</p>
            <h2 class="mt-2 text-2xl font-bold">SMO</h2>
          </a>
          <a href="/graphic-designing-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Creative</p>
            <h2 class="mt-2 text-2xl font-bold">Graphic Design</h2>
          </a>
          <a href="/banner-designing-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Creative</p>
            <h2 class="mt-2 text-2xl font-bold">Banner Design</h2>
          </a>
          <a href="/logo-designing-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Branding</p>
            <h2 class="mt-2 text-2xl font-bold">Logo Design</h2>
          </a>
          <a href="/website-maintenance-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Support</p>
            <h2 class="mt-2 text-2xl font-bold">Website Maintenance</h2>
          </a>
          <a href="/bigcommerce-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Commerce</p>
            <h2 class="mt-2 text-2xl font-bold">BigCommerce Development</h2>
          </a>
          <a href="/mern-stack-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Full Stack</p>
            <h2 class="mt-2 text-2xl font-bold">MERN Stack Development</h2>
          </a>
          <a href="/app-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Mobile</p>
            <h2 class="mt-2 text-2xl font-bold">App Development</h2>
          </a>
          <a href="/software-testing-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">QA</p>
            <h2 class="mt-2 text-2xl font-bold">Software Testing</h2>
          </a>
          <a href="/cyber-security-development" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Security</p>
            <h2 class="mt-2 text-2xl font-bold">Cyber Security</h2>
          </a>
          <a href="/hubspot-crm-services" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">CRM</p>
            <h2 class="mt-2 text-2xl font-bold">HubSpot CRM</h2>
          </a>
          <a href="/zoho-crm-services" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">CRM</p>
            <h2 class="mt-2 text-2xl font-bold">Zoho CRM</h2>
          </a>
          <a href="/php-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Backend</p>
            <h2 class="mt-2 text-2xl font-bold">PHP Development</h2>
          </a>
          <a href="/codeigniter-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Backend</p>
            <h2 class="mt-2 text-2xl font-bold">CodeIgniter Development</h2>
          </a>
          <a href="/shopify-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Commerce</p>
            <h2 class="mt-2 text-2xl font-bold">Shopify Development</h2>
          </a>
          <a href="/react-js-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Frontend</p>
            <h2 class="mt-2 text-2xl font-bold">React Development</h2>
          </a>
          <a href="/node-js-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Backend</p>
            <h2 class="mt-2 text-2xl font-bold">Node Development</h2>
          </a>
          <a href="/vue-js-development-company" class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur px-6 py-5 hover:border-orange-400/60 hover:-translate-y-1 transition-all">
            <p class="text-sm text-orange-300 font-semibold">Frontend</p>
            <h2 class="mt-2 text-2xl font-bold">Vue Development</h2>
          </a>
        </div>
      </section>
    </main>
@endsection

