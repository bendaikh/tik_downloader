<x-admin.layout title="SEO Settings">
    <div class="content-card card">
        <div class="heading">
            <h2>SEO Settings</h2>
            <p>Optimize your TikTok downloader for better search engine rankings</p>
        </div>

        <form
            action="{{route('admin.seo-settings.update')}}"
            method="POST"
            id="seoSettingsForm"
        >
            @csrf
            
            <!-- Basic SEO Section -->
            <div class="settings-section">
                <h3>Basic SEO</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('seo_title')])>
                    <label for="seo_title">Meta Title</label>
                    <input 
                        type="text" 
                        id="seo_title" 
                        name="seo_title" 
                        value="{{config('seo.seo_title')}}"
                        placeholder="TikTok Video Downloader - Download Without Watermark"
                        maxlength="60"
                    >
                    <small>Optimal length: 50-60 characters. Leave empty to use default.</small>
                    @error('seo_title')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('seo_description')])>
                    <label for="seo_description">Meta Description</label>
                    <textarea 
                        id="seo_description" 
                        name="seo_description" 
                        rows="3"
                        placeholder="Download TikTok videos without watermark for free. Fast, secure, and easy to use TikTok video downloader."
                        maxlength="160"
                    >{{config('seo.seo_description')}}</textarea>
                    <small>Optimal length: 150-160 characters. Leave empty to use default.</small>
                    @error('seo_description')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('seo_keywords')])>
                    <label for="seo_keywords">Meta Keywords</label>
                    <input 
                        type="text" 
                        id="seo_keywords" 
                        name="seo_keywords" 
                        value="{{config('seo.seo_keywords')}}"
                        placeholder="tiktok downloader, tiktok video download, download tiktok without watermark"
                    >
                    <small>Comma-separated keywords. Less important for modern SEO but still used by some search engines.</small>
                    @error('seo_keywords')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('seo_author')])>
                    <label for="seo_author">Meta Author</label>
                    <input 
                        type="text" 
                        id="seo_author" 
                        name="seo_author" 
                        value="{{config('seo.seo_author')}}"
                        placeholder="Your Name or Company"
                    >
                    @error('seo_author')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('seo_robots')])>
                    <label for="seo_robots">Robots Meta Tag</label>
                    <select id="seo_robots" name="seo_robots">
                        <option value="">Use default</option>
                        <option value="index,follow" {{config('seo.seo_robots') == 'index,follow' ? 'selected' : ''}}>index,follow</option>
                        <option value="noindex,follow" {{config('seo.seo_robots') == 'noindex,follow' ? 'selected' : ''}}>noindex,follow</option>
                        <option value="index,nofollow" {{config('seo.seo_robots') == 'index,nofollow' ? 'selected' : ''}}>index,nofollow</option>
                        <option value="noindex,nofollow" {{config('seo.seo_robots') == 'noindex,nofollow' ? 'selected' : ''}}>noindex,nofollow</option>
                    </select>
                    @error('seo_robots')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('seo_canonical')])>
                    <label for="seo_canonical">Canonical URL</label>
                    <input 
                        type="url" 
                        id="seo_canonical" 
                        name="seo_canonical" 
                        value="{{config('seo.seo_canonical')}}"
                        placeholder="https://yourdomain.com"
                    >
                    <small>Leave empty to use current page URL. Useful for preventing duplicate content issues.</small>
                    @error('seo_canonical')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Open Graph Section -->
            <div class="settings-section">
                <h3>Open Graph (Social Media)</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('og_title')])>
                    <label for="og_title">OG Title</label>
                    <input 
                        type="text" 
                        id="og_title" 
                        name="og_title" 
                        value="{{config('seo.og_title')}}"
                        placeholder="TikTok Video Downloader - Free Download"
                        maxlength="60"
                    >
                    <small>Title shown when your site is shared on Facebook, Twitter, etc.</small>
                    @error('og_title')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('og_description')])>
                    <label for="og_description">OG Description</label>
                    <textarea 
                        id="og_description" 
                        name="og_description" 
                        rows="3"
                        placeholder="Download TikTok videos without watermark instantly. Free, fast, and secure."
                        maxlength="160"
                    >{{config('seo.og_description')}}</textarea>
                    @error('og_description')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('og_image')])>
                    <label for="og_image">OG Image URL</label>
                    <input 
                        type="url" 
                        id="og_image" 
                        name="og_image" 
                        value="{{config('seo.og_image')}}"
                        placeholder="https://yourdomain.com/images/og-image.jpg"
                    >
                    <small>Recommended size: 1200x630 pixels. Image shown when your site is shared.</small>
                    @error('og_image')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('og_type')])>
                    <label for="og_type">OG Type</label>
                    <select id="og_type" name="og_type">
                        <option value="website" {{config('seo.og_type') == 'website' ? 'selected' : ''}}>website</option>
                        <option value="article" {{config('seo.og_type') == 'article' ? 'selected' : ''}}>article</option>
                        <option value="product" {{config('seo.og_type') == 'product' ? 'selected' : ''}}>product</option>
                    </select>
                    @error('og_type')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Twitter Cards Section -->
            <div class="settings-section">
                <h3>Twitter Cards</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('twitter_card')])>
                    <label for="twitter_card">Twitter Card Type</label>
                    <select id="twitter_card" name="twitter_card">
                        <option value="summary" {{config('seo.twitter_card') == 'summary' ? 'selected' : ''}}>summary</option>
                        <option value="summary_large_image" {{config('seo.twitter_card') == 'summary_large_image' ? 'selected' : ''}}>summary_large_image</option>
                        <option value="app" {{config('seo.twitter_card') == 'app' ? 'selected' : ''}}>app</option>
                        <option value="player" {{config('seo.twitter_card') == 'player' ? 'selected' : ''}}>player</option>
                    </select>
                    @error('twitter_card')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('twitter_site')])>
                    <label for="twitter_site">Twitter Site</label>
                    <input 
                        type="text" 
                        id="twitter_site" 
                        name="twitter_site" 
                        value="{{config('seo.twitter_site')}}"
                        placeholder="@yourusername"
                    >
                    <small>Your Twitter username (without @)</small>
                    @error('twitter_site')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('twitter_creator')])>
                    <label for="twitter_creator">Twitter Creator</label>
                    <input 
                        type="text" 
                        id="twitter_creator" 
                        name="twitter_creator" 
                        value="{{config('seo.twitter_creator')}}"
                        placeholder="@yourusername"
                    >
                    <small>Content creator's Twitter username (without @)</small>
                    @error('twitter_creator')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- Structured Data Section -->
            <div class="settings-section">
                <h3>Structured Data (Schema.org)</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('structured_data_enabled')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="structured_data_enabled" 
                            value="1" 
                            {{config('seo.structured_data_enabled') ? 'checked' : ''}}
                        >
                        <span>Enable Structured Data</span>
                    </label>
                    <small>Add JSON-LD structured data to help search engines understand your content</small>
                    @error('structured_data_enabled')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('structured_data_type')])>
                    <label for="structured_data_type">Organization Type</label>
                    <select id="structured_data_type" name="structured_data_type">
                        <option value="Organization" {{config('seo.structured_data_type') == 'Organization' ? 'selected' : ''}}>Organization</option>
                        <option value="Corporation" {{config('seo.structured_data_type') == 'Corporation' ? 'selected' : ''}}>Corporation</option>
                        <option value="LocalBusiness" {{config('seo.structured_data_type') == 'LocalBusiness' ? 'selected' : ''}}>Local Business</option>
                        <option value="WebSite" {{config('seo.structured_data_type') == 'WebSite' ? 'selected' : ''}}>Website</option>
                    </select>
                    @error('structured_data_type')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('structured_data_name')])>
                    <label for="structured_data_name">Organization Name</label>
                    <input 
                        type="text" 
                        id="structured_data_name" 
                        name="structured_data_name" 
                        value="{{config('seo.structured_data_name')}}"
                        placeholder="Your Company Name"
                    >
                    @error('structured_data_name')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('structured_data_description')])>
                    <label for="structured_data_description">Organization Description</label>
                    <textarea 
                        id="structured_data_description" 
                        name="structured_data_description" 
                        rows="3"
                        placeholder="Description of your organization or website"
                    >{{config('seo.structured_data_description')}}</textarea>
                    @error('structured_data_description')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('structured_data_url')])>
                    <label for="structured_data_url">Organization URL</label>
                    <input 
                        type="url" 
                        id="structured_data_url" 
                        name="structured_data_url" 
                        value="{{config('seo.structured_data_url')}}"
                        placeholder="https://yourdomain.com"
                    >
                    @error('structured_data_url')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('structured_data_logo')])>
                    <label for="structured_data_logo">Organization Logo URL</label>
                    <input 
                        type="url" 
                        id="structured_data_logo" 
                        name="structured_data_logo" 
                        value="{{config('seo.structured_data_logo')}}"
                        placeholder="https://yourdomain.com/images/logo.png"
                    >
                    <small>Recommended size: 112x112 pixels minimum</small>
                    @error('structured_data_logo')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('structured_data_contact_email')])>
                    <label for="structured_data_contact_email">Contact Email</label>
                    <input 
                        type="email" 
                        id="structured_data_contact_email" 
                        name="structured_data_contact_email" 
                        value="{{config('seo.structured_data_contact_email')}}"
                        placeholder="contact@yourdomain.com"
                    >
                    @error('structured_data_contact_email')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('structured_data_contact_phone')])>
                    <label for="structured_data_contact_phone">Contact Phone</label>
                    <input 
                        type="text" 
                        id="structured_data_contact_phone" 
                        name="structured_data_contact_phone" 
                        value="{{config('seo.structured_data_contact_phone')}}"
                        placeholder="+1-555-123-4567"
                    >
                    @error('structured_data_contact_phone')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div @class(['form-element', 'is-error'=> $errors->has('structured_data_address')])>
                        <label for="structured_data_address">Address</label>
                        <input 
                            type="text" 
                            id="structured_data_address" 
                            name="structured_data_address" 
                            value="{{config('seo.structured_data_address')}}"
                            placeholder="123 Main Street"
                        >
                        @error('structured_data_address')
                        <div class="error">{{$message}}</div>
                        @enderror
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('structured_data_city')])>
                        <label for="structured_data_city">City</label>
                        <input 
                            type="text" 
                            id="structured_data_city" 
                            name="structured_data_city" 
                            value="{{config('seo.structured_data_city')}}"
                            placeholder="New York"
                        >
                        @error('structured_data_city')
                        <div class="error">{{$message}}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div @class(['form-element', 'is-error'=> $errors->has('structured_data_state')])>
                        <label for="structured_data_state">State/Province</label>
                        <input 
                            type="text" 
                            id="structured_data_state" 
                            name="structured_data_state" 
                            value="{{config('seo.structured_data_state')}}"
                            placeholder="NY"
                        >
                        @error('structured_data_state')
                        <div class="error">{{$message}}</div>
                        @enderror
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('structured_data_zip')])>
                        <label for="structured_data_zip">ZIP/Postal Code</label>
                        <input 
                            type="text" 
                            id="structured_data_zip" 
                            name="structured_data_zip" 
                            value="{{config('seo.structured_data_zip')}}"
                            placeholder="10001"
                        >
                        @error('structured_data_zip')
                        <div class="error">{{$message}}</div>
                        @enderror
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('structured_data_country')])>
                        <label for="structured_data_country">Country</label>
                        <input 
                            type="text" 
                            id="structured_data_country" 
                            name="structured_data_country" 
                            value="{{config('seo.structured_data_country')}}"
                            placeholder="United States"
                        >
                        @error('structured_data_country')
                        <div class="error">{{$message}}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Schema Markup Section -->
            <div class="settings-section">
                <h3>Schema Markup Types</h3>
                
                <div class="schema-options">
                    <div @class(['form-element', 'is-error'=> $errors->has('schema_markup_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="schema_markup_enabled" 
                                value="1" 
                                {{config('seo.schema_markup_enabled') ? 'checked' : ''}}
                            >
                            <span>Enable Schema Markup</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('schema_organization_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="schema_organization_enabled" 
                                value="1" 
                                {{config('seo.schema_organization_enabled') ? 'checked' : ''}}
                            >
                            <span>Organization Schema</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('schema_website_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="schema_website_enabled" 
                                value="1" 
                                {{config('seo.schema_website_enabled') ? 'checked' : ''}}
                            >
                            <span>Website Schema</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('schema_webpage_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="schema_webpage_enabled" 
                                value="1" 
                                {{config('seo.schema_webpage_enabled') ? 'checked' : ''}}
                            >
                            <span>WebPage Schema</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('schema_article_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="schema_article_enabled" 
                                value="1" 
                                {{config('seo.schema_article_enabled') ? 'checked' : ''}}
                            >
                            <span>Article Schema (for blog posts)</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('schema_product_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="schema_product_enabled" 
                                value="1" 
                                {{config('seo.schema_product_enabled') ? 'checked' : ''}}
                            >
                            <span>Product Schema (for products)</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('schema_faq_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="schema_faq_enabled" 
                                value="1" 
                                {{config('seo.schema_faq_enabled') ? 'checked' : ''}}
                            >
                            <span>FAQ Schema (for FAQ pages)</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('schema_breadcrumb_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="schema_breadcrumb_enabled" 
                                value="1" 
                                {{config('seo.schema_breadcrumb_enabled') ? 'checked' : ''}}
                            >
                            <span>Breadcrumb Schema</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Sitemap Section -->
            <div class="settings-section">
                <h3>Sitemap Configuration</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('sitemap_enabled')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="sitemap_enabled" 
                            value="1" 
                            {{config('seo.sitemap_enabled') ? 'checked' : ''}}
                        >
                        <span>Enable Sitemap</span>
                    </label>
                    <small>Generate XML sitemap for search engines</small>
                    @error('sitemap_enabled')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('sitemap_auto_generate')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="sitemap_auto_generate" 
                            value="1" 
                            {{config('seo.sitemap_auto_generate') ? 'checked' : ''}}
                        >
                        <span>Auto-generate Sitemap</span>
                    </label>
                    <small>Automatically update sitemap when content changes</small>
                    @error('sitemap_auto_generate')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('sitemap_include_blogs')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="sitemap_include_blogs" 
                            value="1" 
                            {{config('seo.sitemap_include_blogs') ? 'checked' : ''}}
                        >
                        <span>Include Blog Posts</span>
                    </label>
                    @error('sitemap_include_blogs')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div @class(['form-element', 'is-error'=> $errors->has('sitemap_include_products')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="sitemap_include_products" 
                            value="1" 
                            {{config('seo.sitemap_include_products') ? 'checked' : ''}}
                        >
                        <span>Include Products</span>
                    </label>
                    @error('sitemap_include_products')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div class="sitemap-priorities">
                    <h4>Sitemap Priorities</h4>
                    <div class="form-row">
                        <div @class(['form-element', 'is-error'=> $errors->has('sitemap_priority_home')])>
                            <label for="sitemap_priority_home">Home Page Priority</label>
                            <input 
                                type="number" 
                                id="sitemap_priority_home" 
                                name="sitemap_priority_home" 
                                value="{{config('seo.sitemap_priority_home', '1.0')}}"
                                step="0.1"
                                min="0"
                                max="1"
                            >
                            @error('sitemap_priority_home')
                            <div class="error">{{$message}}</div>
                            @enderror
                        </div>

                        <div @class(['form-element', 'is-error'=> $errors->has('sitemap_priority_blogs')])>
                            <label for="sitemap_priority_blogs">Blog Posts Priority</label>
                            <input 
                                type="number" 
                                id="sitemap_priority_blogs" 
                                name="sitemap_priority_blogs" 
                                value="{{config('seo.sitemap_priority_blogs', '0.8')}}"
                                step="0.1"
                                min="0"
                                max="1"
                            >
                            @error('sitemap_priority_blogs')
                            <div class="error">{{$message}}</div>
                            @enderror
                        </div>

                        <div @class(['form-element', 'is-error'=> $errors->has('sitemap_priority_products')])>
                            <label for="sitemap_priority_products">Products Priority</label>
                            <input 
                                type="number" 
                                id="sitemap_priority_products" 
                                name="sitemap_priority_products" 
                                value="{{config('seo.sitemap_priority_products', '0.7')}}"
                                step="0.1"
                                min="0"
                                max="1"
                            >
                            @error('sitemap_priority_products')
                            <div class="error">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="sitemap-changefreq">
                    <h4>Change Frequency</h4>
                    <div class="form-row">
                        <div @class(['form-element', 'is-error'=> $errors->has('sitemap_changefreq_home')])>
                            <label for="sitemap_changefreq_home">Home Page</label>
                            <select id="sitemap_changefreq_home" name="sitemap_changefreq_home">
                                <option value="always" {{config('seo.sitemap_changefreq_home') == 'always' ? 'selected' : ''}}>always</option>
                                <option value="hourly" {{config('seo.sitemap_changefreq_home') == 'hourly' ? 'selected' : ''}}>hourly</option>
                                <option value="daily" {{config('seo.sitemap_changefreq_home') == 'daily' ? 'selected' : ''}}>daily</option>
                                <option value="weekly" {{config('seo.sitemap_changefreq_home') == 'weekly' ? 'selected' : ''}}>weekly</option>
                                <option value="monthly" {{config('seo.sitemap_changefreq_home') == 'monthly' ? 'selected' : ''}}>monthly</option>
                                <option value="yearly" {{config('seo.sitemap_changefreq_home') == 'yearly' ? 'selected' : ''}}>yearly</option>
                                <option value="never" {{config('seo.sitemap_changefreq_home') == 'never' ? 'selected' : ''}}>never</option>
                            </select>
                            @error('sitemap_changefreq_home')
                            <div class="error">{{$message}}</div>
                            @enderror
                        </div>

                        <div @class(['form-element', 'is-error'=> $errors->has('sitemap_changefreq_blogs')])>
                            <label for="sitemap_changefreq_blogs">Blog Posts</label>
                            <select id="sitemap_changefreq_blogs" name="sitemap_changefreq_blogs">
                                <option value="always" {{config('seo.sitemap_changefreq_blogs') == 'always' ? 'selected' : ''}}>always</option>
                                <option value="hourly" {{config('seo.sitemap_changefreq_blogs') == 'hourly' ? 'selected' : ''}}>hourly</option>
                                <option value="daily" {{config('seo.sitemap_changefreq_blogs') == 'daily' ? 'selected' : ''}}>daily</option>
                                <option value="weekly" {{config('seo.sitemap_changefreq_blogs') == 'weekly' ? 'selected' : ''}}>weekly</option>
                                <option value="monthly" {{config('seo.sitemap_changefreq_blogs') == 'monthly' ? 'selected' : ''}}>monthly</option>
                                <option value="yearly" {{config('seo.sitemap_changefreq_blogs') == 'yearly' ? 'selected' : ''}}>yearly</option>
                                <option value="never" {{config('seo.sitemap_changefreq_blogs') == 'never' ? 'selected' : ''}}>never</option>
                            </select>
                            @error('sitemap_changefreq_blogs')
                            <div class="error">{{$message}}</div>
                            @enderror
                        </div>

                        <div @class(['form-element', 'is-error'=> $errors->has('sitemap_changefreq_products')])>
                            <label for="sitemap_changefreq_products">Products</label>
                            <select id="sitemap_changefreq_products" name="sitemap_changefreq_products">
                                <option value="always" {{config('seo.sitemap_changefreq_products') == 'always' ? 'selected' : ''}}>always</option>
                                <option value="hourly" {{config('seo.sitemap_changefreq_products') == 'hourly' ? 'selected' : ''}}>hourly</option>
                                <option value="daily" {{config('seo.sitemap_changefreq_products') == 'daily' ? 'selected' : ''}}>daily</option>
                                <option value="weekly" {{config('seo.sitemap_changefreq_products') == 'weekly' ? 'selected' : ''}}>weekly</option>
                                <option value="monthly" {{config('seo.sitemap_changefreq_products') == 'monthly' ? 'selected' : ''}}>monthly</option>
                                <option value="yearly" {{config('seo.sitemap_changefreq_products') == 'yearly' ? 'selected' : ''}}>yearly</option>
                                <option value="never" {{config('seo.sitemap_changefreq_products') == 'never' ? 'selected' : ''}}>never</option>
                            </select>
                            @error('sitemap_changefreq_products')
                            <div class="error">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="sitemap-actions">
                    <button type="button" class="btn-secondary" onclick="generateSitemap()">
                        🔄 Generate Sitemap Now
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

            <!-- Performance Section -->
            <div class="settings-section">
                <h3>Performance Optimization</h3>
                
                <div @class(['form-element', 'is-error'=> $errors->has('performance_enabled')])>
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="performance_enabled" 
                            value="1" 
                            {{config('seo.performance_enabled') ? 'checked' : ''}}
                        >
                        <span>Enable Performance Optimization</span>
                    </label>
                    <small>Improve page load speed for better SEO rankings</small>
                    @error('performance_enabled')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div>

                <div class="performance-options">
                    <div @class(['form-element', 'is-error'=> $errors->has('minify_html')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="minify_html" 
                                value="1" 
                                {{config('seo.minify_html') ? 'checked' : ''}}
                            >
                            <span>Minify HTML</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('minify_css')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="minify_css" 
                                value="1" 
                                {{config('seo.minify_css') ? 'checked' : ''}}
                            >
                            <span>Minify CSS</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('minify_js')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="minify_js" 
                                value="1" 
                                {{config('seo.minify_js') ? 'checked' : ''}}
                            >
                            <span>Minify JavaScript</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('enable_gzip')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="enable_gzip" 
                                value="1" 
                                {{config('seo.enable_gzip') ? 'checked' : ''}}
                            >
                            <span>Enable Gzip Compression</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('enable_browser_caching')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="enable_browser_caching" 
                                value="1" 
                                {{config('seo.enable_browser_caching') ? 'checked' : ''}}
                            >
                            <span>Enable Browser Caching</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('cache_duration')])>
                        <label for="cache_duration">Cache Duration (seconds)</label>
                        <input 
                            type="number" 
                            id="cache_duration" 
                            name="cache_duration" 
                            value="{{config('seo.cache_duration', '86400')}}"
                            min="1"
                        >
                        <small>Default: 86400 (24 hours)</small>
                        @error('cache_duration')
                        <div class="error">{{$message}}</div>
                        @enderror
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('lazy_loading_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="lazy_loading_enabled" 
                                value="1" 
                                {{config('seo.lazy_loading_enabled') ? 'checked' : ''}}
                            >
                            <span>Enable Lazy Loading for Images</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('image_optimization_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="image_optimization_enabled" 
                                value="1" 
                                {{config('seo.image_optimization_enabled') ? 'checked' : ''}}
                            >
                            <span>Enable Image Optimization</span>
                        </label>
                    </div>

                    <div @class(['form-element', 'is-error'=> $errors->has('webp_conversion_enabled')])>
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="webp_conversion_enabled" 
                                value="1" 
                                {{config('seo.webp_conversion_enabled') ? 'checked' : ''}}
                            >
                            <span>Enable WebP Conversion</span>
                        </label>
                    </div>
                </div>
            </div>

            @push('header')
                <button class="button is-primary" type="submit" form="seoSettingsForm">Save SEO Settings</button>
            @endpush
        </form>
    </div>

    <script>
        function generateSitemap() {
            if (confirm('Generate a new sitemap? This will overwrite the existing sitemap.xml file.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{route("admin.seo-settings.generate-sitemap")}}';
                
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

        .settings-section h4 {
            margin: 1rem 0 0.5rem 0;
            color: #555;
            font-size: 1rem;
            font-weight: 600;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .schema-options, .performance-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .sitemap-priorities, .sitemap-changefreq {
            margin-top: 1rem;
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

        .sitemap-status {
            margin-top: 0.5rem;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
            padding: 0.75rem;
            border-radius: 6px;
            border-left: 4px solid #28a745;
        }

        .status-success a {
            color: #155724;
            text-decoration: underline;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-weight: 500;
        }

        .checkbox-label input[type="checkbox"] {
            margin: 0;
        }
    </style>
</x-admin.layout>
