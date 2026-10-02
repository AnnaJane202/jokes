<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
export default {
    name: 'Show',
    layout: MainLayout,
    components: { MainLayout, Pagination, Link },
    props: {
        category: Object,
        posts: Object,
    },

}
</script>

<template>
    <div class="w-full max-w-7xl mx-auto py-6">
        <h1 class="text-2xl font-bold mb-6">
            Категория: {{ category.title }}
        </h1>

        <div v-if="posts.data && posts.data.length" class="space-y-6">
            <div v-for="post in posts.data" :key="post.id"
                 class="bg-white rounded-lg shadow overflow-hidden transition-transform hover:scale-[1.01]">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-800">
                            <Link :href="route('client.posts.show', post.id)" class="hover:text-blue-600">
                                {{ post.title }}
                            </Link>
                        </h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ post.category[0]?.title }}
                            </span>
                    </div>
                    <p class="text-gray-600 mb-4">{{ post.content }}</p>
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>{{ post.user.name }}</span>
                        <span>{{ post.updated_at }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="text-gray-500 text-center py-8">
            В этой категории пока нет постов
        </div>

        <!-- Пагинация -->
        <Pagination v-if="posts.links" :links="posts.links" :meta="posts.meta" class="mt-6" />
    </div>
</template>

<style scoped>

</style>
