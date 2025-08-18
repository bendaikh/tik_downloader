<x-admin.layout title="Add New Blog Post">
    <div class="content-card card">
        <div class="heading">
            <h2>Add New Blog Post</h2>
            <p>Create a new blog post using our AI-powered assistant or write your own.</p>
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

        <form method="POST" action="{{ route('admin.blogs.store') }}" id="blogForm">
            @csrf

            <!-- Blog Post Title -->
            <div class="form-element {{ $errors->has('title') ? 'is-error' : '' }}">
                <label>Blog Post Title</label>
                <input type="text" name="title" id="blogTitle" value="{{ old('title') }}" 
                       placeholder="Enter your blog post title" required>
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- AI Generation Section -->
            <div style="margin: 2rem 0; padding: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                <h3 style="margin-bottom: 1rem; color: #374151; display: flex; align-items: center;">
                    <span style="font-size: 1.25rem; margin-right: 0.5rem;">🤖</span>
                    Generate with AI
                </h3>
                
                <div class="form-element">
                    <label>Content Prompt</label>
                    <textarea id="aiPrompt" placeholder="e.g., 'The impact of AI on modern web design'" 
                              style="height: 80px; resize: vertical;"></textarea>
                    <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                        Describe what you want to write about. Be specific for better results.
                    </small>
                </div>

                <button type="button" id="generateContent" class="button is-primary" style="margin-top: 1rem;">
                    <span id="generateText">Generate Content</span>
                    <span id="generateLoader" style="display: none;">Generating...</span>
                </button>
            </div>

            <!-- AI Generated Content Section -->
            <div id="aiGeneratedSection" style="display: none; margin: 2rem 0; padding: 1.5rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
                <h3 style="margin-bottom: 1rem; color: #166534; display: flex; align-items: center; justify-content: space-between;">
                    <span>
                        <span style="font-size: 1.25rem; margin-right: 0.5rem;">✨</span>
                        AI Generated Content
                    </span>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" id="copyContent" class="button is-small is-secondary">
                            <span>📋 Copy</span>
                        </button>
                        <button type="button" id="regenerateContent" class="button is-small is-primary">
                            <span>🔄 Regenerate</span>
                        </button>
                    </div>
                </h3>

                <div id="aiGeneratedContent">
                    <div class="form-element">
                        <label>Generated Title</label>
                        <div id="generatedTitle" contenteditable="true" style="padding: 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 4px; font-weight: 600;"></div>
                    </div>

                    <div class="form-element">
                        <label>Generated Description</label>
                        <div id="generatedDescription" contenteditable="true" style="padding: 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 4px; min-height: 60px;"></div>
                    </div>

                    <div class="form-element">
                        <label>Generated Content</label>
                        <div id="generatedContent" contenteditable="true" style="padding: 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 4px; min-height: 200px; max-height: 400px; overflow-y: auto;"></div>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="form-element {{ $errors->has('slug') ? 'is-error' : '' }}">
                <label>URL Slug (Optional)</label>
                <input type="text" name="slug" id="blogSlug" value="{{ old('slug') }}" 
                       placeholder="auto-generated-from-title">
                @error('slug')
                    <div class="error">{{ $message }}</div>
                @enderror
                <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                    Leave empty to auto-generate from title
                </small>
            </div>

            <div class="form-element {{ $errors->has('description') ? 'is-error' : '' }}">
                <label>Description (Optional)</label>
                <textarea name="description" id="blogDescription" placeholder="A brief description of your blog post" 
                          style="height: 80px; resize: vertical;">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-element {{ $errors->has('content') ? 'is-error' : '' }}">
                <!-- Hidden textarea that will receive Quill HTML on submit -->
                <textarea name="content" id="blogContent" style="display:none;">{{ old('content') }}</textarea>
                <!-- Quill editor placeholder -->
                <div id="quillEditor" style="height: 300px;">{!! old('content') !!}</div>
                @error('content')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- SEO Section -->
            <div style="margin: 2rem 0;">
                <h3 style="margin-bottom: 1rem; color: #374151;">SEO Settings</h3>
                
                <div class="form-element {{ $errors->has('meta_title') ? 'is-error' : '' }}">
                    <label>Meta Title (Optional)</label>
                    <input type="text" name="meta_title" id="metaTitle" value="{{ old('meta_title') }}" 
                           placeholder="SEO title for search engines">
                    @error('meta_title')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-element {{ $errors->has('meta_description') ? 'is-error' : '' }}">
                    <label>Meta Description (Optional)</label>
                    <textarea name="meta_description" id="metaDescription" 
                              placeholder="SEO description for search engines" 
                              style="height: 80px; resize: vertical;">{{ old('meta_description') }}</textarea>
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
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                        <label>Publish immediately</label>
                    </label>
                </div>

                <div class="form-element {{ $errors->has('published_at') ? 'is-error' : '' }}">
                    <label>Publish Date (Optional)</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at') }}">
                    @error('published_at')
                        <div class="error">{{ $message }}</div>
                    @enderror
                    <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                        Leave empty to use current time when publishing
                    </small>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="button is-primary">Save Blog Post</button>
                <a href="{{ route('admin.blogs.index') }}" class="button is-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Quill WYSIWYG Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        var quill = new Quill('#quillEditor', {
            theme: 'snow',
            placeholder: 'Write your blog content here...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'link'],
                    [{ list: 'ordered'}, { list: 'bullet' }],
                    ['image', 'code-block'],
                    ['clean']
                ]
            }
        });

        // Persist Quill content on form submit
        document.getElementById('blogForm').addEventListener('submit', function (e) {
            const html = quill.root.innerHTML.trim();
            if (html === '' || html === '<p><br></p>') {
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

        // Auto-generate slug from title
        document.getElementById('blogTitle').addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            document.getElementById('blogSlug').value = slug;
        });

        function generateAIContent() {
            const prompt = document.getElementById('aiPrompt').value.trim();
            
            if (!prompt) {
                alert('Please enter a content prompt');
                return;
            }

            currentPrompt = prompt;
            const button = document.getElementById('generateContent');
            const regenerateButton = document.getElementById('regenerateContent');
            const generateText = document.getElementById('generateText');
            const generateLoader = document.getElementById('generateLoader');

            // Show loading state
            generateText.style.display = 'none';
            generateLoader.style.display = 'inline';
            button.disabled = true;
            regenerateButton.disabled = true;

            fetch('{{ route("admin.blogs.generate-ai-content") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                    return;
                }

                // Display generated content
                document.getElementById('generatedTitle').textContent = data.title || '';
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
                regenerateButton.disabled = false;
            });
        }

        function copyAIContentToForm() {
            const title = document.getElementById('generatedTitle').textContent;
            const description = document.getElementById('generatedDescription').textContent;
            const content = document.getElementById('generatedContent').innerHTML;

            if (title) {
                document.getElementById('blogTitle').value = title;
                // Trigger slug generation
                document.getElementById('blogTitle').dispatchEvent(new Event('input'));
            }

            if (description) {
                document.getElementById('blogDescription').value = description;
                document.getElementById('metaDescription').value = description;
            }

            if (content) {
                if (typeof quill !== 'undefined') {
                    quill.root.innerHTML = content;
                } else {
                    document.getElementById('blogContent').value = content;
                }
            }

            if (title) {
                document.getElementById('metaTitle').value = title;
            }

            alert('AI content copied to form!');
        }
    </script>
</x-admin.layout>
