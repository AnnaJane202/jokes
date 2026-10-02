<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: 'Show',

    layout: AdminLayout,

    props: {
        user: Object,
        postsCount: Number,
        likesCount: Number,
    },



    methods: {

    },

    components: {
        Link,
    }

}
</script>

<template>
    <!-- Основной блок с профилем (на мобильных - под сайдбаром) -->
    <main class="w-full lg:w-5/6 flex flex-col">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
            <!-- Заголовок профиля -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">Профиль пользователя</h2>
                    <div class="flex space-x-2">
<!--                        <button class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">-->
<!--                            Редактировать профиль-->
<!--                        </button>-->
                    </div>
                </div>
            </div>

            <!-- Профиль - растягивается на доступное пространство -->
            <div class="flex-1 overflow-auto">
                <div class="p-4 sm:p-6">
                    <!-- Основная информация -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Основная информация</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Аватар и основная информация -->
                            <div class="lg:col-span-1">
                                <div class="flex flex-col items-center">
                                    <div class="relative mb-4">
<!--                                        <div class="w-32 h-32 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold">-->
<!--                                            Аватар-->
<!--                                        </div>-->
<!--                                        <div class="absolute bottom-2 right-2 bg-green-500 w-4 h-4 rounded-full border-2 border-white"></div>-->
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-900">{{ user.name }}</h4>
                                    <p class="text-gray-600">{{ user.role[0] }}</p>
                                    <p class="text-sm text-gray-500 mt-2">Зарегистрирован: {{ user.created_at }}</p>
                                </div>
                            </div>

                            <!-- Детали профиля -->
                            <div class="lg:col-span-2">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                            <p class="text-gray-900">{{ user.email }}</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Статистика и активность -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Статистика и активность</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-500">Постов создано</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ postsCount}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg">
<!--                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>-->
<!--                                        </svg>-->

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-500">Лайков</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ likesCount }}</p>
                                    </div>
                                </div>
                            </div>

<!--                            <div class="bg-purple-50 rounded-lg p-4">-->
<!--                                <div class="flex items-center">-->
<!--                                    <div class="p-2 bg-purple-100 rounded-lg">-->
<!--                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>-->
<!--                                        </svg>-->
<!--                                    </div>-->
<!--                                    <div class="ml-4">-->
<!--                                        <p class="text-sm text-gray-500">Комментариев</p>-->
<!--                                        <p class="text-2xl font-semibold text-gray-900">856</p>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div class="bg-yellow-50 rounded-lg p-4">-->
<!--                                <div class="flex items-center">-->
<!--                                    <div class="p-2 bg-yellow-100 rounded-lg">-->
<!--                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>-->
<!--                                        </svg>-->
<!--                                    </div>-->
<!--                                    <div class="ml-4">-->
<!--                                        <p class="text-sm text-gray-500">В сети</p>-->
<!--                                        <p class="text-2xl font-semibold text-gray-900">48ч</p>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
                        </div>
                    </div>




                    <!-- Последняя активность -->
<!--                    <div class="mb-8">-->
<!--                        <h3 class="text-lg font-medium text-gray-900 mb-4">Последняя активность</h3>-->
<!--                        <div class="space-y-4">-->
<!--                            <div class="flex items-start">-->
<!--                                <div class="flex-shrink-0">-->
<!--                                    <div class="p-2 bg-green-100 rounded-lg">-->
<!--                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>-->
<!--                                        </svg>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="ml-4">-->
<!--                                    <p class="text-sm font-medium text-gray-900">Опубликован новый пост</p>-->
<!--                                    <p class="text-sm text-gray-500">"Новые возможности React 18"</p>-->
<!--                                    <p class="text-xs text-gray-400">2 часа назад</p>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="flex items-start">-->
<!--                                <div class="flex-shrink-0">-->
<!--                                    <div class="p-2 bg-blue-100 rounded-lg">-->
<!--                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>-->
<!--                                        </svg>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="ml-4">-->
<!--                                    <p class="text-sm font-medium text-gray-900">Обновлен профиль</p>-->
<!--                                    <p class="text-sm text-gray-500">Добавлены новые навыки</p>-->
<!--                                    <p class="text-xs text-gray-400">Вчера, 15:30</p>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="flex items-start">-->
<!--                                <div class="flex-shrink-0">-->
<!--                                    <div class="p-2 bg-purple-100 rounded-lg">-->
<!--                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>-->
<!--                                        </svg>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="ml-4">-->
<!--                                    <p class="text-sm font-medium text-gray-900">Оставлен комментарий</p>-->
<!--                                    <p class="text-sm text-gray-500">К посту "Оптимизация производительности"</p>-->
<!--                                    <p class="text-xs text-gray-400">2 дня назад</p>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

                    <!-- Социальные сети -->
