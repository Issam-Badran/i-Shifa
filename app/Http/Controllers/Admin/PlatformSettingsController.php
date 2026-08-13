<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class PlatformSettingsController extends Controller
{
    public function show()
    {
        $settings = PlatformSetting::first();

        return response()->json([
            'status' => 'success',
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $settings = PlatformSetting::first();

        $settings->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Settings updated successfully',
            'settings' => $settings
        ]);
    }
}
