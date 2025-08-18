<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixBlogSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:fix-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing blog slugs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $blogs = \App\Models\Blog::whereNull('slug')->orWhere('slug', '')->get();
        
        if ($blogs->isEmpty()) {
            $this->info('All blog posts already have slugs!');
            return 0;
        }
        
        $this->info("Found {$blogs->count()} blog posts without slugs. Fixing...");
        
        foreach ($blogs as $blog) {
            if (!empty($blog->title)) {
                $originalSlug = $blog->slug;
                $blog->slug = \Illuminate\Support\Str::slug($blog->title);
                $blog->save();
                
                $this->line("Fixed: '{$blog->title}' -> '{$blog->slug}'");
            } else {
                $this->warn("Skipped: Blog ID {$blog->id} has no title");
            }
        }
        
        $this->info('All slugs have been fixed!');
        return 0;
    }
}
