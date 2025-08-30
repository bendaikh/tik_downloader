<section class="hero">
    <div class="container">
        <h1>Download TikTok Video</h1>
        <p class="hero-subtitle">
            The ultimate tool for downloading TikTok videos without watermarks.
        </p>
        
        <div class="download-form">
            <form id="downloadForm" action="{{ route('fetch') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div style="position: relative; display: flex; gap: 0.5rem;">
                        <input 
                            type="url" 
                            name="url" 
                            class="url-input" 
                            placeholder="Just insert TikTok URL"
                            required
                            autocomplete="off"
                            style="flex: 1; padding: 1rem 1.5rem; border: 2px solid var(--border-color); border-radius: 12px; background: var(--card-bg); color: var(--text-primary); font-size: 1rem;"
                        >
                        <button type="button" class="paste-btn" style="background: transparent; border: 2px solid var(--border-color); color: var(--text-secondary); padding: 1rem 1.5rem; border-radius: 12px; cursor: pointer; transition: all 0.3s ease; white-space: nowrap; display: flex; align-items: center; gap: 0.5rem;">
                            📋 Paste
                        </button>
                        <button type="submit" class="download-btn" id="downloadBtn" style="background: var(--gradient-accent); color: white; border: none; padding: 1rem 2rem; border-radius: 12px; font-weight: 600; cursor: pointer; transition: transform 0.3s ease; white-space: nowrap;">
                            Download
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Download Results -->
        <div id="downloadResults" style="display: none; margin-top: 2rem;">
            <div class="download-card" style="background: var(--card-bg); border-radius: 16px; padding: 2rem; border: 1px solid var(--border-color); max-width: 600px; margin: 0 auto;">
                <div id="videoInfo" style="margin-bottom: 1.5rem;"></div>
                <div id="downloadLinks"></div>
            </div>
        </div>
        
        <!-- Error Message -->
        <div id="errorMessage" style="display: none; margin-top: 1rem;">
            <div style="background: var(--error-color); color: white; padding: 1rem; border-radius: 8px; text-align: center; max-width: 600px; margin: 0 auto;">
                <span id="errorText"></span>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('downloadForm');
    const downloadBtn = document.getElementById('downloadBtn');
    const downloadResults = document.getElementById('downloadResults');
    const errorMessage = document.getElementById('errorMessage');
    const videoInfo = document.getElementById('videoInfo');
    const downloadLinks = document.getElementById('downloadLinks');
    const errorText = document.getElementById('errorText');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset previous results
        downloadResults.style.display = 'none';
        errorMessage.style.display = 'none';
        
        // Show loading state
        downloadBtn.disabled = true;
        downloadBtn.textContent = 'Processing...';
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': window.csrf_token,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.video) {
                showVideoResults(data.video);
            } else {
                showError(data.error || 'Failed to fetch video. Please check the URL and try again.');
            }
        } catch (error) {
            showError('Network error. Please try again.');
            console.error('Download error:', error);
        } finally {
            // Reset button state
            downloadBtn.disabled = false;
            downloadBtn.textContent = 'Download';
        }
    });
    
    function showVideoResults(video) {
        // Video info
        videoInfo.innerHTML = `
            <div style="display: flex; gap: 1rem; align-items: center;">
                <img src="${video.cover?.url || ''}" alt="Video thumbnail" style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover;" onerror="this.style.display='none'">
                <div>
                    <h3 style="font-size: 1.125rem; margin-bottom: 0.5rem;">${video.caption || 'TikTok Video'}</h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">@${video.author?.username || 'Unknown'}</p>
                </div>
            </div>
        `;
        
        // Download links
        let linksHtml = '<div style="display: flex; flex-direction: column; gap: 0.75rem;">';
        
        // HD Video without watermark (primary download)
        if (video.downloads && video.downloads.length > 0) {
            const hdDownload = video.downloads[0]; // First download is usually HD
            const size = hdDownload.size ? ` (${hdDownload.size})` : '';
            const downloadUrl = '/download?url=' + encodeURIComponent(hdDownload.url) + '&filename=tiktok-video-hd&type=video';
            
            linksHtml += `
                <a href="${downloadUrl}" 
                   style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: var(--gradient-accent); color: white; text-decoration: none; border-radius: 8px; font-weight: 500; transition: transform 0.3s ease; cursor: pointer;"
                   onmouseover="this.style.transform='scale(1.02)'"
                   onmouseout="this.style.transform='scale(1)'"
                   onclick="trackDownload('hd_video', 'HD Video Without Watermark')">
                    <span>📹 Download HD Video Without Watermark${size}</span>
                    <span>⬇️</span>
                </a>
            `;
        }
        
        // MP3 Audio download
        if (video.music && video.music.downloadUrl) {
            const audioDownloadUrl = '/download?url=' + encodeURIComponent(video.music.downloadUrl) + '&filename=tiktok-audio&type=audio';
            
            linksHtml += `
                <a href="${audioDownloadUrl}" 
                   style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: var(--accent-blue); color: white; text-decoration: none; border-radius: 8px; font-weight: 500; transition: transform 0.3s ease; cursor: pointer;"
                   onmouseover="this.style.transform='scale(1.02)'"
                   onmouseout="this.style.transform='scale(1)'"
                   onclick="trackDownload('mp3_audio', 'MP3 Audio')">
                    <span>🎵 Download MP3 Audio</span>
                    <span>⬇️</span>
                </a>
            `;
        } else if (video.downloads && video.downloads.length > 0) {
            // Fallback: Extract audio from video
            const videoUrl = video.downloads[0].url;
            const extractAudioUrl = '/extract-audio-download?video_url=' + btoa(videoUrl) + '&filename=tiktok-audio';
            
            linksHtml += `
                <a href="${extractAudioUrl}" 
                   style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: var(--accent-blue); color: white; text-decoration: none; border-radius: 8px; font-weight: 500; transition: transform 0.3s ease; cursor: pointer;"
                   onmouseover="this.style.transform='scale(1.02)'"
                   onmouseout="this.style.transform='scale(1)'"
                   onclick="trackDownload('extracted_audio', 'Extracted MP3 Audio')">
                    <span>🎵 Extract MP3 Audio from Video</span>
                    <span>⬇️</span>
                </a>
            `;
        }
        
        // Add watermark download if available
        if (video.watermark?.url) {
            const watermarkDownloadUrl = '/download?url=' + encodeURIComponent(video.watermark.url) + '&filename=tiktok-video-watermark&type=watermark';
            
            linksHtml += `
                <a href="${watermarkDownloadUrl}" 
                   style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: var(--border-color); color: var(--text-primary); text-decoration: none; border-radius: 8px; font-weight: 500; transition: transform 0.3s ease; cursor: pointer;"
                   onmouseover="this.style.transform='scale(1.02)'"
                   onmouseout="this.style.transform='scale(1)'"
                   onclick="trackDownload('watermark_video', 'Video with Watermark')">
                    <span>💧 Download with Watermark</span>
                    <span>⬇️</span>
                </a>
            `;
        }
        
        linksHtml += '</div>';
        downloadLinks.innerHTML = linksHtml;
        
        downloadResults.style.display = 'block';
    }
    
    function showError(message) {
        errorText.textContent = message;
        errorMessage.style.display = 'block';
    }
    
    // Track download events
    function trackDownload(type, label) {
        if (typeof gaTrackDownload === 'function') {
            gaTrackDownload(window.location.href, label);
        }
        
        // Also track as custom event
        if (typeof gaTrackEvent === 'function') {
            gaTrackEvent('download', {
                'event_category': 'video',
                'event_label': label,
                'download_type': type,
                'value': 1
            });
        }
    }
});
</script>
