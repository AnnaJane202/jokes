<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: 'MainLayout',

    components: {
        Link
    },

    data() {
        return {
            userMenuOpen: false,
        };
    },
    methods: {
        toggleUserMenu() {
            this.userMenuOpen = !this.userMenuOpen;
        },
        closeUserMenu(event) {
            if (this.$refs.userMenu && !this.$refs.userMenu.contains(event.target)) {
                this.userMenuOpen = false;
            }
        },
    },
    mounted() {
        document.addEventListener('click', this.closeUserMenu);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.closeUserMenu);
    },
}
</script>

<template>


    <body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Flash-сообщения -->
    <div v-if="$page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ $page.props.flash.success }}
    </div>
    <div v-if="$page.props.flash.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        {{ $page.props.flash.error }}
    </div>
    <div v-if="$page.props.flash.warning" class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
        {{ $page.props.flash.warning }}
    </div>
    <div v-if="$page.props.flash.info" class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative">
        {{ $page.props.flash.info }}
    </div>
    <!-- Шапка -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">Анекдоты</h1>
            <nav>
                <ul class="flex space-x-6">
                    <li><Link :href="route('post.index')" class="text-gray-700 hover:text-blue-600 transition">Главная</Link></li>
                    <li v-if="$page.props.auth.user"><Link :href="route('client.posts.create')" class="text-gray-700 hover:text-blue-600 transition">Добавить анекдот</Link></li>

                    <li v-if="!$page.props.auth.user"><Link :href="route('login')" class="text-gray-700 hover:text-blue-600 transition">Войти</Link></li>
                    <li v-if="!$page.props.auth.user"><Link :href="route('register')" class="text-gray-700 hover:text-blue-600 transition">Регистрация</Link></li>

                    <!-- Меню пользователя -->
                    <li v-if="$page.props.auth.user" class="relative" ref="userMenu">
                        <button
                            @click.stop="toggleUserMenu"
                            class="flex items-center gap-2 text-gray-700 hover:text-blue-600 transition"
                        >
                            <img
                                :src="$page.props.auth.user.avatar"
                                class="w-8 h-8 rounded-full border"
                            />
                            <span class="text-pink-600 font-medium">{{ $page.props.auth.user.name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Выпадающее меню -->
                        <div
                            v-if="userMenuOpen"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border"
                        >
                            <Link
                                :href="route('client.profile.dashboard')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                👤 Личный кабинет
                            </Link>
                            <Link
                                :href="route('client.profile.edit')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                ✏️ Редактировать профиль
                            </Link>
                            <Link
                                :href="route('client.violations.index')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                📋 Мои нарушения
                            </Link>
                            <Link
                                :href="route('client.reports.index')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                📢 Мои жалобы
                            </Link>
<!--                            <Link-->
<!--                                :href="route('client.profile.notifications.index')"-->
<!--                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"-->
<!--                            >-->
<!--                                🔔 Уведомления-->
<!--                            </Link>-->
                            <hr class="my-1" />
                            <Link
                                v-if="$page.props.auth.user.is_admin"
                                :href="route('admin.posts.index')"
                                class="block px-4 py-2 text-sm text-blue-600 hover:bg-gray-100"
                            >
                                ⚙️ Админ панель
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                            >
                                🚪 Выйти
                            </Link>
                        </div>
                    </li>
<!--                    <li v-if="$page.props.auth.user?.is_admin" class="text-blue-600">-->
<!--                        <Link :href="route('admin.posts.index')">Админ панель</Link>-->
<!--                    </li>-->
                </ul>
            </nav>
        </div>
    </header>

    <!-- Основной контент -->
    <main class="container mx-auto px-4 py-8 flex-grow flex flex-col md:flex-row">
        <slot/>
    </main>

    <!-- Футер -->
    <footer class="bg-gray-800 text-white py-8 mt-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-6 md:mb-0">
                    <h3 class="text-xl font-bold mb-4">Мой Блог</h3>
                    <p class="text-gray-400">Лучшие статьи о технологиях, дизайне и маркетинге</p>
                </div>
                <div class="mb-6 md:mb-0">
                    <h4 class="text-lg font-semibold mb-4">Навигация</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Главная</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">О нас</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Контакты</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Социальные сети</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-vk text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-telegram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-youtube text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-github text-xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                <p>&copy; 2023 Мой Блог. Все права защищены.</p>
            </div>
        </div>
    </footer>
    </body>

</template>

<style scoped>

</style>
