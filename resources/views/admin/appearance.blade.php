<x-admin.layout title="Appearance">
    <div class="content-card card appearance">
        <div class="heading">
            <h2>Appearance</h2>
            <p>Customize the look and feel of your website</p>
        </div>

        @foreach($themes as $theme)
            <div
                @class(['theme', 'is-active'=> $theme->isActivated()]) style="--bg-image: url({{route('admin.appearance.theme.screenshot', $theme->id)}})">
                <div class="screenshot"></div>
                <div class="theme-content">
                    <h3>{{$theme->name}} <small>v{{$theme->version}}</small></h3>
                    <p>{{$theme->description}}</p>
                    <span>{{$theme->author}}</span>
                    <div class="actions">
                        @if($theme->isActivated())
                            <form action="{{route('admin.appearance.theme.clear-cache', $theme->id)}}" method="POST">
                                @csrf
                                <button type="submit" class="button is-primary">Clear Cache</button>
                            </form>
                        @else
                            <form action="{{route('admin.appearance.theme.activate', $theme->id)}}" method="POST">
                                @csrf
                                <button type="submit" class="button is-primary">Activate</button>
                            </form>
                        @endif
                    </div>
                </div>
            </divw>
        @endforeach
    </div>
</x-admin.layout>
