<x-admin.layout title="Edit Blog Post">
    <div class="content-card card">
        <div class="heading">
            <h2>Edit Blog Post</h2>
            <p>Update your blog post content and settings.</p>
        </div>

        @if($errors->any())
            <div class="alert error">
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" id="blogForm">
            @csrf
            @method('PUT')

            <!-- Blog Post Title -->
            <div class="form-element {{ $errors->has('title') ? 'is-error' : '' }}">
                <label>Blog Post Title</label>
                <input type="text" name="title" id="blogTitle" value="{{ old('title', $blog->title) }}" 
                       placeholder="Enter your blog post title" required>
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- AI Indicator -->
            @if($blog->is_ai_generated)
                <div style="margin: 1rem 0; padding: 1rem; background: #dbeafe; border: 1px solid #93c5fd; border-radius: 8px;">
                    <div style="display: flex; align-items: center;">
                        <span style="font-size: 1.25rem; margin-right: 0.5rem;">🤖</span>
                        <span style="color: #1e40af; font-weight: 600;">This post was generated using AI</span>
                    </div>
                    @if($blog->ai_prompts)
                        <div style="margin-top: 0.5rem;">
                            <small style="color: #1e40af;">
                                Original prompt: {{ is_array($blog->ai_prompts) ? implode(', ', $blog->ai_prompts) : $blog->ai_prompts }}
                            </small>
                        </div>
                    @endif
                </div>
            @endif

            <!-- AI Generation Section for existing posts -->
            <div style="margin: 2rem 0; padding: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                <h3 style="margin-bottom: 1rem; color: #374151; display: flex; align-items: center;">
                    <span style="font-size: 1.25rem; margin-right: 0.5rem;">🤖</span>
                    Enhance with AI
                </h3>
                
                <div class="form-element">
                    <label>Enhancement Prompt</label>
                    <textarea id="aiPrompt" placeholder="e.g., 'Add more examples and improve the conclusion'" 
                              style="height: 80px; resize: vertical;"></textarea>
                    <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                        Describe how you want to improve or modify this content.
                    </small>
                </div>

                <button type="button" id="generateContent" class="button is-primary" style="margin-top: 1rem;">
                    <span id="generateText">Enhance Content</span>
                    <span id="generateLoader" style="display: none;">Generating...</span>
                </button>
            </div>

            <!-- AI Generated Content Section -->
            <div id="aiGeneratedSection" style="display: none; margin: 2rem 0; padding: 1.5rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
                <h3 style="margin-bottom: 1rem; color: #166534; display: flex; align-items: center; justify-content: space-between;">
                    <span>
                        <span style="font-size: 1.25rem; margin-right: 0.5rem;">✨</span>
                        AI Enhanced Content
                    </span>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" id="copyContent" class="button is-small is-secondary">
                            <span>📋 Replace Current</span>
                        </button>
                        <button type="button" id="regenerateContent" class="button is-small is-primary">
                            <span>🔄 Regenerate</span>
                        </button>
                    </div>
                </h3>

                <div id="aiGeneratedContent">
                    <div class="form-element">
                        <label>Enhanced Title</label>
                        <div id="generatedTitle" style="padding: 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 4px; font-weight: 600;"></div>
                    </div>

                    <div class="form-element">
                        <label>Enhanced Description</label>
                        <div id="generatedDescription" style="padding: 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 4px; min-height: 60px;"></div>
                    </div>

                    <div class="form-element">
                        <label>Enhanced Content</label>
                        <div id="generatedContent" style="padding: 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 4px; min-height: 200px; max-height: 400px; overflow-y: auto;"></div>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="form-element {{ $errors->has('slug') ? 'is-error' : '' }}">
                <label>URL Slug</label>
                <input type="text" name="slug" id="blogSlug" value="{{ old('slug', $blog->slug) }}" 
                       placeholder="auto-generated-from-title">
                @error('slug')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-element {{ $errors->has('description') ? 'is-error' : '' }}">
                <label>Description</label>
                <textarea name="description" id="blogDescription" placeholder="A brief description of your blog post" 
                          style="height: 80px; resize: vertical;">{{ old('description', $blog->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-element {{ $errors->has('content') ? 'is-error' : '' }}">
                <label>Content</label>
                <textarea name="content" id="blogContent" style="display:none;">{{ old('content', $blog->content) }}</textarea>
                <div id="quillEditor" style="height:300px;">{!! old('content', $blog->content) !!}</div>
                @error('content')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- SEO Section -->
            <div style="margin: 2rem 0;">
                <h3 style="margin-bottom: 1rem; color: #374151;">SEO Settings</h3>
                
                <div class="form-element {{ $errors->has('meta_title') ? 'is-error' : '' }}">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" id="metaTitle" value="{{ old('meta_title', $blog->meta_title) }}" 
                           placeholder="SEO title for search engines">
                    @error('meta_title')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-element {{ $errors->has('meta_description') ? 'is-error' : '' }}">
                    <label>Meta Description</label>
                    <textarea name="meta_description" id="metaDescription" 
                              placeholder="SEO description for search engines" 
                              style="height: 80px; resize: vertical;">{{ old('meta_description', $blog->meta_description) }}</textarea>
                    @error('meta_description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Publishing Options -->
            <div style="margin: 2rem 0;">
                <h3 style="margin-bottom: 1rem; color: #374151;">Publishing Options</h3>
                
                <div class="form-element">
                    <label class="checkbox">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                        <label>Published</label>
                    </label>
                </div>

                <div class="form-element {{ $errors->has('published_at') ? 'is-error' : '' }}">
                    <label>Publish Date</label>
                    <input type="datetime-local" name="published_at" 
                           value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}">
                    @error('published_at')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="button is-primary">Update Blog Post</button>
                <a href="{{ route('admin.blogs.index') }}" class="button is-secondary">Cancel</a>
                <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" 
                      style="display: inline;"
                      onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button is-danger">Delete Post</button>
                </form>
            </div>
        </form>
    </div>

    <!-- Quill WYSIWYG Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        var quill = new Quill('#quillEditor', {
            theme: 'snow',
            modules: {
                toolbar: [[{ header: [1,2,3,false]}],['bold','italic','underline','link'],[{list:'ordered'},{list:'bullet'}],['image','code-block'],['clean']]
            }
        });

        document.getElementById('blogForm').addEventListener('submit', function(e){
            const html = quill.root.innerHTML.trim();
            if(html === '' || html === '<p><br></p>'){
                alert('Please enter content for your blog post.');
                e.preventDefault();
                return;
            }
            document.getElementById('blogContent').value = html;
        });

        let currentPrompt = '';

        document.getElementById('generateContent').addEventListener('click', function() {
            generateAIContent();
        });

        document.getElementById('regenerateContent').addEventListener('click', function() {
            generateAIContent();
        });

        document.getElementById('copyContent').addEventListener('click', function() {
            copyAIContentToForm();
        });

        function generateAIContent() {
            const prompt = document.getElementById('aiPrompt').value.trim();
            
            if (!prompt) {
                alert('Please enter an enhancement prompt');
                return;
            }

            // Include current content for context
            const currentTitle = document.getElementById('blogTitle').value;
            const currentContent = document.getElementById('blogContent').value;
            const enhancedPrompt = `Current title: "${currentTitle}"\n\nCurrent content:\n${currentContent}\n\nEnhancement request: ${prompt}`;

            currentPrompt = prompt;
            const button = document.getElementById('generateContent');
            const regenerateButton = document.getElementById('regenerateContent');
            const generateText = document.getElementById('generateText');
            const generateLoader = document.getElementById('generateLoader');

            // Show loading state
            generateText.style.display = 'none';
            generateLoader.style.display = 'inline';
            button.disabled = true;
            if (regenerateButton) regenerateButton.disabled = true;

            fetch('{{ route("admin.blogs.generate-ai-content") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ prompt: enhancedPrompt })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                    return;
                }

                // Display generated content
                document.getElementById('generatedTitle').textContent = data.title || currentTitle;
                document.getElementById('generatedDescription').textContent = data.description || '';
                document.getElementById('generatedContent').innerHTML = data.content || '';
                
                document.getElementById('aiGeneratedSection').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to generate content. Please try again.');
            })
            .finally(() => {
                // Reset loading state
                generateText.style.display = 'inline';
                generateLoader.style.display = 'none';
                button.disabled = false;
                if (regenerateButton) regenerateButton.disabled = false;
            });
        }

        function copyAIContentToForm() {
            const title = document.getElementById('generatedTitle').textContent;
            const description = document.getElementById('generatedDescription').textContent;
            const content = document.getElementById('generatedContent').innerHTML;

            if (title && title !== document.getElementById('blogTitle').value) {
                document.getElementById('blogTitle').value = title;
            }

            if (description) {
                document.getElementById('blogDescription').value = description;
                if (!document.getElementById('metaDescription').value) {
                    document.getElementById('metaDescription').value = description;
                }
            }

            if (content) {
                document.getElementById('blogContent').value = content;
            }

            if (title && !document.getElementById('metaTitle').value) {
                document.getElementById('metaTitle').value = title;
            }

            alert('AI enhanced content applied to form!');
        }
    </script>
</x-admin.layout>
