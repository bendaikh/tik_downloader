@props(['text', 'icon', 'open' => false])

<div class="nav-dropdown" x-data="{ open: {{ $open ? 'true' : 'false' }} }">
    <button @click="open = !open" class="nav-link nav-dropdown-toggle" :class="{ 'is-active': open }">
        <x-dynamic-component :component="$icon" />
        <span>{{ $text }}</span>
        <svg class="nav-dropdown-arrow" :class="{ 'rotate-180': open }" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6,9 12,15 18,9"></polyline>
        </svg>
    </button>
    
    <div x-show="open" x-collapse class="nav-dropdown-menu">
        {{ $slot }}
    </div>
</div>
