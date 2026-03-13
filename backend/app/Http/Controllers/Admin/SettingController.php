<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Get all settings grouped by group.
     */
    public function index(): JsonResponse
    {
        $settings = Setting::all();

        $grouped = $settings->groupBy('group')->map(function ($group) {
            return $group->map(function ($setting) {
                return [
                    'id' => $setting->id,
                    'key' => $setting->key,
                    'value' => $setting->type === 'encrypted_string' && $setting->value ? str_repeat('*', 8) : $setting->value,
                    'label' => $setting->label,
                    'type' => $setting->type,
                    'description' => $setting->description,
                ];
            });
        });

        return response()->json([
            'data' => $grouped
        ]);
    }

    /**
     * Update multiple settings.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|exists:settings,key',
            'settings.*.value' => 'present',
        ]);

        foreach ($validated['settings'] as $item) {
            Setting::where('key', $item['key'])->update([
                'value' => is_bool($item['value']) ? ($item['value'] ? '1' : '0') : $item['value']
            ]);
        }

        return response()->json([
            'message' => 'Settings updated successfully.'
        ]);
    }
}
