<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['author_id'] = Auth::id();
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        // Ensure keys exist to avoid undefined index notices
        $isPublished = $validated['is_published'] ?? false;
        $publishedAt = $validated['published_at'] ?? null;

        if ($isPublished && !$publishedAt) {
            $validated['published_at'] = now();
        }
        $validated['is_published'] = $isPublished;

        $blog = Blog::create($validated);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'description' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $isPublished = $validated['is_published'] ?? false;
        $publishedAt = $validated['published_at'] ?? null;

        if ($isPublished && !$blog->published_at && !$publishedAt) {
            $validated['published_at'] = now();
        }
        $validated['is_published'] = $isPublished;

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully!');
    }

    /**
     * Generate AI content for blog post
     */
    public function generateAiContent(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
            'generate_image' => 'sometimes|boolean',
        ]);

        try {
            // Get AI settings from config or database
            $apiKey = config('services.openai.api_key'); // You'll need to add this to config
            $model = config('services.openai.model', 'gpt-3.5-turbo');
            $temperature = (float) config('services.openai.temperature', 0.7);

            if (!$apiKey) {
                return response()->json([
                    'error' => 'OpenAI API key not configured. Please configure it in the AI Integration settings.'
                ], 400);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional content writer and SEO expert.\n\nWrite a high-quality blog post with:\n• An engaging <h1> title (first line).\n• Several well-structured sections using <h2> and <h3> headings.\n• Informative paragraphs, bullet lists, and call-to-action where appropriate.\n• At least two royalty-free illustrative images embedded with <img> tags and descriptive alt text.\n• Helpful links when relevant (anchor tags).\n\nReturn only HTML for the blog article (no <!DOCTYPE> or <html>/<body> wrappers).'
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->prompt
                    ]
                ],
                'temperature' => $temperature,
                'max_tokens' => 2000,
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Failed to generate content: ' . $response->json('error.message', 'Unknown error')
                ], 500);
            }

            $content = $response->json('choices.0.message.content');
            
            // Extract title and content from AI response
            $lines = explode("\n", $content);
            $title = '';
            $bodyContent = '';

            $skipPrefixes = ['<!DOCTYPE', '<html', '</html', '<head', '</head', '<body', '</body'];

            foreach ($lines as $line) {
                $trimmed = trim($line);

                // Skip blank lines and unwanted structural tags
                if ($trimmed === '') continue;

                $shouldSkip = false;
                foreach ($skipPrefixes as $prefix) {
                    if (str_starts_with($trimmed, $prefix)) { $shouldSkip = true; break; }
                }
                if ($shouldSkip) continue;

                if ($title === '') {
                    // Prefer content inside <h1> tag if present
                    if (preg_match('/<h1[^>]*>(.*?)<\/h1>/i', $trimmed, $m)) {
                        $title = strip_tags($m[1]);
                        continue; // don't add heading to body
                    }

                    // Markdown heading starting with # or plain text line
                    if (!str_starts_with($trimmed, '#')) {
                        $title = strip_tags(str_replace(['#', '*'], '', $trimmed));
                        continue;
                    }
                }

                $bodyContent .= $line . "\n";
            }

            // If no clear title found, generate one from the prompt
            if (empty($title)) {
                $title = 'Blog Post: ' . Str::limit($request->prompt, 50);
            }

            // Generate description from first paragraph
            $description = Str::limit(strip_tags($bodyContent), 160);

            // Generate a representative image using OpenAI image generation (optional)
            $imageUrl = null;
            if ($request->boolean('generate_image', true)) { // default to true if param missing
                try {
                    $imageResponse = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ])->timeout(60)->post('https://api.openai.com/v1/images/generations', [
                        'model'  => 'dall-e-3',
                        'prompt' => 'A high-quality, photorealistic cover image that illustrates the topic: ' . $request->prompt,
                        'n'      => 1,
                        'size'   => '1024x1024',
                    ]);

                    if (!$imageResponse->failed()) {
                        $imageUrl = $imageResponse->json('data.0.url');
                    }
                } catch (\Exception $e) {
                    // silently ignore image generation failure to avoid breaking main content generation
                }
            }

            // If an image was generated, inject it at the top of the content unless content already has images
            if ($imageUrl && !preg_match('/<img\s/i', $bodyContent)) {
                $imgTag = '<p><img src="' . $imageUrl . '" alt="' . e($title) . '" style="max-width:100%;height:auto;"></p>';
                $bodyContent = $imgTag . "\n" . $bodyContent;
            }

            // Ensure image src attributes are absolute; replace relative ones with Unsplash placeholders
            $bodyContent = preg_replace_callback('/<img[^>]+src=["\\\']([^"\\\']+)["\\\'][^>]*>/i', function ($m) {
                $src = $m[1];
                if (preg_match('/^https?:\/\//i', $src)) {
                    return $m[0]; // already absolute
                }
                $placeholder = 'https://source.unsplash.com/featured/?blog';
                return str_replace($src, $placeholder, $m[0]);
            }, $bodyContent);

            return response()->json([
                'title' => trim($title),
                'content' => trim($bodyContent),
                'description' => $description,
                'meta_title' => trim($title),
                'meta_description' => $description,
                'featured_image' => $imageUrl,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate content: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Regenerate AI content
     */
    public function regenerateContent(Request $request)
    {
        return $this->generateAiContent($request);
    }
}
