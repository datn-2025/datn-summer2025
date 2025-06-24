<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RNewsArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Top 10 cuốn sách giúp bạn thay đổi tư duy',
                'thumbnail' => 'news/top-10-sach-thay-doi-tu-duy.jpg',
                'summary' => 'Khám phá những cuốn sách được đánh giá cao về khả năng giúp bạn thay đổi tư duy và phát triển bản thân.',
                'content' => 'Nội dung chi tiết của bài viết về các cuốn sách hay giúp thay đổi tư duy, phân tích vì sao chúng hiệu quả...',
                'category' => 'Books',
                'is_featured' => true,
            ],
            [
                'title' => 'Mẹo bảo quản sách luôn như mới',
                'thumbnail' => 'news/meo-bao-quan-sach.jpg',
                'summary' => 'Những mẹo đơn giản giúp sách của bạn luôn bền đẹp theo thời gian.',
                'content' => 'Chi tiết hướng dẫn cách bảo quản sách, tránh ẩm mốc, cong vênh, giữ màu sắc bìa và giấy luôn đẹp...',
                'category' => 'Tips',
                'is_featured' => false,
            ],
            [
                'title' => 'Xu hướng đọc sách năm 2025',
                'thumbnail' => 'news/xu-huong-doc-sach-2025.jpg',
                'summary' => 'Tổng hợp những xu hướng đọc sách nổi bật trong năm 2025, từ thể loại đến hình thức đọc.',
                'content' => 'Phân tích thị trường sách năm 2025, sự phát triển của sách điện tử, sách audio và xu hướng chọn thể loại của độc giả...',
                'category' => 'Books',
                'is_featured' => true,
            ],
        ];

        foreach ($articles as $article) {
            NewsArticle::create([
                'id' => (string) Str::uuid(),
                'title' => $article['title'],
                'thumbnail' => $article['thumbnail'],
                'summary' => $article['summary'],
                'content' => $article['content'],
                'category' => $article['category'],
                'is_featured' => $article['is_featured'],
            ]);
        }
    }
}
