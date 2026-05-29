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
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('formats')) {
            $query->whereIn('format', $request->formats);
        }
        if ($request->filled('statuses')) {
            $query->whereIn('status', $request->statuses);
        }
        if ($request->filled('countries')) {
            $query->whereIn('country', $request->countries);
        }
        if ($request->filled('studios')) {
            $query->whereIn('studio', $request->studios);
        }
        if ($request->filled('genres')) {
            $query->whereHas('genres', function($q) use ($request) {
                $q->whereIn('genres.id', $request->genres);
            });
        }
        if ($request->filled('year_min')) {
            $query->where('year', '>=', $request->year_min);
        }
        if ($request->filled('year_max')) {
            $query->where('year', '<=', $request->year_max);
        }

        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'popular':
                $query->withCount('ratings')->orderByDesc('ratings_count');
                break;
            case 'newest':
                $query->orderBy('year', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('year', 'asc')->orderBy('created_at', 'asc');
                break;
            case 'rating':
                if (!request()->routeIs('anime.top')) {
                    $query->withAvg('ratings', 'score')->orderByDesc('ratings_avg_score');
                }
                break;
            case 'default':
            default:
                if (!request()->routeIs('anime.top')) {
                    $query->orderBy('updated_at', 'desc');
                }
                break;
        }
        return $query;
    }

    private function getFilterData()
    {
        return [
            'filterGenres' => \App\Models\Genre::orderBy('name')->get(),
            'filterStudios' => Anime::whereNotNull('studio')->where('studio', '!=', '')
                ->select('studio', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('studio')->orderByDesc('total')->get(),
            'filterCountries' => Anime::whereNotNull('country')->where('country', '!=', '')->distinct()->pluck('country'),
            'filterFormats' => Anime::whereNotNull('format')->where('format', '!=', '')->where('format', '!=', 'Невідомо')->distinct()->pluck('format'),
            'filterStatuses' => Anime::whereNotNull('status')->where('status', '!=', '')->distinct()->pluck('status'),
            'minYear' => Anime::min('year') ?? 1900,
            'maxYear' => Anime::max('year') ?? date('Y')
        ];
    }

    public function index(Request $request)
    {
        $query = Anime::query();
        $query = $this->applyFilters($query, $request);
        $animes = $query->paginate(12)->appends($request->all());

        if ($request->ajax()) {
            return view('Anime.partials.grid', compact('animes'))->render();
        }

        $heroAnimes = Anime::with(['genres', 'ratings'])->orderBy('created_at', 'desc')->take(5)->get();
        return view('Anime.index', array_merge(compact('animes', 'heroAnimes'), $this->getFilterData()));
    }

    public function genres()
    {
        $genres = \App\Models\Genre::with(['animes' => function($query) {
            $query->orderBy('created_at', 'desc')->take(10)->with(['ratings']);
        }])->has('animes', '>=', 1)->orderBy('name')->get();

        return view('Anime.genres', compact('genres'));
    }

    public function genreShow($id, Request $request)
    {
        $genre = \App\Models\Genre::findOrFail($id);
        
        $topAnime = $genre->animes()->withAvg('ratings', 'score')->orderByDesc('ratings_avg_score')->take(3)->get();
        
        $query = $genre->animes();
        $query = $this->applyFilters($query, $request);
        $animes = $query->paginate(12)->appends($request->all());

        if ($request->ajax()) {
            return view('Anime.partials.grid', compact('animes'))->render();
        }

        return view('Anime.genre_show', array_merge(compact('genre', 'topAnime', 'animes'), $this->getFilterData()));
    }

    public function ongoing(Request $request)
    {
        $query = Anime::where('status', 'ongoing');
        $query = $this->applyFilters($query, $request);
        $animes = $query->paginate(12)->appends($request->all());

        if ($request->ajax()) {
            return view('Anime.partials.grid', compact('animes'))->render();
        }
        
        $heroAnimes = Anime::with(['genres', 'ratings'])->where('status', 'ongoing')->orderBy('created_at', 'desc')->take(5)->get();
        return view('Anime.index', array_merge(compact('animes', 'heroAnimes'), $this->getFilterData()))->with('pageTitle', 'Онгоїнги');
    }

    public function top(Request $request)
    {
        $query = Anime::withAvg('ratings', 'score');
        
        // If sorting isn't specifically overridden, sort by rating for the top page
        if (!$request->filled('sort') || $request->sort == 'default') {
            $query->orderByDesc('ratings_avg_score');
        }
        
        $query = $this->applyFilters($query, $request);
        
        // Limit to top 100 exactly
        $allTop100 = $query->take(100)->get();
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 12;
        $animes = new \Illuminate\Pagination\LengthAwarePaginator(
            $allTop100->forPage($page, $perPage),
            $allTop100->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return view('Anime.partials.grid', compact('animes'))->render();
        }
        
        $heroAnimes = Anime::with(['genres', 'ratings'])->withAvg('ratings', 'score')->orderByDesc('ratings_avg_score')->take(5)->get();
        return view('Anime.index', array_merge(compact('animes', 'heroAnimes'), $this->getFilterData()))->with('pageTitle', 'Топ рейтингу');
    }

    public function adminIndex(\Illuminate\Http\Request $request)
    {
        $query = Anime::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('genre')) {
            $query->whereHas('genres', function($q) use ($request) {
                $q->where('genres.id', $request->genre);
            });
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('format')) {
            $query->where('format', $request->format);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $animes = $query->orderBy('id', 'desc')->paginate(20)->appends($request->all());

        $genres = \App\Models\Genre::orderBy('name')->get();
        // Get unique formats and countries for dropdowns
        $formats = Anime::whereNotNull('format')->where('format', '!=', '')->distinct()->pluck('format');
        $countries = Anime::whereNotNull('country')->where('country', '!=', '')->distinct()->pluck('country');
        $years = Anime::whereNotNull('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('admin.anime.index', compact('animes', 'genres', 'formats', 'countries', 'years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = \App\Models\Genre::orderBy('name')->get();
        return view('Anime.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'original_title' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'year' => 'nullable|integer',
            'format' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'studio' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url|max:2048',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'total_episodes' => 'nullable|integer|min:1',
            'duration' => 'nullable|string|max:100',
            'broadcast_day' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:255',
            'season' => 'nullable|string|max:50',
        ]);

        $this->animeService->storeAnime($validated);

        return redirect()->route('anime.admin_index')->with('success', 'Аніме успішно додано!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $anime = Anime::with(['reviews.user', 'episodes', 'genres', 'ratings'])->findOrFail($id);

        $commentsQuery = \App\Models\Comment::with('user')->where('anime_id', $id);
        $sort = $request->query('sort', 'new');
        if ($sort == 'old') {
            $commentsQuery->orderBy('created_at', 'asc');
        } elseif ($sort == 'popular') {
            $commentsQuery->withSum('votes', 'vote')->orderBy('votes_sum_vote', 'desc')->orderBy('created_at', 'desc');
        } else {
            $commentsQuery->orderBy('created_at', 'desc');
        }
        $commentsList = $commentsQuery->get();

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

        // Знайти схожі аніме (за спільними жанрами)
        $genreIds = $anime->genres->pluck('id');
        $similarAnimes = Anime::whereHas('genres', function($q) use ($genreIds) {
            $q->whereIn('genres.id', $genreIds);
        })->where('id', '!=', $anime->id)
          ->withCount(['ratings as average_rating' => function($query) {
              $query->select(\DB::raw('coalesce(avg(score),0)'));
          }])
          ->take(10)
          ->get();

        $listStats = \App\Models\UserAnimeList::select('status', \DB::raw('count(*) as count'))
            ->where('anime_id', $anime->id)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('Anime.show', compact('anime', 'currentEpisode', 'similarAnimes', 'commentsList', 'listStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $anime = Anime::findOrFail($id);
        $genres = \App\Models\Genre::orderBy('name')->get();
        return view('Anime.edit', compact('anime', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $anime = Anime::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'original_title' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'year' => 'nullable|integer',
            'format' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'studio' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url|max:2048',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'total_episodes' => 'nullable|integer|min:1',
            'duration' => 'nullable|string|max:100',
            'broadcast_day' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:255',
            'season' => 'nullable|string|max:50',
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
