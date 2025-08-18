<x-admin.layout title="AI Integration">
    <div class="content-card card">
        <div class="heading">
            <h2>AI Integration</h2>
            <p>Configure AI-powered features for your application.</p>
        </div>

        @if(session('success'))
            <div class="alert success">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.ai-integration.update') }}">
            @csrf

            <!-- AI Enable/Disable -->
            <div class="form-element">
                <label class="checkbox">
                    <input type="checkbox" name="ai_enabled" value="1" {{ old('ai_enabled', config('ai.enabled', false)) ? 'checked' : '' }}>
                    <label>Enable AI Features</label>
                </label>
                @error('ai_enabled')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- OpenAI API Key -->
            <div class="form-element {{ $errors->has('openai_api_key') ? 'is-error' : '' }}">
                <label>OpenAI API Key</label>
                <input type="password" name="openai_api_key" value="{{ old('openai_api_key', config('services.openai.api_key')) }}" placeholder="sk-...">
                @error('openai_api_key')
                    <div class="error">{{ $message }}</div>
                @enderror
                <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                    Your OpenAI API key for AI-powered features. Get one at <a href="https://platform.openai.com/api-keys" target="_blank" style="color: #3b82f6;">OpenAI Platform</a>
                </small>
            </div>

            <!-- OpenAI Model -->
            <div class="form-element {{ $errors->has('openai_model') ? 'is-error' : '' }}">
                <label>OpenAI Model</label>
                <select name="openai_model">
                    <option value="gpt-3.5-turbo" {{ old('openai_model', config('services.openai.model', 'gpt-3.5-turbo')) == 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo (Recommended)</option>
                    <option value="gpt-4" {{ old('openai_model', config('services.openai.model')) == 'gpt-4' ? 'selected' : '' }}>GPT-4 (More Advanced)</option>
                    <option value="gpt-4-turbo" {{ old('openai_model', config('services.openai.model')) == 'gpt-4-turbo' ? 'selected' : '' }}>GPT-4 Turbo (Latest)</option>
                </select>
                @error('openai_model')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <!-- AI Temperature -->
            <div class="form-element {{ $errors->has('ai_temperature') ? 'is-error' : '' }}">
                <label>AI Creativity Level</label>
                <input type="range" name="ai_temperature" min="0" max="2" step="0.1" value="{{ old('ai_temperature', config('services.openai.temperature', 0.7)) }}" 
                       oninput="this.nextElementSibling.textContent = this.value">
                <span style="margin-left: 1rem; font-weight: 600;">{{ old('ai_temperature', config('services.openai.temperature', 0.7)) }}</span>
                @error('ai_temperature')
                    <div class="error">{{ $message }}</div>
                @enderror
                <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                    Lower values (0.0-0.5) = More focused and consistent. Higher values (0.6-2.0) = More creative and varied.
                </small>
            </div>

            <!-- Auto Generate Descriptions -->
            <div class="form-element">
                <label class="checkbox">
                    <input type="checkbox" name="auto_generate_descriptions" value="1" {{ old('auto_generate_descriptions', config('ai.auto_generate_descriptions', false)) ? 'checked' : '' }}>
                    <label>Auto-generate Product Descriptions</label>
                </label>
                @error('auto_generate_descriptions')
                    <div class="error">{{ $message }}</div>
                @enderror
                <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                    Automatically generate compelling product descriptions using AI when creating new products.
                </small>
            </div>

            <!-- Feature Cards -->
            <div style="margin: 2rem 0;">
                <h3 style="margin-bottom: 1rem; color: #374151; font-size: 1.125rem;">Available AI Features</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    
                    <!-- Smart Descriptions -->
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; background: #f9fafb;">
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-size: 1.5rem; margin-right: 0.5rem;">🤖</span>
                            <h4 style="margin: 0; color: #1f2937;">Smart Descriptions</h4>
                        </div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
                            Generate compelling product descriptions that convert visitors into customers using advanced AI.
                        </p>
                    </div>

                    <!-- Content Optimization -->
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; background: #f9fafb;">
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-size: 1.5rem; margin-right: 0.5rem;">⚡</span>
                            <h4 style="margin: 0; color: #1f2937;">Content Optimization</h4>
                        </div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
                            Optimize existing content for better engagement and search engine visibility.
                        </p>
                    </div>

                    <!-- Blog Post Generation -->
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; background: #f9fafb;">
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-size: 1.5rem; margin-right: 0.5rem;">📝</span>
                            <h4 style="margin: 0; color: #1f2937;">AI Blog Posts</h4>
                        </div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
                            Generate complete, engaging blog posts from simple prompts to boost your content marketing.
                        </p>
                    </div>

                    <!-- Language Translation -->
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; background: #f9fafb;">
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-size: 1.5rem; margin-right: 0.5rem;">🌍</span>
                            <h4 style="margin: 0; color: #1f2937;">Multi-language Support</h4>
                        </div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
                            Automatically translate product descriptions and content to reach global audiences.
                        </p>
                    </div>

                </div>
            </div>

            <button type="submit" class="button is-primary">Save AI Settings</button>
        </form>
    </div>
</x-admin.layout>
