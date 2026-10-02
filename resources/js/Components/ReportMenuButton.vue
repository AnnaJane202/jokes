<script>

import {Link} from "@inertiajs/vue3";

export default {
   name: 'ReportMenuButton',

    components: {
       Link,
    },

    props: {
        type: {
            type: String,
            required: true,
        },
        id: {
            type: [Number, String],
            required: true,
        },
    },
    data() {
        return {
            isOpen: false, // уникальное состояние для каждого экземпляра
        }
    },
    methods: {
        toggleMenu(event) {
            // Останавливаем всплытие, чтобы клик не закрыл меню сразу
            event.stopPropagation()
            this.isOpen = !this.isOpen
        },
        closeMenu() {
            this.isOpen = false
        },
        handleClickOutside(event) {
            // Закрываем меню, если клик был вне этого компонента
            if (this.$refs.menuContainer && !this.$refs.menuContainer.contains(event.target)) {
                this.closeMenu()
            }
        },
    },
    mounted() {
        // Подписываемся на клик по документу
        document.addEventListener('click', this.handleClickOutside)
    },
    beforeUnmount() {
        // Отписываемся при уничтожении
        document.removeEventListener('click', this.handleClickOutside)
    },


}
</script>

<template>
    <div class="relative" ref="menuContainer">
        <!-- Кнопка с тремя точками -->
        <button
            @click.stop="toggleMenu"
            class="text-gray-400 hover:text-gray-600 transition"
            aria-label="Действия"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
            </svg>
        </button>

        <!-- Выпадающее меню -->
        <div
            v-if="isOpen"
            class="absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg py-1 z-10 border"
        >
            <Link
                :href="route('client.reports.create', { type: type, id: id })"
                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition"
                @click="closeMenu"
            >
                ⚠️ Пожаловаться
            </Link>
            <!-- Можно добавить другие пункты меню -->
        </div>
    </div>
</template>

<style scoped>

</style>
