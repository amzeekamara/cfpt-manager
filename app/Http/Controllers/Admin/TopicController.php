<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::with('seminar')->orderBy('name')->get();
        $seminars = \App\Models\Seminar::orderBy('title')->get();
        return view('admin.topics.index', compact('topics', 'seminars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'seminar_id' => 'required|exists:seminars,id',
        ]);
        Topic::create($request->only('name', 'seminar_id') + ['is_active' => true]);
        return redirect()->route('admin.topics.index')->with('success', 'Thème ajouté.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->route('admin.topics.index')->with('success', 'Thème supprimé.');
    }
}
