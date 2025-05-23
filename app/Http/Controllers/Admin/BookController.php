<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\Brand;
use App\Models\Author;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Models\BookAttributeValue;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query()
            ->with([
                'category:id,name',
                'author:id,name',
                'brand:id,name',
                'formats:id,book_id,format_name,price,discount,stock',
                'images:id,book_id,image_url'
            ])
            ->withSum(['formats as total_stock' => function ($query) {
                $query->whereIn('format_name', ['Bìa mềm', 'Bìa cứng']);
            }], 'stock');

        // Tìm kiếm theo tiêu đề sách hoặc mã ISBN
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo thương hiệu (brand)
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Lọc theo tác giả
        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        // Lọc theo khoảng trang
        if ($request->filled('min_pages')) {
            $query->where('page_count', '>=', $request->min_pages);
        }
        if ($request->filled('max_pages')) {
            $query->where('page_count', '<=', $request->max_pages);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo khoảng giá của format
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('formats', function ($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        // Sắp xếp theo số trang hoặc giá
        if ($request->filled('sort')) {
            match ($request->sort) {
                'pages_asc' => $query->orderBy('page_count', 'asc'),
                'pages_desc' => $query->orderBy('page_count', 'desc'),
                'price_asc' => $query->withMin('formats as min_price', 'price')
                    ->orderBy('min_price', 'asc'),
                'price_desc' => $query->withMax('formats as max_price', 'price')
                    ->orderBy('max_price', 'desc'),
                default => $query->orderBy('id', 'desc')
            };
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $books = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $categories = Category::all();
        $brands = Brand::all();
        $authors = Author::all();
        return view('admin.books.index', compact('books', 'categories', 'brands', 'authors'));
    }

    public function create()
    {
        $categories = Category::whereNull('deleted_at')->get();
        $brands = Brand::whereNull('deleted_at')->get();
        $authors = Author::whereNull('deleted_at')->get();
        $attributes = Attribute::with('values')->get();
        return view('admin.books.create', compact('categories', 'brands', 'authors', 'attributes'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'page_count' => 'nullable|integer',
            'attribute_values.*.id' => 'exists:attribute_values,id',
            'attribute_values' => 'nullable|array',
            'attribute_values.*.extra_price' => 'nullable|numeric|min:0',
            'has_physical' => 'boolean',
            'formats.physical.price' => 'required_if:has_physical,1|nullable|numeric|min:0',
            'formats.physical.discount' => 'nullable|numeric|min:0|max:100',
            'formats.physical.stock' => 'required_if:has_physical,1|nullable|integer|min:0',
            'formats.ebook.price' => 'required_if:has_ebook,1|nullable|numeric|min:0',
            'formats.ebook.discount' => 'nullable|numeric|min:0|max:100',
            'formats.ebook.file' => 'required_if:has_ebook,1|nullable|mimes:pdf,epub|max:50000',
            'formats.ebook.sample_file' => 'nullable|mimes:pdf,epub|max:10000',
            'formats.ebook.allow_sample_read' => 'boolean',
            'status' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'brand_id' => 'required|exists:brands,id',
            'publication_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'has_ebook' => 'boolean',
        ]);

        $data = $request->only([
            'title',
            'description',
            'author_id',
            'brand_id',
            'category_id',
            'status',
            'isbn',
            'publication_date',
            'page_count'
        ]);

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;

         // Xử lý ảnh chính
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImagePath = $coverImage->store('public/books');
            $coverImagePath = str_replace('public/', '', $coverImagePath);
        }


        $book = Book::create($data);

         // Xử lý ảnh phụ
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('books/thumbnail', 'public');
                // Lưu ảnh vào bảng book_images
                $book->images()->create([
                    'image_url' => $path
                ]);
            }
        }

        // Lưu định dạng sách vật lý
        if ($request->boolean('has_physical')) {
            $book->formats()->create([
                'format_name' => 'Sách Vật Lý',
                'price' => $request->input('formats.physical.price'),
                'discount' => $request->input('formats.physical.discount'),
                'stock' => $request->input('formats.physical.stock'),
            ]);
        }

        // Lưu định dạng ebook
        if ($request->boolean('has_ebook')) {
            $ebookFormat = [
                'format_name' => 'Ebook',
                'price' => $request->input('formats.ebook.price'),
                'discount' => $request->input('formats.ebook.discount'),
                'allow_sample_read' => $request->boolean('formats.ebook.allow_sample_read'),
            ];

            // Upload file ebook chính
            if ($request->hasFile('formats.ebook.file')) {
                $ebookFile = $request->file('formats.ebook.file');
                $ebookFilename = time() . '_' . Str::slug(pathinfo($ebookFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $ebookFile->getClientOriginalExtension();
                $ebookPath = $ebookFile->storeAs('ebooks', $ebookFilename, 'public');
                $ebookFormat['file_url'] = $ebookPath;
            }

            // Upload file xem thử
            if ($request->hasFile('formats.ebook.sample_file')) {
                $sampleFile = $request->file('formats.ebook.sample_file');
                $sampleFilename = time() . '_sample_' . Str::slug(pathinfo($sampleFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $sampleFile->getClientOriginalExtension();
                $samplePath = $sampleFile->storeAs('ebook-samples', $sampleFilename, 'public');
                $ebookFormat['sample_file_url'] = $samplePath;
            }

            $book->formats()->create($ebookFormat);
        }

        // Lưu thuộc tính và giá thêm
        if ($request->filled('attribute_values')) {
            foreach ($request->attribute_values as $valueId => $data) {
                // Sử dụng create thay vì attach để đảm bảo UUID được tạo tự động
                BookAttributeValue::create([
                    'book_id' => $book->id,
                    'attribute_value_id' => $data['id'],
                    'extra_price' => $data['extra_price'] ?? 0
                ]);
            }
        }

        Toastr::success('Thêm sách thành công!');
        return redirect()->route('admin.books.index');
    }

    public function show($id, $slug)
    {
        $book = Book::with([
            'category:id,name',
            'author:id,name',
            'brand:id,name',
            'formats:id,book_id,format_name,price,discount,stock,file_url,sample_file_url,allow_sample_read',
            'images:id,book_id,image_url',
            'attributeValues.attribute',
            'reviews.user:id,name,email'
        ])->findOrFail($id);

        // Calculate average rating
        $averageRating = $book->reviews->avg('rating');
        $reviewCount = $book->reviews->count();

        // Group attributes by attribute name
        $attributes = [];
        foreach ($book->attributeValues as $attributeValue) {
            $attributeName = $attributeValue->attribute->name;
            if (!isset($attributes[$attributeName])) {
                $attributes[$attributeName] = [];
            }
            $attributes[$attributeName][] = [
                'value' => $attributeValue->value,
                'extra_price' => $attributeValue->pivot->extra_price ?? 0
            ];
        }

        return view('admin.books.show', compact('book', 'attributes', 'averageRating', 'reviewCount'));
    }
    
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        
        Toastr::success('Sách đã được chuyển vào thùng rác!', 'Thành công');
        return redirect()->route('admin.books.index');
    }
    
    public function trash(Request $request)
    {
        $query = Book::onlyTrashed()
            ->with([
                'category:id,name',
                'author:id,name',
                'brand:id,name',
                'formats:id,book_id,format_name,price,discount,stock',
                'images:id,book_id,image_url'
            ]);
            
        // Tìm kiếm theo tiêu đề sách hoặc mã ISBN
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }
        
        $trashedBooks = $query->orderBy('deleted_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.books.trash', compact('trashedBooks'));
    }
    
    public function restore($id)
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        $book->restore();
        
        Toastr::success('Sách đã được khôi phục thành công!', 'Thành công');
        return redirect()->route('admin.books.trash');
    }
    
    public function forceDelete($id)
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        
        // Xóa các hình ảnh liên quan
        foreach ($book->images as $image) {
            if ($image->image_url && Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }
            $image->delete();
        }
        
        // Xóa ảnh bìa
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }
        
        // Xóa các định dạng sách
        $book->formats()->delete();
        
        // Xóa các liên kết với thuộc tính
        $book->attributeValues()->detach();
        
        // Xóa vĩnh viễn sách
        $book->forceDelete();
        
        Toastr::success('Sách đã được xóa vĩnh viễn!', 'Thành công');
        return redirect()->route('admin.books.trash');
    }
}
