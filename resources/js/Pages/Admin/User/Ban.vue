<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: 'Ban',

    props: {
       user: Array,
    },



    data() {
        return {

        }
    },

    methods: {

    },
    components: {
        Link
    },

    layout: AdminLayout
}
</script>

<template>
    <!-- Основной блок с формой бана (на мобильных - под сайдбаром) -->
    <main class="w-full lg:w-5/6 flex flex-col">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
            <!-- Заголовок формы -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">Блокировка пользователя</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Назад к списку
                        </button>
                    </div>
                </div>
            </div>

            <!-- Форма бана - растягивается на доступное пространство -->
            <div class="flex-1 overflow-auto">
                <div class="max-w-4xl mx-auto p-4 sm:p-6">
                    <!-- Предупреждение -->
                    <div class="mb-8">
                        <div class="bg-red-50 border-l-4 border-red-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Внимание: Вы собираетесь заблокировать пользователя</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>Эта операция ограничит доступ пользователя к системе. Пожалуйста, убедитесь в правильности вашего решения.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о пользователе -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Информация о пользователе</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex flex-col md:flex-row md:items-center gap-6">
                                <div class="flex-shrink-0">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-r from-red-500 to-orange-600 flex items-center justify-center text-white text-2xl font-bold">
                                        ПП
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">Имя пользователя</label>
                                            <p class="text-lg font-semibold text-gray-900">Петр Петров</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">ID пользователя</label>
                                            <p class="text-lg font-mono text-gray-900">#USER-4872</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                            <p class="text-gray-900">petr.petrov@example.com</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">Роль</label>
                                            <p class="text-gray-900">Модератор</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">Дата регистрации</label>
                                            <p class="text-gray-900">10 февраля 2023</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">Статус</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Активен
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- История нарушений -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">История нарушений</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Дата
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Тип нарушения
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Описание
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Модератор
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        15.03.2024
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Предупреждение
                                                </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        Неприемлемые комментарии в обсуждении
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        Иван Иванов
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        28.02.2024
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    Временная блокировка
                                                </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        Рассылка спам-сообщений
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        Мария Сидорова
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        15.01.2024
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Предупреждение
                                                </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        Нарушение правил публикации
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        Иван Иванов
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Форма блокировки -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Параметры блокировки</h3>
                        <div class="space-y-6">
                            <!-- Причина блокировки -->
                            <div>
                                <label for="ban_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Причина блокировки *
                                </label>
                                <select
                                    id="ban_reason"
                                    name="ban_reason"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                    required
                                >
                                    <option value="">Выберите причину блокировки</option>
                                    <option value="spam">Рассылка спама</option>
                                    <option value="abuse">Оскорбления и агрессивное поведение</option>
                                    <option value="rules">Систематическое нарушение правил</option>
                                    <option value="fraud">Мошенничество</option>
                                    <option value="content">Неприемлемый контент</option>
                                    <option value="other">Другая причина</option>
                                </select>
                            </div>

                            <!-- Детальное описание -->
                            <div>
                                <label for="ban_details" class="block text-sm font-medium text-gray-700 mb-2">
                                    Детальное описание нарушения *
                                </label>
                                <textarea
                                    id="ban_details"
                                    name="ban_details"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-vertical"
                                    placeholder="Опишите подробно нарушение, которое совершил пользователь..."
                                    required
                                ></textarea>
                                <p class="mt-1 text-sm text-gray-500">Это описание будет видно только администраторам.</p>
                            </div>

                            <!-- Тип блокировки -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Тип блокировки *
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 p-4 focus:outline-none">
                                        <input type="radio" name="ban_type" value="temporary" class="sr-only">
                                        <div class="flex w-full items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="text-sm">
                                                    <p class="font-medium text-gray-900">Временная</p>
                                                    <p class="text-gray-500">На определенный срок</p>
                                                </div>
                                            </div>
                                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </label>

                                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 p-4 focus:outline-none">
                                        <input type="radio" name="ban_type" value="permanent" class="sr-only" checked>
                                        <div class="flex w-full items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="text-sm">
                                                    <p class="font-medium text-gray-900">Постоянная</p>
                                                    <p class="text-gray-500">Без срока действия</p>
                                                </div>
                                            </div>
                                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </label>

                                    <label class="relative flex cursor-pointer rounded-lg border border-gray-300 p-4 focus:outline-none">
                                        <input type="radio" name="ban_type" value="warning" class="sr-only">
                                        <div class="flex w-full items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="text-sm">
                                                    <p class="font-medium text-gray-900">Предупреждение</p>
                                                    <p class="text-gray-500">Только предупреждение</p>
                                                </div>
                                            </div>
                                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Срок блокировки (появляется только для временной блокировки) -->
                            <div id="ban_duration_container" class="hidden">
                                <label for="ban_duration" class="block text-sm font-medium text-gray-700 mb-2">
                                    Срок блокировки *
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <select
                                            id="ban_duration_unit"
                                            name="ban_duration_unit"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                        >
                                            <option value="days">Дни</option>
                                            <option value="weeks">Недели</option>
                                            <option value="months">Месяцы</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input
                                            type="number"
                                            id="ban_duration"
                                            name="ban_duration"
                                            min="1"
                                            max="365"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                            placeholder="Количество"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Дополнительные действия -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Дополнительные действия
                                </label>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input
                                            id="delete_content"
                                            name="delete_content"
                                            type="checkbox"
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                        >
                                        <label for="delete_content" class="ml-2 block text-sm text-gray-700">
                                            Удалить все публикации пользователя
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input
                                            id="notify_user"
                                            name="notify_user"
                                            type="checkbox"
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                            checked
                                        >
                                        <label for="notify_user" class="ml-2 block text-sm text-gray-700">
                                            Уведомить пользователя по email
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input
                                            id="restrict_ip"
                                            name="restrict_ip"
                                            type="checkbox"
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                        >
                                        <label for="restrict_ip" class="ml-2 block text-sm text-gray-700">
                                            Заблокировать по IP-адресу
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Комментарий для администраторов -->
                            <div>
                                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Комментарий для администраторов
                                </label>
                                <textarea
                                    id="admin_notes"
                                    name="admin_notes"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-vertical"
                                    placeholder="Дополнительные заметки для других администраторов..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Подтверждение -->
                    <div class="bg-yellow-50 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-medium text-yellow-800 mb-3">Подтверждение операции</h3>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.196 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Пожалуйста, подтвердите, что вы понимаете последствия этой операции. Блокировка пользователя:
                                </p>
                                <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside space-y-1">
                                    <li>Ограничит доступ пользователя к системе</li>
                                    <li>Может быть отменена только администратором</li>
                                    <li>Будет записана в историю действий</li>
                                    <li>Пользователь получит уведомление о блокировке</li>
                                </ul>
                                <div class="mt-4">
                                    <div class="flex items-center">
                                        <input
                                            id="confirmation"
                                            name="confirmation"
                                            type="checkbox"
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                            required
                                        >
                                        <label for="confirmation" class="ml-2 block text-sm font-medium text-yellow-800">
                                            Я подтверждаю, что понимаю последствия блокировки пользователя
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex-shrink-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="text-sm text-gray-500">
                        Текущий администратор: <span class="font-medium text-gray-700">Иван Иванов</span>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">
                            Отмена
                        </button>
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm whitespace-nowrap">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Заблокировать пользователя
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<style scoped>

</style>
