<x-theme::layout title="Popular TikTok Videos">
    <section style="padding: 4rem 0;">
        <div class="container">
            <h1 class="section-title">Popular Videos</h1>
            <p class="section-subtitle">Most downloaded TikTok videos on our platform</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
                @forelse($videos as $video)
                    <div style="background: var(--card-bg); border-radius: 16px; overflow: hidden; border: 1px solid var(--border-color); transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        @if($video->getCoverUrl())
                            <img src="{{ $video->getCoverUrl() }}" alt="Video thumbnail" style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 200px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                📹
                            </div>
                        @endif
                        
                        <div style="padding: 1.5rem;">
                            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">
                                {{ Str::limit($video->caption ?: 'TikTok Video', 60) }}
                            </h3>
                            
                            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                                @{{ $video->username ?: 'Unknown' }} • {{ number_format($video->downloads) }} downloads
                            </p>
                            
                            @if($video->url)
                                <a href="{{ $video->url }}" target="_blank" style="display: inline-block; background: var(--gradient-accent); color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    View Original
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📱</div>
                        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">No videos yet</h3>
                        <p>Popular videos will appear here as users download them.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-theme::layout>
