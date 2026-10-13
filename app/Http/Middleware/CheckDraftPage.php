<?php

namespace App\Http\Middleware;

use App\Support\Seo\PageMeta;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDraftPage
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip backend admin, authentication, API, asset, health check, and robots/sitemap
        if (
            $request->is('sortiqadmin*') ||
            $request->is('admin*') ||
            $request->is('api*') ||
            $request->is('robots.txt') ||
            $request->is('sitemap*') ||
            $request->is('sitemap_index.xml') ||
            $request->is('up')
        ) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        $path = '/' . ltrim($request->path(), '/');

        if (PageMeta::isDraftRoute($routeName, $path)) {
            abort(404);
        }

        return $next($request);
    }
}
