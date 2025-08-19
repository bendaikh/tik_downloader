@extends('TikTokDark::layout')

@section('title', 'Privacy Policy')
@section('description', 'Read our privacy policy to understand how we protect your data.')

@section('content')
<section class="hero">
    <div class="hero-container">
        <h1>Privacy Policy</h1>
        <p>Learn how we collect, use, and protect your personal information.</p>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="content-section">
            <h2>1. Information We Collect</h2>
            <p>We collect information you provide directly to us, such as when you use our TikTok downloader service. This may include video URLs and download preferences.</p>
            
            <h2>2. How We Use Your Information</h2>
            <p>We use the information we collect to provide, maintain, and improve our services, to process your downloads, and to communicate with you.</p>
            
            <h2>3. Information Sharing</h2>
            <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>
            
            <h2>4. Data Security</h2>
            <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
            
            <h2>5. Cookies and Tracking</h2>
            <p>We may use cookies and similar tracking technologies to enhance your experience and collect information about how you use our service.</p>
            
            <h2>6. Third-Party Services</h2>
            <p>Our service may contain links to third-party websites or services. We are not responsible for the privacy practices of these third parties.</p>
            
            <h2>7. Changes to This Policy</h2>
            <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page.</p>
            
            <h2>8. Contact Us</h2>
            <p>If you have any questions about this privacy policy, please contact us through our website.</p>
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
