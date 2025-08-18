@props(['href', 'text', 'active' => false])

@php
    $active = $active ?: request()->url() === $href || request()->routeIs($href);
@endphp

<a {{$attributes->class([
    'nav-sub-link',
    'is-active' => $active,
])}} href="{{$href}}">
    <span>{{$text}}</span>
</a>
