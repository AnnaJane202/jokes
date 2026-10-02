<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Находим последнее активное нарушение пользователя
        $violation = $user->violations()
            ->where('status', 'active')
            ->latest()
            ->first();

        return inertia('Client/Ban/Index', [
            'banReason' => $user->ban_reason,
            'bannedUntil' => $user->banned_until?->format('d.m.Y H:i'),
            'violationId' => $violation?->id,
        ]);
    }
}
