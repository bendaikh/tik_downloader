<?php

namespace App\Service;

class MicrosoftServicesService
{
    /**
     * Get Bing Webmaster Tools verification meta tag
     */
    public function getBingWebmasterMetaTag(): ?string
    {
        if (config('services.microsoft.bing_webmaster.enabled') && config('services.microsoft.bing_webmaster.verification_code')) {
            return '<meta name="msvalidate.01" content="' . htmlspecialchars(config('services.microsoft.bing_webmaster.verification_code'), ENT_QUOTES, 'UTF-8') . '" />';
        }
        
        return null;
    }

    /**
     * Get Microsoft Clarity tracking script
     */
    public function getClarityScript(): ?string
    {
        if (config('services.microsoft.clarity.enabled') && config('services.microsoft.clarity.project_id')) {
            return '<script type="text/javascript">
                (function(c,l,a,r,i,t,y){
                    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
                })(window, document, "clarity", "script", "' . config('services.microsoft.clarity.project_id') . '");
            </script>';
        }
        
        return null;
    }

    /**
     * Get Microsoft Advertising UET tag
     */
    public function getMicrosoftAdsScript(): ?string
    {
        if (config('services.microsoft.ads.enabled') && config('services.microsoft.ads.tracking_id')) {
            return '<script>
                (function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"' . config('services.microsoft.ads.tracking_id') . '"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");
            </script>';
        }
        
        return null;
    }

    /**
     * Get Azure Application Insights script
     */
    public function getApplicationInsightsScript(): ?string
    {
        if (config('services.microsoft.application_insights.enabled') && config('services.microsoft.application_insights.instrumentation_key')) {
            return '<script type="text/javascript">
                var appInsights=window.appInsights||function(a){
                    function b(a){c[a]=function(){var b=arguments;c.queue.push(function(){c[a].apply(c,b)})}}var c={config:a},d=document,e=window;setTimeout(function(){var b=d.createElement("script");b.src=a.url||"https://az416426.vo.msecnd.net/scripts/a/ai.0.js",d.getElementsByTagName("script")[0].parentNode.appendChild(b)});try{c.cookie=d.cookie}catch(h){}c.queue=[];for(var f=["Event","Exception","Metric","PageView","Trace","Dependency"];f.length;)b("track"+f.pop());if(b("setAuthenticatedUserContext"),b("clearAuthenticatedUserContext"),b("startTrackEvent"),b("stopTrackEvent"),b("startTrackPage"),b("stopTrackPage"),b("flush"),!a.disableExceptionTracking){f="onerror",b("_"+f);var g=e[f];e[f]=function(a,b,d,e,h){var i=g&&g(a,b,d,e,h);return!0!==i&&c["_"+f](a,b,d,e,h),i}}return c
                }({
                    instrumentationKey:"' . config('services.microsoft.application_insights.instrumentation_key') . '"
                });
                window.appInsights=appInsights,appInsights.queue&&0===appInsights.queue.length&&appInsights.trackPageView();
            </script>';
        }
        
        return null;
    }

    /**
     * Get all Microsoft services scripts and meta tags
     */
    public function getAllScripts(): string
    {
        $scripts = [];
        
        // Bing Webmaster Tools meta tag
        if ($bingMeta = $this->getBingWebmasterMetaTag()) {
            $scripts[] = $bingMeta;
        }
        
        // Microsoft Clarity script
        if ($clarityScript = $this->getClarityScript()) {
            $scripts[] = $clarityScript;
        }
        
        // Microsoft Advertising script
        if ($adsScript = $this->getMicrosoftAdsScript()) {
            $scripts[] = $adsScript;
        }
        
        // Azure Application Insights script
        if ($aiScript = $this->getApplicationInsightsScript()) {
            $scripts[] = $aiScript;
        }
        
        $output = implode("\n    ", $scripts);
        
        // Ensure proper HTML output
        return trim($output);
    }
}
