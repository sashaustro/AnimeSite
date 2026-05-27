<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Anime;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $collection = Auth::user()->collections()->create([
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->is_public ?? true,
        ]);

        return redirect()->back()->with('success', 'Колекцію створено!');
    }

    public function update(Request $request, Collection $collection)
    {
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $collection->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->back()->with('success', 'Колекцію оновлено!');
    }

    public function destroy(Collection $collection)
    {
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        $collection->delete();

        return redirect()->back()->with('success', 'Колекцію видалено!');
    }

    public function toggleAnime(Request $request, Collection $collection, Anime $anime)
    {
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        $collection->animes()->toggle($anime->id);

        return response()->json(['success' => true]);
    }
}
