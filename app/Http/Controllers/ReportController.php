<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:comment,player,info',
            'reference_id' => 'required|integer',
            'reason' => 'nullable|string',
            'description' => 'nullable|string|max:1000'
        ]);

        $message = $request->reason ?? 'Інше';
        if ($request->filled('description')) {
            $message .= " | Опис: " . $request->description;
        }

        Report::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'reference_id' => $request->reference_id,
            'message' => $message
        ]);

        return back()->with('success', 'Дякуємо! Вашу скаргу надіслано на розгляд модераторам.');
    }
}
