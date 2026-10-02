<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: 'History',

    props: {
        user: Object,
        violations: Object,
    },

    layout: AdminLayout,
}
</script>

<template>
    <!-- Основной блок с историей нарушений (на мобильных - под сайдбаром) -->
    <main class="w-full lg:w-5/6 flex flex-col">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
            <!-- Заголовок -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">История нарушений пользователя</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm whitespace-nowrap">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            Вынести нарушение
                        </button>
                        <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2h2m3-4H9a2 2 0 00-2 2v7a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1m-1 4l-3 3m0 0l-3-3m3 3V3"/>
                            </svg>
                            Экспорт
                        </button>
                    </div>
                </div>
            </div>

            <!-- История нарушений - растягивается на доступное пространство -->
            <div class="flex-1 overflow-auto">
                <div class="p-4 sm:p-6">
                    <!-- Информация о пользователе -->
                    <div class="mb-8">
                        <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center gap-6">
                                <div class="flex-shrink-0">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center text-white text-2xl font-bold">
                                        АН
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">Анна Николаева</h3>
                                            <p class="text-sm text-gray-600 mt-1">ID: #USER-8921 • Зарегистрирован: 05.04.2023</p>
                                        </div>
                                        <div class="mt-2 md:mt-0">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                    </svg>
                                                    3 нарушения
                                                </span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Email</p>
                                            <p class="text-sm font-medium text-gray-900">anna.n@example.com</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Роль</p>
                                            <p class="text-sm font-medium text-gray-900">Пользователь</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Текущий статус</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Активен
                                                </span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Рейтинг</p>
                                            <p class="text-sm font-medium text-gray-900">124 ★ (4.8)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Статистика нарушений -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Общая статистика</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-orange-100 rounded-lg">
                                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-500">Всего нарушений</p>
                                        <p class="text-2xl font-semibold text-gray-900">3</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-yellow-100 rounded-lg">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-500">Предупреждения</p>
                                        <p class="text-2xl font-semibold text-gray-900">2</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-red-100 rounded-lg">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-500">Блокировки</p>
                                        <p class="text-2xl font-semibold text-gray-900">1</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-500">Последнее</p>
                                        <p class="text-sm font-semibold text-gray-900">15.03.2024</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Фильтры -->
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex flex-wrap gap-2">
                                <button class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm hover:bg-orange-700 transition-colors">
                                    Все нарушения
                                </button>
                                <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                                    Предупреждения
                                </button>
                                <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                                    Выговоры
                                </button>
                                <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                                    Блокировки
                                </button>
                            </div>
                            <div class="relative">
                                <input
                                    type="text"
                                    placeholder="Поиск по нарушениям..."
                                    class="w-full sm:w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                >
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Список нарушений -->
                    <div class="space-y-4">
                        <!-- Нарушение 1 (Критическое) -->
                        <div class="bg-white border-l-4 border-red-500 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mb-2">
                                                <h4 class="text-base font-semibold text-gray-900">Постоянная блокировка</h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Критическое
                                                    </span>
                                                <span class="text-sm text-gray-500">
                                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        15 марта 2024
                                                    </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">
                                                Причина: Систематическое нарушение правил после 3 предупреждений
                                            </p>
                                            <div class="flex flex-wrap gap-2 text-xs">
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                        Модератор: Иван Иванов
                                                    </span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                        ID: #MOD-001
                                                    </span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                        Срок: Бессрочно
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 lg:flex-col lg:items-end">
                                    <span class="text-xs text-gray-400 whitespace-nowrap">ID: #VIO-003</span>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3 pl-12">
                                <details class="text-sm">
                                    <summary class="text-orange-600 cursor-pointer hover:text-orange-700">
                                        Показать детали
                                    </summary>
                                    <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-600">
                                            <span class="font-medium">Комментарий модератора:</span> Пользователь неоднократно нарушал правила сообщества, игнорировал предупреждения. Принято решение о постоянной блокировке аккаунта.
                                        </p>
                                        <p class="text-gray-500 text-xs mt-2">
                                            Ссылка на нарушение: https://site.com/post/456
                                        </p>
                                    </div>
                                </details>
                            </div>
                        </div>

                        <!-- Нарушение 2 (Тяжёлое) -->
                        <div class="bg-white border-l-4 border-orange-500 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mb-2">
                                                <h4 class="text-base font-semibold text-gray-900">Временная блокировка</h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        Тяжёлое
                                                    </span>
                                                <span class="text-sm text-gray-500">
                                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        28 февраля 2024
                                                    </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">
                                                Причина: Рассылка спам-сообщений в комментариях
                                            </p>
                                            <div class="flex flex-wrap gap-2 text-xs">
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                        Модератор: Мария Сидорова
                                                    </span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                        ID: #MOD-002
                                                    </span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                        Срок: 7 дней
                                                    </span>
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">
                                                        Снята: 06.03.2024
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 lg:flex-col lg:items-end">
                                    <span class="text-xs text-gray-400 whitespace-nowrap">ID: #VIO-002</span>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Нарушение 3 (Среднее) -->
                        <div class="bg-white border-l-4 border-yellow-500 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mb-2">
                                                <h4 class="text-base font-semibold text-gray-900">Выговор</h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Среднее
                                                    </span>
                                                <span class="text-sm text-gray-500">
                                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        15 января 2024
                                                    </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">
                                                Причина: Оскорбление других пользователей в обсуждении
                                            </p>
                                            <div class="flex flex-wrap gap-2 text-xs">
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                        Модератор: Алексей Петров
                                                    </span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                        ID: #MOD-003
                                                    </span>
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                                        Обжаловано
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 lg:flex-col lg:items-end">
                                    <span class="text-xs text-gray-400 whitespace-nowrap">ID: #VIO-001</span>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3 pl-12">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-medium text-gray-700">Статус обжалования:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            На рассмотрении
                                        </span>
                                    <span class="text-gray-500 text-xs">Подано: 20.01.2024</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Система оценок и рисков -->
                    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Карточка риска -->
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Уровень риска пользователя</h4>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Текущий уровень</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Высокий
                                    </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-red-600 h-2.5 rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                3 нарушения за последние 3 месяца
                            </p>
                        </div>

                        <!-- Следующие действия -->
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Рекомендуемые действия</h4>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <span class="text-gray-600">Усилить контроль за активностью</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-orange-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <span class="text-gray-600">Провести беседу с пользователем</span>
                                </li>
                            </ul>
                        </div>

                        <!-- История модерации -->
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Последние действия</h4>
                            <div class="space-y-2">
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">15.03.2024</span> - Блокировка (Иван Иванов)
                                </p>
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">28.02.2024</span> - Временная блокировка (Мария С.)
                                </p>
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">15.01.2024</span> - Выговор (Алексей П.)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Пагинация -->
                    <div class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6">
                        <div class="text-sm text-gray-700">
                            Показано <span class="font-medium">1</span> - <span class="font-medium">3</span> из <span class="font-medium">3</span> нарушений
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50" disabled>
                                Назад
                            </button>
                            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                                1
                            </button>
                            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50" disabled>
                                Вперед
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<style scoped>

</style>
