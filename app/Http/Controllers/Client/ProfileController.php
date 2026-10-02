<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\PublicUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return inertia('Client/Profile/Edit', [
            'user' => PublicUserResource::make($user)->resolve(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'current_password' => 'nullable|string|min:8',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Если email реально изменился — сбрасываем подтверждение
        if ($user->email !== $validated['email']) {
            $data['email_verified_at'] = null;
        }

        if (!empty($validated['current_password']) && !empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Текущий пароль неверен']);
            }
            $data['password'] = Hash::make($validated['new_password']);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Профиль обновлён');
    }
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB
        ]);

        $user = Auth::user();

        // Удаляем старый аватар, если он есть
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);


        }

        // Сохраняем новый файл
        $file = $request->file('avatar');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('avatars', $fileName, 'public');

        // Обновляем запись в БД
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Аватар успешно обновлён');
    }

    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'violations' => $user->violations()->count(),
            'active_violations' => $user->violations()->where('status', 'active')->count(),
            'appeals' => $user->violations()->whereNotNull('appealed_at')->count(),
            'reports' => $user->reports()->count(),
//            'unread_notifications' => $user->unreadNotifications()->count(),
        ];

        return inertia('Client/Profile/Dashboard', [
            'user' => PublicUserResource::make($user)->resolve(),
            'stats' => $stats,
        ]);
    }
}
