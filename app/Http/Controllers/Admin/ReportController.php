<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'comment');
        $status = $request->query('status');
        $search = $request->query('search');
        
        $query = Report::with('user')->where('type', $tab);

        if ($status && in_array($status, ['pending', 'resolved'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('username', 'like', "%{$search}%");
                  });
            });
        }
        
        $reports = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->all());
            
        return view('admin.reports.index', compact('reports', 'tab'));
    }

    public function resolve($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'resolved';
        $report->save();

        return back()->with('success', 'Скаргу позначено як вирішену.');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return back()->with('success', 'Скаргу успішно видалено.');
    }

    public function mute(Request $request, User $user)
    {
        $request->validate([
            'mute_value' => 'required|integer|min:1',
            'mute_unit' => 'required|in:seconds,minutes,hours,days,weeks,months',
            'mute_reason' => 'required|string|max:255'
        ]);

        $muteValue = (int) $request->mute_value;
        $now = Carbon::now();
        switch($request->mute_unit) {
            case 'seconds': $muted_until = $now->addSeconds($muteValue); break;
            case 'minutes': $muted_until = $now->addMinutes($muteValue); break;
            case 'hours': $muted_until = $now->addHours($muteValue); break;
            case 'days': $muted_until = $now->addDays($muteValue); break;
            case 'weeks': $muted_until = $now->addWeeks($muteValue); break;
            case 'months': $muted_until = $now->addMonths($muteValue); break;
            default: $muted_until = $now->addHours($muteValue);
        }

        $unitStr = '';
        switch($request->mute_unit) {
            case 'seconds': $unitStr = 'сек.'; break;
            case 'minutes': $unitStr = 'хв.'; break;
            case 'hours': $unitStr = 'год.'; break;
            case 'days': $unitStr = 'дн.'; break;
            case 'weeks': $unitStr = 'тиж.'; break;
            case 'months': $unitStr = 'міс.'; break;
        }

        $user->update([
            'muted_until' => $muted_until,
            'mute_reason' => $request->mute_reason
        ]);

        return back()->with('success', "Користувачу {$user->username} видано мут на {$request->mute_value} {$unitStr}");
    }

    public function unmute(User $user)
    {
        $user->update([
            'muted_until' => null,
            'mute_reason' => null
        ]);

        return back()->with('success', "Мут знято з користувача {$user->username}.");
    }
}
