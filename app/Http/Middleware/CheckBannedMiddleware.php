<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedMiddleware
{

    /**
     * Роуты, которые доступны заблокированным пользователям
     */
    protected $except = [
        'logout',           // Выход из системы
        'banned.notice',    // Страница "Вы заблокированы"
        'appeal.create',    // Подача апелляции
        'appeal.store',     // Отправка апелляции
        'contact',          // Страница контактов
        'rules',            // Правила сайта
        'login',            // Страница входа
        'register',         // Страница регистрации

        // Страницы для забаненных
        'banned.notice',        // Страница "Вы заблокированы"

        // Апелляции (ДОСТУПНЫ ДАЖЕ ЗАБАНЕННЫМ)
        'client.violations.index', //страница нарушений
        'profile.violations.appeal',      // Форма апелляции
        'client.violations.appeal.store', // Отправка апелляции
        'client.appeal.appeal-evidence', //загрузка скринов
//        'profile.violations.show',         // Просмотр нарушения
//        'profile.notifications.index',     // Уведомления
//        'profile.notifications.show',      // Конкретное уведомление
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ ЛОГИРУЕМ
//        Log::info('🔴 CheckBannedMiddleware: Сработал!', [
//            'url' => $request->fullUrl(),
//            'user_id' => auth()->id(),
//            'is_banned' => auth()->user()?->is_banned,
//        ]);
        if ($request->routeIs($this->except)) {
            return $next($request);
        }

        if (auth()->check()) {

            $user = auth()->user();
//            dd($user->isBanned());
            if ($user->isBanned()) {

                $banReason = $user->ban_reason;

                return redirect()->route('client.banned.notice');
            }
        }

        return $next($request);


    }
}
