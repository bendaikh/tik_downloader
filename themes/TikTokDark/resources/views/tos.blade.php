@extends('TikTokDark::layout')

@section('title', 'Terms of Service')
@section('description', 'Read our terms of service for using the TikTok downloader.')

@section('content')
<section class="hero">
    <div class="hero-container">
        <h1>Terms of Service</h1>
        <p>Please read these terms carefully before using our service.</p>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="content-section">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using this TikTok downloader service, you accept and agree to be bound by the terms and provision of this agreement.</p>
            
            <h2>2. Use License</h2>
            <p>Permission is granted to temporarily download one copy of the materials (information or software) on our website for personal, non-commercial transitory viewing only.</p>
            
            <h2>3. Disclaimer</h2>
            <p>The materials on our website are provided on an 'as is' basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
            
            <h2>4. Limitations</h2>
            <p>In no event shall we or our suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on our website.</p>
            
            <h2>5. Accuracy of Materials</h2>
            <p>The materials appearing on our website could include technical, typographical, or photographic errors. We do not warrant that any of the materials on our website are accurate, complete or current.</p>
            
            <h2>6. Links</h2>
            <p>We have not reviewed all of the sites linked to our website and are not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by us of the site.</p>
            
            <h2>7. Modifications</h2>
            <p>We may revise these terms of service for our website at any time without notice. By using this website you are agreeing to be bound by the then current version of these Terms of Service.</p>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.content-section {
    max-width: 800px;
    margin: 0 auto;
}

.content-section h2 {
    color: var(--text-primary);
    margin-top: var(--spacing-xl);
    margin-bottom: var(--spacing-md);
    font-size: 1.5rem;
}

.content-section h2:first-child {
    margin-top: 0;
}

.content-section p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
}
</style>
@endpush
