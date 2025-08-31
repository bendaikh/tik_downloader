<nav class="card nav">
    <small>Menu</small>
    
    <x-admin.nav-link
        href="{{route('admin.analytics')}}"
        icon="admin.icon.mini.bar-chart"
        text="Analytics"
    />
    <x-admin.nav-link
        href="{{route('admin.proxy')}}"
        icon="admin.icon.mini.bolt"
        text="Configure Proxies"
    />
    <x-admin.nav-link
        href="{{route('admin.appearance')}}"
        icon="admin.icon.mini.paint-brush"
        text="Appearance"
    />
    <x-admin.nav-link
        href="{{route('admin.products.index')}}"
        icon="admin.icon.mini.shopping-bag"
        text="Products"
    />
    <x-admin.nav-link
        href="{{route('admin.me')}}"
        icon="admin.icon.mini.user"
        text="My Account"
        :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
    />
    
    <x-admin.nav-link
        href="{{route('admin.update')}}"
        icon="admin.icon.mini.download"
        text="System Updates"
        :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
    />
    
    <x-admin.nav-dropdown 
        text="Blog Posts" 
        icon="admin.icon.mini.edit"
        :open="request()->routeIs('admin.blogs*')"
    >
        <x-admin.nav-sub-link
            href="{{route('admin.blogs.create')}}"
            text="Add Blog Post"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.blogs.index')}}"
            text="Manage Blog Posts"
        />
    </x-admin.nav-dropdown>
    
    <x-admin.nav-dropdown 
        text="Settings" 
        icon="admin.icon.mini.cog"
        :open="request()->routeIs('admin.settings*') || request()->routeIs('admin.ai-integration*') || request()->routeIs('admin.edge-integration*') || request()->routeIs('admin.payment-settings*') || request()->routeIs('admin.google-analytics*') || request()->routeIs('admin.google-search-console*') || request()->routeIs('admin.safari*') || request()->routeIs('admin.seo-settings*')"
    >
        <x-admin.nav-sub-link
            href="{{route('admin.settings')}}"
            text="General Settings"
            :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.payment-settings')}}"
            text="Payment Settings"
            :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.google-analytics')}}"
            text="Google Analytics"
            :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.google-search-console')}}"
            text="Google Search Console"
            :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.safari')}}"
            text="Safari Analytics"
            :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.ai-integration')}}"
            text="AI Integration"
            :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.edge-integration')}}"
            text="Microsoft Services"
            :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.seo-settings')}}"
            text="SEO Settings"
            :class="auth()->user()->is_demo ? 'demo-restricted' : ''"
        />
    </x-admin.nav-dropdown>

    <x-admin.user-dropdown />
</nav>

@if(auth()->user()->is_demo)
<style>
.demo-restricted {
    position: relative;
    opacity: 0.7;
}

.demo-restricted::after {
    content: "🔒 Demo Restricted";
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.75rem;
    color: #f59e0b;
    font-weight: 500;
}

.demo-restricted:hover::after {
    content: "🔒 Demo Restricted - Critical settings disabled";
}
</style>
@endif
