<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class EpisodeService
{
    public function storeEpisode(Anime $anime, array $data)
    {
        return $anime->episodes()->create($data);
    }

    public function updateEpisode(Episode $episode, array $data)
    {
        $episode->update($data);
        return $episode;
    }

    /**
     * Handle Resumable.js chunk upload
     */
    public function uploadChunk(Request $request)
    {
        $resumableIdentifier = $request->input('resumableIdentifier');
        $resumableFilename = $request->input('resumableFilename');
        $resumableChunkNumber = $request->input('resumableChunkNumber');
        $resumableTotalChunks = $request->input('resumableTotalChunks');

        $tempDir = storage_path('app/chunks/' . $resumableIdentifier);

        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0777, true);
        }

        $chunkFile = $tempDir . '/' . $resumableChunkNumber;

        // If it's a GET request, check if chunk exists
        if ($request->isMethod('GET')) {
            if (File::exists($chunkFile)) {
                return response()->json(['status' => 'ok'], 200);
            }
            return response()->json(['status' => 'not found'], 404);
        }

        // POST request: save the chunk
        $file = $request->file('file');
        if ($file) {
            $file->move($tempDir, $resumableChunkNumber);
        }

        // Check if all chunks are uploaded
        $uploadedChunks = count(File::files($tempDir));
        if ($uploadedChunks == $resumableTotalChunks) {
            // Combine chunks
            $finalFilename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $resumableFilename);
            $episodesDir = storage_path('app/public/episodes');
            
            if (!File::exists($episodesDir)) {
                File::makeDirectory($episodesDir, 0777, true);
            }
            
            $finalPath = $episodesDir . '/' . $finalFilename;
            
            $out = fopen($finalPath, 'wb');
            for ($i = 1; $i <= $resumableTotalChunks; $i++) {
                $chunkPath = $tempDir . '/' . $i;
                $in = fopen($chunkPath, 'rb');
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
                fclose($in);
                File::delete($chunkPath);
            }
            fclose($out);
            File::deleteDirectory($tempDir);

            return response()->json([
                'status' => 'done',
                'path' => '/stream/episodes/' . $finalFilename
            ], 200);
        }

        return response()->json(['status' => 'chunk saved'], 200);
    }
}
