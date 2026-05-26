<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Http\Requests\AnimeRequest;
use Illuminate\Support\Facades\Log;

class AnimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $animes = Anime::paginate(12); // Оптимальна кількість для сітки 3x4 або 4x3
        return view('Anime.index', compact('animes'));
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
    public function store(AnimeRequest $request)
    {
        // 1. Отримуємо вже перевірені дані з нашого AnimeRequest
        $data = $request->validated();

        // 2. Збереження картинки (якщо вона є)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posters', 'public');
            $data['image'] = $path;
        }

        // 3. Збереження в базу
        $newAnime = Anime::create($data);

        // Логуємо інформацію про додавання замість відправки email через SMTP
        Log::info("Нове аніме додано в каталог! Назва: {$newAnime->title}, Жанр: {$newAnime->genre}");

        return redirect()->route('anime.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $anime = Anime::with('reviews.user')->findOrFail($id);
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
    public function update(AnimeRequest $request, string $id)
    {
        $anime = Anime::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posters', 'public');
            $data['image'] = $path;
        }

        $anime->update($data);

        return redirect()->route('anime.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $anime = Anime::findOrFail($id);
        $anime->delete();

        return redirect()->route('anime.index');
    }

    public function tableView()
    {
        $animes = Anime::all();
        return view('Anime.table', compact('animes'));
    }
}
