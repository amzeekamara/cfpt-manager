<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Theme;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = Theme::orderBy('name')->get();
        return view('admin.themes.index', compact('themes'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Theme::create($request->only('name') + ['is_active' => true]);
        return redirect()->route('admin.themes.index')->with('success', 'Thème ajouté.');
    }

    public function destroy(Theme $theme)
    {
        $theme->delete();
        return redirect()->route('admin.themes.index')->with('success', 'Thème supprimé.');
    }
}
