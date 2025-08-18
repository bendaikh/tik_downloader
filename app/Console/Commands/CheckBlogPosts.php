<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckBlogPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and fix blog posts status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $blogs = \App\Models\Blog::all();
        
        if ($blogs->isEmpty()) {
            $this->info('No blog posts found!');
            return 0;
        }
        
        $this->info("Found {$blogs->count()} blog posts:");
        $this->line('');
        
        foreach ($blogs as $blog) {
            $status = $blog->is_published ? 'Published' : 'Draft';
            $date = $blog->published_at ? $blog->published_at->format('Y-m-d H:i') : 'Not set';
            
            $this->line("ID: {$blog->id}");
            $this->line("Title: {$blog->title}");
            $this->line("Slug: {$blog->slug}");
            $this->line("Status: {$status}");
            $this->line("Published Date: {$date}");
            $this->line("---");
        }
        
        // Check published scope
        $published = \App\Models\Blog::published()->get();
        $this->info("Published posts (via scope): {$published->count()}");
        
        foreach ($published as $blog) {
            $this->line("- {$blog->title} (slug: {$blog->slug})");
        }
        
        return 0;
    }
}
