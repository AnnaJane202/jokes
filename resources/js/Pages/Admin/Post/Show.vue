<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: 'Show',

    layout: AdminLayout,

    props: {
        post: Object,
        comments: Array,
    },


    methods: {
        deleteComment(commentId) {
            if (!confirm('Удалить этот комментарий?')) {
                return;
            }

            this.$inertia.delete(route('admin.comments.destroy', commentId), {
                preserveScroll: true,
                onSuccess: () => {
                    // Удаляем комментарий из локального списка (оптимистичное обновление)
                    this.comments = this.comments.filter(c => c.id !== commentId);
                },
                onError: (errors) => {
                    console.error('Ошибка удаления:', errors);
                    alert('Не удалось удалить комментарий');
                },
            });
        },
    },

    components: {
        Link,
    }

}
</script>

<template>
    <!-- Основной блок с просмотром поста (на мобильных - под сайдбаром) -->
    <main class="w-full lg:w-5/6 flex flex-col">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
            <!-- Заголовок поста -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">Просмотр поста</h2>
                    <div class="flex space-x-2">
<!--                        <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">-->
<!--                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>-->
<!--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>-->
<!--                            </svg>-->
<!--                            Предпросмотр-->
<!--                        </button>-->
                        <button class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <Link :href="route('admin.posts.edit', post.id)">Редактировать</Link>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Просмотр поста - растягивается на доступное пространство -->
            <div class="flex-1 overflow-auto">
                <div class="max-w-4xl mx-auto p-4 sm:p-6">
                    <!-- Заголовок поста -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ post.category[0].title }}
<!--                                    fbure-->
                                </span>
<!--                            <div class="text-sm text-gray-500">-->
<!--                                    <span class="inline-flex items-center">-->
<!--                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>-->
<!--                                        </svg>-->
<!--                                        15 минут чтения-->
<!--                                    </span>-->
<!--                            </div>-->
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ post.title }}</h1>

                        <!-- Мета-информация -->
                        <div class="flex flex-wrap items-center text-sm text-gray-500 mb-6">
                            <div class="flex items-center mr-6 mb-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold mr-2">

                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ post.user.name }}</p>
                                    <p>Автор</p>
                                </div>
                            </div>
                            <div class="flex items-center mr-6 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ post.updated_at }}</span>
                            </div>
                            <div class="flex items-center mr-6 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<!--                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>-->
<!--                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>-->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                    </svg>
                                </svg>
                                <span>{{ post.likes_count }}</span>
                            </div>
<!--                            <div class="flex items-center mb-2">-->
<!--                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>-->
<!--                                </svg>-->
<!--                                <span>48 комментариев</span>-->
<!--                            </div>-->
                        </div>

                        <!-- Статус поста -->
                        <div v-if="post.published == 1" class="flex items-center justify-between p-4 bg-green-50 rounded-lg mb-6">
                            <span >
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="font-medium text-green-800">Пост опубликован</span>
                                </div>
                                <div class="text-sm text-green-700">
                                    Опубликовано: 15 марта 2024, 10:30
                                </div>
                            </span>
                        </div>
                        <div v-if="post.published == 0" class="flex items-center justify-between p-4 bg-red-50 rounded-lg mb-6">
                            <span >
                                <div class="flex items-center">
                                    <span class="font-medium text-red-800">Пост неопубликован</span>
                                </div>

                            </span>
                        </div>
                    </div>



                    <!-- Контент поста -->
                    <div class="prose max-w-none mb-8">
                        <h1 class="text-xl text-indigo-600">{{ post.title }}</h1>
                        <p class="lead">
                            {{ post.content}}
                        </p>
                    </div>

                    <!-- Теги поста -->
<!--                    <div class="mb-8">-->
<!--                        <h3 class="text-lg font-medium text-gray-900 mb-3">Теги:</h3>-->
<!--                        <div class="flex flex-wrap gap-2">-->
<!--                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">React</span>-->
<!--                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">JavaScript</span>-->
<!--                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Frontend</span>-->
<!--                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Web Development</span>-->
<!--                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Concurrent Rendering</span>-->
<!--                        </div>-->
<!--                    </div>-->

                    <!-- Статистика поста -->
<!--                    <div class="bg-gray-50 rounded-lg p-6 mb-8">-->
<!--                        <h3 class="text-lg font-medium text-gray-900 mb-4">Статистика поста</h3>-->
<!--                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">-->
<!--                            <div class="text-center">-->
<!--                                <div class="text-2xl font-bold text-gray-900">2,543</div>-->
<!--                                <div class="text-sm text-gray-600">Просмотры</div>-->
<!--                            </div>-->
<!--                            <div class="text-center">-->
<!--                                <div class="text-2xl font-bold text-gray-900">48</div>-->
<!--                                <div class="text-sm text-gray-600">Комментарии</div>-->
<!--                            </div>-->
<!--                            <div class="text-center">-->
<!--                                <div class="text-2xl font-bold text-gray-900">127</div>-->
<!--                                <div class="text-sm text-gray-600">Лайки</div>-->
<!--                            </div>-->
<!--                            <div class="text-center">-->
<!--                                <div class="text-2xl font-bold text-gray-900">4.8</div>-->
<!--                                <div class="text-sm text-gray-600">Рейтинг</div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

                    <div class="mt-6">
                        <h2 class="text-xl font-bold mb-4">Комментарии к посту</h2>
                        <div v-if="comments && comments.length" class="space-y-3">
                            <div v-for="comment in comments" :key="comment.id" class="bg-gray-50 p-3 rounded">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ comment.user.name }}</span>
                                    <span class="text-sm text-gray-500">{{ comment.created_at }}</span>
                                </div>
                                <p class="mt-1">{{ comment.content }}</p>
                                <div class="flex gap-2 mt-2">
                                    <Link :href="route('admin.comments.edit', comment.id)" class="text-blue-600 text-sm hover:underline">
                                        Редактировать
                                    </Link>
                                    <button @click="deleteComment(comment.id)" class="text-red-600 text-sm hover:underline">
                                        Удалить
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-gray-500">Комментариев нет</div>
                    </div>

                </div>
            </div>



        </div>
    </main>
</template>

<style scoped>

</style>