<!--                    <div>-->
<!--                        <h3 class="text-lg font-medium text-gray-900 mb-4">Социальные сети</h3>-->
<!--                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">-->
<!--                            <a href="#" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">-->
<!--                                <div class="p-2 bg-blue-100 rounded-lg">-->
<!--                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">-->
<!--                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>-->
<!--                                    </svg>-->
<!--                                </div>-->
<!--                                <div class="ml-3">-->
<!--                                    <p class="text-sm font-medium text-gray-900">Facebook</p>-->
<!--                                    <p class="text-xs text-gray-500">@ivanivanov</p>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                            <a href="#" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">-->
<!--                                <div class="p-2 bg-blue-100 rounded-lg">-->
<!--                                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">-->
<!--                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.213c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>-->
<!--                                    </svg>-->
<!--                                </div>-->
<!--                                <div class="ml-3">-->
<!--                                    <p class="text-sm font-medium text-gray-900">Twitter</p>-->
<!--                                    <p class="text-xs text-gray-500">@ivan_dev</p>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                            <a href="#" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">-->
<!--                                <div class="p-2 bg-gray-900 rounded-lg">-->
<!--                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">-->
<!--                                        <path d="M12 0C5.372 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.628 0 12-5.372 12-12 0-6.628-5.372-12-12-12z"/>-->
<!--                                    </svg>-->
<!--                                </div>-->
<!--                                <div class="ml-3">-->
<!--                                    <p class="text-sm font-medium text-gray-900">GitHub</p>-->
<!--                                    <p class="text-xs text-gray-500">ivan-ivanov</p>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                            <a href="#" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">-->
<!--                                <div class="p-2 bg-red-100 rounded-lg">-->
<!--                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">-->
<!--                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>-->
<!--                                    </svg>-->
<!--                                </div>-->
<!--                                <div class="ml-3">-->
<!--                                    <p class="text-sm font-medium text-gray-900">YouTube</p>-->
<!--                                    <p class="text-xs text-gray-500">Ivan Dev</p>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex-shrink-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
<!--                    <div class="text-sm text-gray-500">-->
<!--                        Последнее обновление: сегодня, 10:30-->
<!--                    </div>-->
                    <div class="flex flex-col sm:flex-row gap-3">
<!--                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">-->
<!--                            Сменить пароль-->
<!--                        </button>-->
                        <button v-if="!user.is_banned" class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors text-sm whitespace-nowrap">
                            <Link :href="route('admin.ban.create')">Заблокировать</Link>
                        </button>
                        <button v-if="user.is_banned" class="px-4 py-2 border border-green-300 text-green-700 rounded-lg hover:bg-green-50 transition-colors text-sm whitespace-nowrap">
                            Разблокировать
                        </button>
                        <button  class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors text-sm whitespace-nowrap">
                            <Link :href="route('admin.violations.create', user.id)">Вынести нарушение</Link>
                        </button>
                        <button  class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors text-sm whitespace-nowrap">
                            <Link :href="route('admin.users.violations', user)">История нарушений</Link>
                        </button>
<!--                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">-->
<!--                            Сохранить изменения-->
<!--                        </button>-->
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<style scoped>

</style>
