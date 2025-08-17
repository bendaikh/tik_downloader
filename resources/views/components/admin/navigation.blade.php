<nav class="card nav">
    <small>Menu</small>
    
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
        :open="request()->routeIs('admin.settings*') || request()->routeIs('admin.ai-integration*') || request()->routeIs('admin.payment-settings*')"
    >
        <x-admin.nav-sub-link
            href="{{route('admin.settings')}}"
            text="General Settings"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.payment-settings')}}"
            text="Payment Settings"
        />
        <x-admin.nav-sub-link
            href="{{route('admin.ai-integration')}}"
            text="AI Integration"
        />
    </x-admin.nav-dropdown>

    <x-admin.user-dropdown />
</nav>
