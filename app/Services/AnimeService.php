<?php

namespace App\Services;

use App\Models\Anime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AnimeService
{
    public function storeAnime(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('posters', 'public');
        }

        return Anime::create($data);
    }

    public function updateAnime(Anime $anime, array $data)
    {
        if (isset($data['image'])) {
            // Delete old image if exists
            if ($anime->image) {
                Storage::disk('public')->delete($anime->image);
            }
            $data['image'] = $data['image']->store('posters', 'public');
        }

        $anime->update($data);
        return $anime;
    }
}
