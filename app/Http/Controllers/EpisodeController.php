<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    protected $episodeService;

    public function __construct(\App\Services\EpisodeService $episodeService)
    {
        $this->episodeService = $episodeService;
    }

    public function create(Anime $anime)
    {
        return view('Episode.create', compact('anime'));
    }

    public function store(Request $request, Anime $anime)
    {
        $validated = $request->validate([
            'episode_number' => 'required|integer',
            'title' => 'nullable|string|max:255',
            'video_url' => 'required|string',
        ]);

        $this->episodeService->storeEpisode($anime, $validated);

        return redirect()->route('anime.edit', $anime->id)->with('success', 'Епізод успішно додано!');
    }

    public function edit(Episode $episode)
    {
        return view('Episode.edit', compact('episode'));
    }

    public function update(Request $request, Episode $episode)
    {
        $validated = $request->validate([
            'episode_number' => 'required|integer',
            'title' => 'nullable|string|max:255',
            'video_url' => 'required|string',
        ]);

        $this->episodeService->updateEpisode($episode, $validated);

        return redirect()->route('anime.edit', $episode->anime_id)->with('success', 'Епізод успішно оновлено!');
    }

    public function uploadChunk(Request $request)
    {
        return $this->episodeService->uploadChunk($request);
    }

    public function destroy(Episode $episode)
    {
        $animeId = $episode->anime_id;
        $episode->delete();

        return redirect()->route('anime.edit', $animeId)->with('success', 'Епізод видалено!');
    }
}
