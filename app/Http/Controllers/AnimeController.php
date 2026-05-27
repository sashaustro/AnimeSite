<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Http\Requests\AnimeRequest;
use App\Services\AnimeService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    protected $animeService;

    public function __construct(\App\Services\AnimeService $animeService)
    {
        $this->animeService = $animeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Anime::query();
        
        if (request()->has('search') && request('search') !== null) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }
        
        $animes = $query->orderBy('updated_at', 'desc')->paginate(12);
        return view('Anime.index', compact('animes'));
    }

    public function genres()
    {
        // Поки що виведемо всі аніме як заглушку, пізніше тут буде вибірка по таблиці genres
        $animes = Anime::orderBy('id', 'desc')->paginate(12);
        return view('Anime.genres', compact('animes'));
    }

    public function ongoing()
    {
        $animes = Anime::where('status', 'ongoing')->orderBy('id', 'desc')->paginate(12);
        return view('Anime.index', compact('animes'))->with('pageTitle', 'Онгоїнги');
    }

    public function top()
    {
        // Заглушка для ТОП рейтингу (сортування за ID поки немає оцінок)
        $animes = Anime::orderBy('id', 'asc')->paginate(12);
        return view('Anime.index', compact('animes'))->with('pageTitle', 'Топ рейтингу');
    }

    public function adminIndex()
    {
        $animes = Anime::orderBy('id', 'desc')->paginate(20);
        return view('admin.anime.index', compact('animes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Anime.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'nullable|integer',
            'format' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'studio' => 'nullable|string|max:100',
            'voice_acting' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->animeService->storeAnime($validated);

        return redirect()->route('anime.admin_index')->with('success', 'Аніме успішно додано!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $anime = Anime::with(['reviews.user', 'episodes'])->findOrFail($id);
        
        $currentEpId = request('ep');
        $currentEpisode = $currentEpId ? $anime->episodes->firstWhere('id', $currentEpId) : $anime->episodes->first();

        // Запис історії переглядів
        if (auth()->check() && $currentEpisode) {
            \App\Models\WatchHistory::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'anime_id' => $anime->id,
                    'episode_id' => $currentEpisode->id,
                ],
                [
                    'watched_at' => now(),
                ]
            );
        }

        return view('Anime.show', compact('anime'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $anime = Anime::findOrFail($id);
        return view('Anime.edit', compact('anime'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $anime = Anime::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'nullable|integer',
            'format' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'studio' => 'nullable|string|max:100',
            'voice_acting' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->animeService->updateAnime($anime, $validated);

        return redirect()->route('anime.admin_index')->with('success', 'Аніме успішно оновлено!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $anime = Anime::findOrFail($id);
        $anime->delete();

        return redirect()->route('anime.admin_index')->with('success', 'Аніме видалено!');
    }
}
