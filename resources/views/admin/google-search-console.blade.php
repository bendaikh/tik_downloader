<x-admin.layout title="Google Search Console Settings">
    <div class="content-card card">
        <div class="heading">
            <h2>Google Search Console Settings</h2>
            <p>Configure Google Search Console integration for better SEO and search visibility</p>
        </div>

        <form
            action="{{route('admin.google-search-console.update')}}"
            method="POST"
            id="googleSearchConsoleForm"
        >
            @csrf
            
            <!-- Google Search Console Configuration Section -->
            <div class="settings-section">
                <h3>Google Search Console Configuration</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('gsc_enabled')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="gsc_enabled" 
                            value="1" 
                            {{config('search_console.gsc_enabled') ? 'checked' : ''}}
                        >
                        <span>Enable Google Search Console</span>
                    </label>
                    <small>Enable Google Search Console integration for SEO monitoring</small>
                    @error('gsc_enabled')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('gsc_property_url')])>
                    <label for="gsc_property_url">Property URL</label>
                    <input 
                        type="url" 
                        id="gsc_property_url" 
                        name="gsc_property_url" 
                        value="{{config('search_console.gsc_property_url')}}"
                        placeholder="https://yourdomain.com"
                    >
                    <small>Your website URL as registered in Google Search Console</small>
                    @error('gsc_property_url')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Verification Methods Section -->
            <div class="settings-section">
                <h3>Website Verification</h3>
                <p class="section-description">Choose one of the following verification methods to prove ownership of your website:</p>
                
                <div class="verification-methods">
                    <!-- HTML File Verification -->
                    <div class="verification-method">
                        <div @class(['form-element', 'is-error'=> $errors->has('gsc_verification_method')])>
                            <label class="radio-label">
                                <input 
                                    type="radio" 
                                    name="gsc_verification_method" 
                                    value="html_file" 
                                    {{config('search_console.gsc_verification_method') === 'html_file' ? 'checked' : ''}}
                                    onchange="toggleVerificationMethod('html_file')"
                                >
                                <span>HTML File Upload</span>
                            </label>
                            <small>Upload an HTML file to your website root directory</small>
                        </div>
                        
                        <div id="html_file_section" class="verification-details" style="display: {{config('search_console.gsc_verification_method') === 'html_file' ? 'block' : 'none'}};">
                            <div @class(['form-element', 'is-error'=> $errors->has('gsc_html_file_content')])>
                                <label for="gsc_html_file_content">HTML File Content</label>
                                <textarea 
                                    id="gsc_html_file_content" 
                                    name="gsc_html_file_content" 
                                    rows="3"
                                    placeholder="google1234567890abcdef.html"
                                >{{config('search_console.gsc_html_file_content')}}</textarea>
                                <small>Copy the content from Google Search Console (usually starts with "google")</small>
                                @error('gsc_html_file_content')
                                <div class="error">{{$message}}</div>
                                @enderror
                            </div>
                            
                            @if(config('search_console.gsc_html_filename'))
                            <div class="verification-status">
                                <div class="status-success">
                                    ✅ Verification file created: <code>{{config('search_console.gsc_html_filename')}}</code>
                                    <br>
                                    <small>Access it at: <a href="{{url(config('search_console.gsc_html_filename'))}}" target="_blank">{{url(config('search_console.gsc_html_filename'))}}</a></small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Meta Tag Verification -->
                    <div class="verification-method">
                        <div @class(['form-element', 'is-error'=> $errors->has('gsc_verification_method')])>
                            <label class="radio-label">
                                <input 
                                    type="radio" 
                                    name="gsc_verification_method" 
                                    value="meta_tag" 
                                    {{config('search_console.gsc_verification_method') === 'meta_tag' ? 'checked' : ''}}
                                    onchange="toggleVerificationMethod('meta_tag')"
                                >
                                <span>HTML Meta Tag</span>
                            </label>
                            <small>Add a meta tag to your website's &lt;head&gt; section</small>
                        </div>
                        
                        <div id="meta_tag_section" class="verification-details" style="display: {{config('search_console.gsc_verification_method') === 'meta_tag' ? 'block' : 'none'}};">
                            <div @class(['form-element', 'is-error'=> $errors->has('gsc_meta_tag')])>
                                <label for="gsc_meta_tag">Meta Tag Content</label>
                                <input 
                                    type="text" 
                                    id="gsc_meta_tag" 
                                    name="gsc_meta_tag" 
                                    value="{{config('search_console.gsc_meta_tag')}}"
                                    placeholder="name=&quot;google-site-verification&quot; content=&quot;your-verification-code&quot;"
                                >
                                <small>Copy the meta tag from Google Search Console</small>
                                @error('gsc_meta_tag')
                                <div class="error">{{$message}}</div>
                                @enderror
                            </div>
                            
                            @if(config('search_console.gsc_meta_tag'))
                            <div class="verification-status">
                                <div class="status-info">
                                    ℹ️ Meta tag will be automatically added to your website's head section
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- DNS Record Verification -->
                    <div class="verification-method">
                        <div @class(['form-element', 'is-error'=> $errors->has('gsc_verification_method')])>
                            <label class="radio-label">
                                <input 
                                    type="radio" 
                                    name="gsc_verification_method" 
                                    value="dns_record" 
                                    {{config('search_console.gsc_verification_method') === 'dns_record' ? 'checked' : ''}}
                                    onchange="toggleVerificationMethod('dns_record')"
                                >
                                <span>DNS Record</span>
                            </label>
                            <small>Add a TXT record to your domain's DNS settings</small>
                        </div>
                        
                        <div id="dns_record_section" class="verification-details" style="display: {{config('search_console.gsc_verification_method') === 'dns_record' ? 'block' : 'none'}};">
                            <div @class(['form-element', 'is-error'=> $errors->has('gsc_dns_record')])>
                                <label for="gsc_dns_record">DNS TXT Record</label>
                                <input 
                                    type="text" 
                                    id="gsc_dns_record" 
                                    name="gsc_dns_record" 
                                    value="{{config('search_console.gsc_dns_record')}}"
                                    placeholder="google-site-verification=your-verification-code"
                                >
                                <small>Copy the TXT record from Google Search Console</small>
                                @error('gsc_dns_record')
                                <div class="error">{{$message}}</div>
                                @enderror
                            </div>
                            
                            @if(config('search_console.gsc_dns_record'))
                            <div class="verification-status">
                                <div class="status-warning">
                                    ⚠️ You need to manually add this TXT record to your domain's DNS settings
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sitemap Section -->
            <div class="settings-section">
                <h3>Sitemap Configuration</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('gsc_auto_submit_sitemap')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="gsc_auto_submit_sitemap" 
                            value="1" 
                            {{config('search_console.gsc_auto_submit_sitemap') ? 'checked' : ''}}
                        >
                        <span>Auto-submit Sitemap</span>
                    </label>
                    <small>Automatically submit sitemap to Google Search Console when updated</small>
                    @error('gsc_auto_submit_sitemap')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('gsc_sitemap_url')])>
                    <label for="gsc_sitemap_url">Sitemap URL</label>
                    <input 
                        type="url" 
                        id="gsc_sitemap_url" 
                        name="gsc_sitemap_url" 
                        value="{{config('search_console.gsc_sitemap_url')}}"
                        placeholder="https://yourdomain.com/sitemap.xml"
                    >
                    <small>Your sitemap URL (usually /sitemap.xml)</small>
                    @error('gsc_sitemap_url')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div class="sitemap-actions">
                    <button type="button" class="btn-secondary" onclick="generateSitemap()">
                        🔄 Generate Sitemap
                    </button>
                    @if(file_exists(public_path('sitemap.xml')))
                    <div class="sitemap-status">
                        <div class="status-success">
                            ✅ Sitemap exists at: <a href="{{url('sitemap.xml')}}" target="_blank">{{url('sitemap.xml')}}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Setup Instructions Section -->
            <div class="settings-section">
                <h3>Setup Instructions</h3>
                <div class="instructions">
                    <ol>
                        <li>Go to <a href="https://search.google.com/search-console" target="_blank">Google Search Console</a></li>
                        <li>Add your property (website URL)</li>
                        <li>Choose a verification method above</li>
                        <li>Follow the verification steps for your chosen method</li>
                        <li>Generate and submit your sitemap</li>
                        <li>Wait for Google to verify your ownership</li>
                        <li>Monitor your search performance in Google Search Console</li>
                    </ol>
                </div>
            </div>

            <!-- Verification Status Section -->
            <div class="settings-section">
                <h3>Verification Status</h3>
                <div class="verification-status-overview">
                    @if(config('search_console.gsc_enabled'))
                        <div class="status-item">
                            <span class="status-icon">✅</span>
                            <span>Google Search Console is enabled</span>
                        </div>
                        
                        @if(config('search_console.gsc_verification_method'))
                        <div class="status-item">
                            <span class="status-icon">✅</span>
                            <span>Verification method: {{ucfirst(str_replace('_', ' ', config('search_console.gsc_verification_method')))}}</span>
                        </div>
                        @else
                        <div class="status-item">
                            <span class="status-icon">⚠️</span>
                            <span>No verification method selected</span>
                        </div>
                        @endif
                        
                        @if(file_exists(public_path('sitemap.xml')))
                        <div class="status-item">
                            <span class="status-icon">✅</span>
                            <span>Sitemap is available</span>
                        </div>
                        @else
                        <div class="status-item">
                            <span class="status-icon">⚠️</span>
                            <span>Sitemap not found - generate one above</span>
                        </div>
                        @endif
                    @else
                        <div class="status-item">
                            <span class="status-icon">❌</span>
                            <span>Google Search Console is disabled</span>
                        </div>
                    @endif
                </div>
            </div>

            @push('header')
                <button class="button is-primary" type="submit" form="googleSearchConsoleForm">Save Settings</button>
            @endpush
        </form>
    </div>

    <script>
        function toggleVerificationMethod(method) {
            // Hide all verification details
            document.querySelectorAll('.verification-details').forEach(el => {
                el.style.display = 'none';
            });
            
            // Show selected method details
            const selectedSection = document.getElementById(method + '_section');
            if (selectedSection) {
                selectedSection.style.display = 'block';
            }
        }

        function generateSitemap() {
            if (confirm('Generate a new sitemap? This will overwrite the existing sitemap.xml file.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{route("admin.google-search-console.generate-sitemap")}}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{csrf_token()}}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <style>
        .settings-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .settings-section h3 {
            margin-bottom: 1rem;
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .section-description {
            color: #666;
            margin-bottom: 1rem;
        }

        .verification-methods {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .verification-method {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            background: white;
        }

        .verification-details {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .radio-label, .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-weight: 500;
        }

        .radio-label input[type="radio"], .checkbox-label input[type="checkbox"] {
            margin: 0;
        }

        .verification-status {
            margin-top: 1rem;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
            padding: 0.75rem;
            border-radius: 6px;
            border-left: 4px solid #28a745;
        }

        .status-info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 0.75rem;
            border-radius: 6px;
            border-left: 4px solid #17a2b8;
        }

        .status-warning {
            background: #fff3cd;
            color: #856404;
            padding: 0.75rem;
            border-radius: 6px;
            border-left: 4px solid #ffc107;
        }

        .sitemap-actions {
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .verification-status-overview {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: white;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .status-icon {
            font-size: 1.2rem;
        }

        .instructions {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #2196f3;
        }

        .instructions ol {
            margin: 0;
            padding-left: 1.5rem;
        }

        .instructions li {
            margin-bottom: 0.5rem;
            color: #1976d2;
        }

        .instructions a {
            color: #2196f3;
            text-decoration: underline;
        }

        code {
            background: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.875rem;
        }
    </style>
</x-admin.layout>
