<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group')->map(function ($items) {
            return $items->keyBy('key')->map(function ($item) {
                return $item->value;
            });
        });

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $group => $values) {
            foreach ($values as $key => $value) {
                Setting::setValue($group, $key, $value);
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
