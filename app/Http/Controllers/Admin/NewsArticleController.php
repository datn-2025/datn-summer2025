<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;

class NewsArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsArticle::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }        // Filter by featured status
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }$articles = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $categories = NewsArticle::distinct()->pluck('category');

        return view('admin.news.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = NewsArticle::distinct()->pluck('category');
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'summary' => 'required|string',
            'content' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean'
        ]);

        try {
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('articles', 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            NewsArticle::create($validated);
            
            Toastr::success('Tin tức đã được tạo thành công!');
            return redirect()->route('admin.news.index');
        } catch (\Exception $e) {
            if (isset($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            Toastr::error('Có lỗi xảy ra khi tạo tin tức!');
            return back()->withInput();
        }
    }

    public function show(NewsArticle $article)
    {
        return view('admin.news.show', compact('article'));
    }

    public function edit(NewsArticle $article)
    {
        $categories = NewsArticle::distinct()->pluck('category');
        return view('admin.news.edit', compact('article', 'categories'));
    }

    public function update(Request $request, NewsArticle $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'summary' => 'required|string',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean'
        ]);

        try {
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($article->thumbnail) {
                    Storage::disk('public')->delete($article->thumbnail);
                }
                // Store new thumbnail
                $thumbnailPath = $request->file('thumbnail')->store('articles', 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $article->update($validated);
            
            Toastr::success('Tin tức đã được cập nhật thành công!');
            return redirect()->route('admin.news.index');
        } catch (\Exception $e) {
            if (isset($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            Toastr::error('Có lỗi xảy ra khi cập nhật tin tức!');
            return back()->withInput();
        }
    }

    public function destroy(NewsArticle $article)
    {
        try {
            if ($article->thumbnail) {
                Storage::disk('public')->delete($article->thumbnail);
            }
            
            $article->delete();
            
            Toastr::success('Tin tức đã được xóa thành công!');
        } catch (\Exception $e) {
            Toastr::error('Có lỗi xảy ra khi xóa tin tức!');
        }
        
        return redirect()->route('admin.news.index');
    }
}
