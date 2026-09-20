<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteData::get('articles') as $article) {
            Article::firstOrCreate(['slug' => $article['slug']], [
                'title' => $article['title'],
                'excerpt' => $article['excerpt'],
                'body' => SiteData::sectionsToHtml($article['body']),
                'category' => $article['category'],
                'date' => $article['date'],
                'read_time' => $article['readTime'],
                'seo_title' => $article['title'],
                'seo_description' => $article['seoDescription'],
                'is_published' => true,
            ]);
        }
    }
}
