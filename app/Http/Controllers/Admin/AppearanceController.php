<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Theme\ThemeManager;
use Illuminate\Support\Facades\Artisan;

class AppearanceController extends Controller
{
    use AdminAccessMiddleware;

    protected ThemeManager $manager;

    public function __construct(ThemeManager $manager)
    {
        $this->manager = $manager;
        $this->middleware($this->makeIsAdminMiddleware())->except('index');
    }

    public function index()
    {
        $themes = $this->manager->getThemes();
        return view('admin.appearance', compact('themes'));
    }

    public function screenshot(string $id)
    {
        $theme = $this->manager->getTheme($id);
        if (!$theme) {
            abort(404);
        }

        $screenshotPath = $theme->getScreenshot();
        
        // Check if screenshot path is null or file doesn't exist
        if (!$screenshotPath || !file_exists($screenshotPath)) {
            // Return a default placeholder image
            $placeholderPath = public_path('assets/admin/placeholder-screenshot.png');
            
            // If placeholder doesn't exist, create a simple one
            if (!file_exists($placeholderPath)) {
                $this->createPlaceholderScreenshot($placeholderPath);
            }
            
            return response()->file($placeholderPath, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return response()->stream(function () use ($screenshotPath) {
            echo file_get_contents($screenshotPath);
        }, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    private function createPlaceholderScreenshot(string $path): void
    {
        // Create a realistic TTDown theme preview using GD
        $width = 400;
        $height = 300;
        
        $image = imagecreatetruecolor($width, $height);
        
        // Set colors matching the actual TTDown theme
        $bgColor = imagecolorallocate($image, 15, 23, 42); // Dark blue background
        $cardBg = imagecolorallocate($image, 30, 41, 59); // Slate card background
        $textColor = imagecolorallocate($image, 148, 163, 184); // Slate text
        $accentColor = imagecolorallocate($image, 99, 102, 241); // Indigo accent
        $borderColor = imagecolorallocate($image, 51, 65, 85); // Slate border
        $whiteColor = imagecolorallocate($image, 255, 255, 255); // White text
        $gradientColor = imagecolorallocate($image, 139, 92, 246); // Purple gradient
        
        // Create gradient background
        for ($i = 0; $i < $height; $i++) {
            $ratio = $i / $height;
            $r = 15 + (139 - 15) * $ratio;
            $g = 23 + (92 - 23) * $ratio;
            $b = 42 + (246 - 42) * $ratio;
            $lineColor = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $i, $width, $i, $lineColor);
        }
        
        // Draw header with navigation
        imagefilledrectangle($image, 0, 0, $width, 70, $cardBg);
        imageline($image, 0, 70, $width, 70, $borderColor);
        
        // Logo
        $logoText = "TTDown";
        $logoX = 25;
        $logoY = 25;
        imagestring($image, 4, $logoX, $logoY, $logoText, $whiteColor);
        
        // Navigation items
        $navItems = ["Popular Videos", "FAQ", "Blog", "English"];
        $navX = $width - 200;
        $navY = 30;
        foreach ($navItems as $item) {
            imagestring($image, 2, $navX, $navY, $item, $textColor);
            $navX += strlen($item) * 6 + 15;
        }
        
        // Main hero section
        $heroY = 90;
        $heroHeight = 160;
        
        // Hero background card
        imagefilledrectangle($image, 20, $heroY, $width - 20, $heroY + $heroHeight, $cardBg);
        imagerectangle($image, 20, $heroY, $width - 20, $heroY + $heroHeight, $borderColor);
        
        // Main title
        $titleText = "Download TikTok Video";
        $titleX = ($width - strlen($titleText) * imagefontwidth(4)) / 2;
        imagestring($image, 4, $titleX, $heroY + 20, $titleText, $whiteColor);
        
        // Subtitle
        $subtitleText = "The ultimate tool for downloading TikTok videos without watermarks";
        $subtitleX = ($width - strlen($subtitleText) * imagefontwidth(2)) / 2;
        imagestring($image, 2, $subtitleX, $heroY + 50, $subtitleText, $textColor);
        
        // URL input field with paste button
        $inputY = $heroY + 80;
        $inputWidth = $width - 80;
        $inputX = 40;
        
        // Input field
        imagefilledrectangle($image, $inputX, $inputY, $inputX + $inputWidth - 80, $inputY + 35, $bgColor);
        imagerectangle($image, $inputX, $inputY, $inputX + $inputWidth - 80, $inputY + 35, $borderColor);
        
        // Paste button
        $pasteX = $inputX + $inputWidth - 80;
        imagefilledrectangle($image, $pasteX, $inputY, $pasteX + 70, $inputY + 35, $cardBg);
        imagerectangle($image, $pasteX, $inputY, $pasteX + 70, $inputY + 35, $borderColor);
        imagestring($image, 2, $pasteX + 10, $inputY + 10, "Paste", $textColor);
        
        // Download button
        $buttonY = $inputY + 45;
        $buttonWidth = 140;
        $buttonX = ($width - $buttonWidth) / 2;
        
        // Gradient button effect
        for ($i = 0; $i < 35; $i++) {
            $ratio = $i / 35;
            $r = 99 + (139 - 99) * $ratio;
            $g = 102 + (92 - 102) * $ratio;
            $b = 241 + (246 - 241) * $ratio;
            $buttonLineColor = imagecolorallocate($image, $r, $g, $b);
            imageline($image, $buttonX, $buttonY + $i, $buttonX + $buttonWidth, $buttonY + $i, $buttonLineColor);
        }
        
        imagerectangle($image, $buttonX, $buttonY, $buttonX + $buttonWidth, $buttonY + 35, $accentColor);
        $buttonText = "Download";
        $buttonTextX = $buttonX + ($buttonWidth - strlen($buttonText) * imagefontwidth(3)) / 2;
        imagestring($image, 3, $buttonTextX, $buttonY + 10, $buttonText, $whiteColor);
        
        // Placeholder text in input
        $placeholderText = "Just insert TikTok URL";
        $placeholderX = $inputX + 15;
        imagestring($image, 2, $placeholderX, $inputY + 10, $placeholderText, $textColor);
        
        // Footer
        $footerY = $height - 25;
        $footerText = "Modern dark theme with gradient backgrounds";
        $footerX = ($width - strlen($footerText) * imagefontwidth(2)) / 2;
        imagestring($image, 2, $footerX, $footerY, $footerText, $textColor);
        
        // Save the image
        imagepng($image, $path);
        imagedestroy($image);
    }

    public function activate(string $id)
    {
        $theme = $this->manager->getTheme($id);
        if (!$theme) {
            abort(404);
        }

        Artisan::call('theme:activate', [
            '--theme' => $id
        ]);

        return redirect()->back()->with('message', [
            'type' => 'success',
            'content' => 'Theme activated successfully'
        ]);
    }

    public function clearCache(string $id)
    {
        $theme = $this->manager->getTheme($id);
        if (!$theme) {
            abort(404);
        }

        Artisan::call('theme:clear-cache');

        return redirect()->back()->with('message', [
            'type' => 'success',
            'content' => 'Theme cache cleared successfully'
        ]);
    }
}
