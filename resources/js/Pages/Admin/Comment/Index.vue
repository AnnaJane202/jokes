<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
export default {
    name: 'Index',
    layout: AdminLayout,

    components: { AdminLayout, Pagination, Link },
    props: {
        comments: Object,
        filters: Object,
    },
    data() {
        return {
            localFilters: {
                search: this.filters.search || '',
                post_id: this.filters.post_id || '',
                user_id: this.filters.user_id || '',
            },
        };
    },
    methods: {
        applyFilters: debounce(function () {
            router.get(route('admin.comments.index'), this.localFilters, { preserveState: true });
        }, 300),
        resetFilters() {
            this.localFilters = { search: '', post_id: '', user_id: '' };
            this.applyFilters();
        },
        destroy(id) {
            if (confirm('Удалить комментарий?')) {
                router.delete(route('admin.comments.destroy', id), { preserveScroll: true });
            }
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('ru-RU');
        },
    },
}
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Комментарии</h1>

        <!-- Фильтры: привязываем к localFilters -->
        <div class="flex gap-4 mb-6">
            <input
                v-model="localFilters.search"
                @input="applyFilters"
                placeholder="Поиск по содержанию..."
                class="border rounded p-2 flex-1"
            />
            <input
                v-model="localFilters.post_id"
                @input="applyFilters"
                placeholder="ID поста"
                class="border rounded p-2 w-32"
            />
            <input
                v-model="localFilters.user_id"
                @input="applyFilters"
                placeholder="ID пользователя"
                class="border rounded p-2 w-32"
            />
            <button @click="resetFilters" class="bg-gray-200 px-4 py-2 rounded">Сбросить</button>
        </div>

        <!-- Таблица -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Пользователь</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Пост</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Комментарий</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <tr v-for="comment in comments.data" :key="comment.id">
                    <td class="px-6 py-4 text-sm">#{{ comment.id }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <img :src="comment.user?.avatar" class="w-6 h-6 rounded-full" />
                            <span class="text-sm">{{ comment.user?.name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <Link :href="route('admin.posts.show', { post: comment.post_id })" class="text-blue-600 hover:underline">
                            Пост #{{ comment.post_id }}
                        </Link>
                    </td>
                    <td class="px-6 py-4 text-sm max-w-xs truncate">
                        {{ comment.content }}
                    </td>
                    <td class="px-6 py-4 text-sm">{{ formatDate(comment.created_at) }}</td>
                    <td class="px-6 py-4 text-sm flex gap-2">
                        <Link :href="route('admin.comments.edit', comment.id)" class="text-blue-600 hover:underline">
                            Редактировать
                        </Link>
                        <button @click="destroy(comment.id)" class="text-red-600 hover:underline">Удалить</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Пагинация: links и meta на верхнем уровне -->
        <div class="mt-4">
            <Pagination :links="comments.links" :meta="comments.meta" />
        </div>
    </div>
</template>

<style scoped>

</style>
