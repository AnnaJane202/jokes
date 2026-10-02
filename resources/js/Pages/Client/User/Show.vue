<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import ReportMenuButton from '@/Components/ReportMenuButton.vue'

export default {
    name: 'Show',

    layout: MainLayout,

    components: {
        Pagination,
        ReportMenuButton
    },

    props: {
        user: Object,
        posts: Object,
    },
}
</script>

<template>
    <div class="w-full max-w-4xl mx-auto py-6">
        <!-- Карточка пользователя -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 flex items-center gap-6">
            <img :src="user.avatar" alt="Аватар" class="w-24 h-24 rounded-full border-2 border-gray-200" />
            <div>
                <h1 class="text-2xl font-bold">{{ user.name }}</h1>
                <p class="text-gray-500">Зарегистрирован: {{ user.created_at }}</p>
                <p class="text-gray-500">Постов: {{ user.posts_count || 0 }}</p>
                <!-- Кнопка "Пожаловаться" -->
                <ReportMenuButton type="user" :id="user.id" class="mt-2" />
            </div>
        </div>

        <!-- Посты пользователя -->
        <h2 class="text-xl font-bold mb-4">Посты пользователя</h2>

        <div v-if="posts.data && posts.data.length" class="space-y-4">
            <div v-for="post in posts.data" :key="post.id" class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold">{{ post.title }}</h3>
                <p class="text-gray-600 mt-2">{{ post.content }}</p>
                <div class="mt-2 text-sm text-gray-400">
                    {{ post.created_at }}
                </div>
            </div>
        </div>
        <div v-else class="text-gray-500">У пользователя пока нет постов</div>

        <!-- Пагинация -->
        <Pagination v-if="posts.links" :links="posts.links" class="mt-4" />
    </div>
</template>

<style scoped>

</style>
