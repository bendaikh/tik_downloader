<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AIIntegrationController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware());
        $this->middleware($this->makeDemoRestrictionMiddleware());
    }

    /**
     * Display the AI integration settings.
     */
    public function index()
    {
        return view('admin.ai-integration');
    }

    /**
     * Update the AI integration settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'openai_api_key' => 'nullable|string|max:255',
            'openai_model' => 'nullable|string|max:100',
            'ai_enabled' => 'boolean',
            'auto_generate_descriptions' => 'boolean',
            'ai_temperature' => 'nullable|numeric|min:0|max:2',
        ]);

        /** @var \App\Service\StorableConfig $storable */
        $storable = app('config.storable');
        
        // Cast temperature to float to satisfy OpenAI API expectations
        $temperature = isset($validated['ai_temperature']) ? (float) $validated['ai_temperature'] : 0.7;

        // Save AI settings to config
        $storable->put('services.openai', [
            'api_key' => $validated['openai_api_key'] ?? null,
            'model' => $validated['openai_model'] ?? 'gpt-3.5-turbo',
            'temperature' => $temperature,
        ]);

        $storable->put('ai', [
            'enabled' => $validated['ai_enabled'] ?? false,
            'auto_generate_descriptions' => $validated['auto_generate_descriptions'] ?? false,
        ]);

        if (!$storable->save()) {
            return back()->withInput()->with('error', 'Failed to save AI integration settings.');
        }
        
        return redirect()->route('admin.ai-integration')
            ->with('success', 'AI integration settings updated successfully!');
    }
}
