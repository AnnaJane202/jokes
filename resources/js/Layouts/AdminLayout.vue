<script xmlns="http://www.w3.org/1999/html">
import {Link} from "@inertiajs/vue3";

export default {
    name: 'AdminLayout',

    components: {
        Link
    },

    methods: {
        logout() {
            axios.post(route('logout'))
                .then (res => {

                })
        }
    }
}
</script>

<template>
    <body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Верхний блок с заголовком и меню -->
    <header class="bg-white shadow-sm flex-shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Заголовок -->
                <h1 class="text-xl font-semibold text-gray-900">Админ-панель сайта</h1>

                <!-- Основное меню -->
                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li><Link :href="route('post.index')" class="text-gray-600 hover:text-gray-900 transition-colors">На сайт</Link></li>
                        <li><Link :href="route('admin.users.index')" class="text-gray-600 hover:text-gray-900 transition-colors">Пользователи</Link></li>
                        <li><Link :href="route('admin.posts.index')" class="text-gray-600 hover:text-gray-900 transition-colors">Посты</Link></li>
                        <li><Link :href="route('admin.categories.index')" class="text-gray-600 hover:text-gray-900 transition-colors">Категории</Link></li>





                    </ul>
                </nav>

                <!-- Мобильное меню (бургер) -->
                <div class="md:hidden">
                    <button class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Основной контент - растягивается на всю доступную высоту -->
    <div class="flex-1 flex flex-col lg:flex-row max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
        <!-- Sidebar (на мобильных - сверху, на больших экранах - слева 1/6 ширины и растянут по высоте) -->
        <aside class="w-full lg:w-1/6 mb-6 lg:mb-0 lg:mr-6 flex flex-col">
            <div class="bg-indigo-950 rounded-lg shadow-sm p-4 flex-1">
                <nav class="h-full">
                    <ul class="space-y-2">
                        <li>
                            <Link :href="route('admin.posts.index')" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-200 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Главная
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('admin.users.index')" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-200 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                Пользователи
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('admin.posts.index')" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-200 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Посты
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('admin.categories.index')" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-200 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                </svg>
                                Категории
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('admin.violations.index')" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-200 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                </svg>
                                Нарушения
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('admin.reports.index')" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-200 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                </svg>
                                Жалобы
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('admin.comments.index')" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-200 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                </svg>
                                Комментарии
                            </Link>
                        </li>
<!--                        <li>-->
<!--                            <Link href="#" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-200 hover:text-blue-600 transition-colors">-->
<!--                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>-->
<!--                                </svg>-->
<!--                                Статистика-->
<!--                            </Link>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <Link href="#" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-200 hover:text-blue-600 transition-colors">-->
<!--                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>-->
<!--                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>-->
<!--                                </svg>-->
<!--                                Настройки-->
<!--                            </Link>-->
<!--                        </li>-->
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Основной блок с таблицей (на мобильных - под сайдбаром) -->
        <main class="w-full lg:w-5/6 flex flex-col">
            <slot/>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto flex-shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-sm text-gray-600 mb-4 md:mb-0">
                    © 2024 Админ-панель. Все права защищены.
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Политика конфиденциальности</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Условия использования</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Поддержка</a>
                </div>
            </div>
        </div>
    </footer>
    </body>
<!--    <section class="">-->
<!--        -->
<!--    </section>-->
</template>

<style scoped>

</style>
