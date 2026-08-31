<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Car;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $cars = Car::where('is_active', true)
            ->select(['id', 'slug', 'thumbnail', 'updated_at'])
            ->latest('updated_at')
            ->get();

        $blogs = BlogPost::published()
            ->select(['id', 'slug', 'thumbnail', 'updated_at'])
            ->latest('updated_at')
            ->get();

        $xml = view('new-store.sitemap', compact('cars', 'blogs'))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
