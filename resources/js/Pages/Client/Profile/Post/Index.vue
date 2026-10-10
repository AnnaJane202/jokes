<script>
import UserProfileLayout from '@/Layouts/UserProfileLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { debounce } from 'lodash';

export default {
    name: 'MyPosts',
    layout: UserProfileLayout,
    components: { Link, Pagination, ConfirmDeleteModal },
    props: {
        posts: Object,
        filters: Object,
        stats: Object,
    },
    data() {
        return {
            localFilters: {
                status: this.filters.status || '',
            },
            deletingPostId: null,
            processing: false,
        };
    },
    methods: {
        applyFilters: debounce(function () {
            router.get(route('client.profile.posts.index'), this.localFilters, {
                preserveState: true,
                preserveScroll: true,
            });
        }, 300),

        formatDate(date) {
            if (!date) return '—';
            return new Date(date).toLocaleDateString('ru-RU', {
                day: '2-digit', month: '2-digit', year: 'numeric',
            });
        },

        askDelete(postId) {
            this.deletingPostId = postId;
        },

        confirmDelete() {
            if (!this.deletingPostId) return;

            this.processing = true;

            router.delete(route('client.posts.destroy', this.deletingPostId), {
                preserveScroll: true,
                onSuccess: () => {
                    this.deletingPostId = null;
                    this.processing = false;
                },
                onError: () => {
                    this.processing = false;
                    alert('Не удалось удалить пост');
                },
            });
        },

        cancelDelete() {
            this.deletingPostId = null;
        },
    },
};
</script>

<template>
    <div class="space-y-6">
        <!-- Заголовок и статистика -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Мои посты</h1>
            <Link
                :href="route('client.posts.create')"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm"
            >
                + Добавить пост
            </Link>
        </div>

        <!-- Статистика -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ stats.total }}</div>
                <div class="text-sm text-gray-500">Всего</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ stats.published }}</div>
                <div class="text-sm text-gray-500">Опубликовано</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ stats.draft }}</div>
                <div class="text-sm text-gray-500">Черновиков</div>
            </div>
        </div>

        <!-- Фильтры -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex flex-wrap gap-3 items-center">
                <select
                    v-model="localFilters.status"
                    @change="applyFilters"
                    class="border rounded-lg p-2 text-sm"
                >
                    <option value="">Все посты</option>
                    <option value="published">Опубликованные</option>
                    <option value="draft">Черновики</option>
                </select>

                <button
                    v-if="localFilters.status"
                    @click="localFilters.status = ''; applyFilters()"
                    class="text-sm text-gray-500 hover:text-gray-700 underline"
                >
                    Сбросить
                </button>
            </div>
        </div>

        <!-- Список постов -->
        <div v-if="posts.data && posts.data.length" class="space-y-3">
            <div
                v-for="post in posts.data"
                :key="post.id"
                class="bg-white rounded-lg shadow p-5"
            >
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                            <Link
                                :href="route('client.posts.show', post.id)"
                                class="hover:text-blue-600"
                            >
                                {{ post.title }}
                            </Link>
                        </h3>
                        <div class="flex items-center gap-3 text-sm text-gray-500">
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 text-xs">
                                {{ post.category?.[0]?.title || 'Без категории' }}
                            </span>
                            <span>📅 {{ post.updated_at }}</span>
                            <span>💬 {{ post.comments_count }}</span>
                            <span>❤️ {{ post.likes_count }}</span>
                        </div>
                    </div>

                    <span
                        class="px-2 py-1 text-xs rounded-full"
                        :class="post.published
                            ? 'bg-green-100 text-green-800'
                            : 'bg-yellow-100 text-yellow-800'"
                    >
                        {{ post.published ? 'Опубликован' : 'Черновик' }}
                    </span>
                </div>

                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                    {{ post.description || post.content }}
                </p>

                <div class="flex items-center gap-3 pt-3 border-t">
                    <Link
                        :href="route('client.posts.show', post.id)"
                        class="text-blue-600 hover:text-blue-800 text-sm"
                    >
                        Просмотр
                    </Link>

<!--                    <Link-->
<!--                        :href="route('client.posts.edit', post.id)"-->
<!--                        class="text-gray-600 hover:text-gray-800 text-sm"-->
<!--                    >-->
<!--                        ✏️ Редактировать-->
<!--                    </Link>-->

                    <button
                        @click="askDelete(post.id)"
                        class="text-red-600 hover:text-red-800 text-sm ml-auto"
                    >
                        🗑️ Удалить
                    </button>
                </div>
            </div>
        </div>

        <!-- Пустое состояние -->
        <div v-else class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-5xl mb-4">📝</div>
            <h3 class="text-lg font-medium text-gray-700 mb-2">
                {{ localFilters.status ? 'Постов не найдено' : 'У вас пока нет постов' }}
            </h3>
            <p class="text-gray-500 text-sm mb-4">
                {{ localFilters.status
                ? 'Попробуйте изменить фильтр'
                : 'Поделитесь своим первым анекдотом!' }}
            </p>
            <Link
                v-if="!localFilters.status"
                :href="route('client.posts.create')"
                class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm"
            >
                + Добавить пост
            </Link>
        </div>

        <!-- Пагинация -->
        <Pagination
            v-if="posts.links && posts.links.length > 3"
            :links="posts.links"
            :meta="posts.meta"
        />

        <!-- Модальное окно удаления -->
        <ConfirmDeleteModal
            :show="!!deletingPostId"
            :processing="processing"
            title="Удалить пост?"
            message="Пост будет удалён. Это действие необратимо."
            @confirm="confirmDelete"
            @close="cancelDelete"
        />
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
