<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: 'ProfileLayout',

    components: {
        Link
    },

    data() {
        return {
            isMobileMenuOpen: false,
            isUserMenuOpen: false
        }
    },
    methods: {
        toggleMobileMenu() {
            this.isMobileMenuOpen = !this.isMobileMenuOpen
            if (this.isMobileMenuOpen) {
                document.body.style.overflow = 'hidden'
            } else {
                document.body.style.overflow = ''
            }
        },
        closeMobileMenu() {
            this.isMobileMenuOpen = false
            document.body.style.overflow = ''
        },
        toggleUserMenu() {
            this.isUserMenuOpen = !this.isUserMenuOpen
        },
        closeUserMenu() {
            this.isUserMenuOpen = false
        }
    },
    mounted() {
        // Закрываем меню при клике вне его
        document.addEventListener('click', (e) => {
            if (this.isUserMenuOpen && !e.target.closest('.relative')) {
                this.closeUserMenu()
            }
        })
    }
}
</script>

<template>


    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Верхнее меню -->
        <header class="bg-white shadow-sm fixed top-0 left-0 right-0 z-30">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Левая часть: логотип и бургер-меню для мобильных -->
                    <div class="flex items-center">
                        <!-- Кнопка бургер-меню для мобильных -->
                        <button
                            @click="toggleMobileMenu"
                            class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path v-if="!isMobileMenuOpen"  stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else  stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Логотип -->
                        <div class="ml-4 lg:ml-0 flex-shrink-0">
                            <span class="text-xl font-bold text-gray-800">Profile</span>
                        </div>
                    </div>

                    <!-- Правая часть: иконки и аватар -->
                    <div class="flex items-center space-x-4">
                        <!-- Уведомления -->
                        <button class="p-2 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path  stroke-linecap="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>

                        <!-- Сообщения -->
                        <button class="p-2 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path  stroke-linecap="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </button>

                        <!-- Аватар пользователя -->
                        <div class="relative">
                            <button @click="toggleUserMenu" class="flex items-center space-x-2 focus:outline-none">
<!--                                <img-->
<!--                                    src="https://via.placeholder.com/32"-->
<!--                                    alt="User avatar"-->
<!--                                    class="h-8 w-8 rounded-full border-2 border-gray-200"-->
<!--                                >-->
                                <span class="hidden md:block text-sm font-medium text-gray-700">{{$page.props.auth.user.name}}</span>
                                <svg class="hidden md:block h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path  stroke-linecap="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Выпадающее меню пользователя -->
                            <div v-if="isUserMenuOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Профиль</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Настройки</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Нарушения</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Помощь</a>
                                <hr class="my-1">
                                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Выйти</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Основной контейнер с боковым меню и контентом -->
        <div class="flex flex-1 pt-16">
            <!-- Боковое меню для десктопа -->
            <aside class="hidden lg:block w-64 bg-white shadow-sm fixed left-0 top-16 bottom-0 overflow-y-auto">
                <nav class="p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path  stroke-linecap="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span>Главная</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors bg-gray-100">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path  stroke-linecap="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Профиль</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path  stroke-linecap="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Проекты</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path  stroke-linecap="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>События</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path  stroke-linecap="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path  stroke-linecap="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Настройки</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Мобильное боковое меню (оверлей) -->
            <transition
                enter-active-class="transition-opacity duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="isMobileMenuOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" @click="closeMobileMenu"></div>
            </transition>

            <transition
                enter-active-class="transition-transform duration-300 ease-out"
                enter-from-class="-translate-x-full"
                enter-to-class="translate-x-0"
                leave-active-class="transition-transform duration-300 ease-in"
                leave-from-class="translate-x-0"
                leave-to-class="-translate-x-full"
            >
                <aside v-if="isMobileMenuOpen" class="fixed left-0 top-16 bottom-0 w-64 bg-white shadow-lg z-50 overflow-y-auto lg:hidden">
                    <nav class="p-4">
                        <ul class="space-y-2">
                            <!-- Те же пункты меню, что и для десктопа -->
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path  stroke-linecap="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span>Главная</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors bg-gray-100">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path  stroke-linecap="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Профиль</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path  stroke-linecap="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Проекты</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path  stroke-linecap="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>События</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path  stroke-linecap="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path  stroke-linecap="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Настройки</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </aside>
            </transition>

            <!-- Основной контент (слот) -->
            <main class="flex-1 lg:ml-64 p-4 sm:p-6 lg:p-8 min-h-[calc(100vh-4rem)]">
                <div class="max-w-7xl mx-auto">
                    <slot></slot>
                </div>
            </main>
        </div>

        <!-- Футер -->
        <footer class="bg-white border-t border-gray-200 lg:ml-64">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <!-- Копирайт -->
                    <div class="text-sm text-gray-500">
                        © 2024 ProfileApp. Все права защищены.
                    </div>

                    <!-- Ссылки футера -->
                    <div class="flex space-x-6">
                        <a href="#" class="text-sm text-gray-500 hover:text-gray-700">О нас</a>
                        <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Контакты</a>
                        <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Конфиденциальность</a>
                        <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Условия использования</a>
                    </div>

                    <!-- Социальные сети -->
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.104c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.092-12.3c0-.21-.005-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

</template>

<style scoped>

</style>
