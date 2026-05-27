<?php

namespace App\Services;

use App\Models\Anime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AnimeService
{
    public function storeAnime(array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('posters', 'public');
        } elseif (!empty($data['image_url'])) {
            try {
                $contents = file_get_contents($data['image_url']);
                $name = 'posters/' . uniqid() . '.jpg';
                Storage::disk('public')->put($name, $contents);
                $data['image'] = $name;
            } catch (\Exception $e) {
                // Ignore
            }
        }
        unset($data['image_url']);

        $genres = $data['genres'] ?? [];
        unset($data['genres']);

        $anime = Anime::create($data);

        if (!empty($genres)) {
            $anime->genres()->sync($genres);
        }

        return $anime;
    }

    public function updateAnime(Anime $anime, array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($anime->image && !str_starts_with($anime->image, 'http')) {
                Storage::disk('public')->delete($anime->image);
            }
            $data['image'] = $data['image']->store('posters', 'public');
        } elseif (!empty($data['image_url'])) {
            try {
                $contents = file_get_contents($data['image_url']);
                $name = 'posters/' . uniqid() . '.jpg';
                Storage::disk('public')->put($name, $contents);
                if ($anime->image && !str_starts_with($anime->image, 'http')) {
                    Storage::disk('public')->delete($anime->image);
                }
                $data['image'] = $name;
            } catch (\Exception $e) {
                // Ignore
            }
        }
        unset($data['image_url']);

        $genres = $data['genres'] ?? [];
        unset($data['genres']);

        $anime->update($data);

        $anime->genres()->sync($genres);

        return $anime;
    }
}
