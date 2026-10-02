<script>
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    name: 'Create',

    layout: MainLayout,

    props: {
        categories: Array,

    },

    data() {
        return {
            success: false,
            post: {
                // slug: 'slug',
                likes: 0,
                description: ' description',
                user_id: null,
                category_id: null,
                published: false,
            }
        }
    },

    methods: {
        storePost() {
            // console.log(this.post);

            axios.post(route('client.posts.store'), this.post)
                .then ( res => {
                    this.post = {
                        slug: 'slug',
                        likes: 0,
                        description: ' description',
                        user_id: null,
                        category_id: null,
                        published: false,
                    };
                    this.success = true;
                });

        },

        // testClick() {
        //     console.log('Click работает!');
        // }
    },

    watch: {
        post: {
            handler() {
                this.success = false;
            },
            deep: true
        }
    }

}
</script>

<template>
    <div v-if="success" class="p-4 bg-green-100 mb-4">
        Успешно сохранено!
    </div>
    <!-- Основной блок с формой (на мобильных - под сайдбаром) -->
    <main class="w-full lg:w-5/6 flex flex-col">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
            <!-- Заголовок формы -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">Создание нового анекдота</h2>
<!--                    <div class="flex space-x-2">-->
<!--                        <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">-->
<!--                            Отменить-->
<!--                        </button>-->
<!--                        <button class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">-->
<!--                            Сохранить черновик-->
<!--                        </button>-->
<!--                    </div>-->
                </div>
            </div>

            <!-- Форма - растягивается на доступное пространство -->
            <div class="flex-1 overflow-auto">
                <div class="p-4 sm:p-6 space-y-6">
                    <!-- Заголовок поста -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Заголовок *
                        </label>
                        <input
                            v-model="post.title"
                            type="text"
                            id="title"
                            name="title"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Введите заголовок"
                            required
                        >
                    </div>

                    <!-- URL slug -->
<!--                    <div>-->
<!--                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">-->
<!--                            URL slug-->
<!--                        </label>-->
<!--                        <div class="flex rounded-lg shadow-sm">-->
<!--                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">-->
<!--                                    site.com/posts/-->
<!--                                </span>-->
<!--                            <input-->
<!--                                type="text"-->
<!--                                id="slug"-->
<!--                                name="slug"-->
<!--                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"-->
<!--                                placeholder="url-slug"-->
<!--                            >-->
<!--                        </div>-->
<!--                    </div>-->

                    <!-- Категория -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Категория
                        </label>
                        <select
                            v-model="post.category_id"
                            id="category"
                            name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                            <option :value="null" disabled selected>Выберите категорию</option>
                            <option v-for="category in categories" :value="category.id">{{ category.title }}</option>

                        </select>
                    </div>

                    <!-- Теги -->
<!--                    <div>-->
<!--                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">-->
<!--                            Теги-->
<!--                        </label>-->
<!--                        <input-->
<!--                            type="text"-->
<!--                            id="tags"-->
<!--                            name="tags"-->
<!--                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"-->
<!--                            placeholder="Введите теги через запятую"-->
<!--                        >-->
<!--                        <p class="mt-1 text-sm text-gray-500">Например: html, css, javascript, web-development</p>-->
<!--                    </div>-->

                    <!-- Основной контент -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Содержание *
                        </label>
                        <textarea
                            v-model="post.content"
                            id="content"
                            name="content"
                            rows="12"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical"
                            placeholder="Начните писать ваш анекдот здесь..."
                            required
                        ></textarea>
                    </div>

                    <!-- Изображение -->
<!--                    <div>-->
<!--                        <label class="block text-sm font-medium text-gray-700 mb-2">-->
<!--                            Изображение поста-->
<!--                        </label>-->
<!--                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">-->
<!--                            <div class="space-y-1 text-center">-->
<!--                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">-->
<!--                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />-->
<!--                                </svg>-->
<!--                                <div class="flex text-sm text-gray-600">-->
<!--                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">-->
<!--                                        <span>Загрузить файл</span>-->
<!--                                        <input id="file-upload" name="file-upload" type="file" class="sr-only">-->
<!--                                    </label>-->
<!--                                    <p class="pl-1">или перетащите</p>-->
<!--                                </div>-->
<!--                                <p class="text-xs text-gray-500">PNG, JPG, GIF до 10MB</p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

                     Настройки публикации
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Настройки публикации</h3>

                        <div class="space-y-4">
                            <!-- Статус -->
<!--                            <div>-->
<!--                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">-->
<!--                                    Статус-->
<!--                                </label>-->
<!--                                <select-->
<!--                                    id="status"-->
<!--                                    name="status"-->
<!--                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"-->
<!--                                >-->
<!--                                    <option value="draft">Черновик</option>-->
<!--                                    <option value="published">Опубликован</option>-->
<!--                                    <option value="scheduled">Запланирован</option>-->
<!--                                </select>-->
<!--                            </div>-->

                            <!-- Дата публикации -->
<!--                            <div>-->
<!--                                <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-2">-->
<!--                                    Дата публикации-->
<!--                                </label>-->
<!--                                <input-->
<!--                                    type="datetime-local"-->
<!--                                    id="publish_date"-->
<!--                                    name="publish_date"-->
<!--                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"-->
<!--                                >-->
<!--                            </div>-->

                            <!-- Автор -->
                            <div>
                                <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                                    Автор
                                </label>
                                <select
                                    v-model="post.user_id"
                                    id="author"
                                    name="author"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                >
                                    <option :value="$page.props.auth.user.id">{{$page.props.auth.user.name}}</option>
                                </select>
                            </div>

                            <!-- Чекбоксы -->
<!--                            <div class="space-y-2">-->
<!--                                <div class="flex items-center">-->
<!--                                    <input-->
<!--                                        id="featured"-->
<!--                                        name="featured"-->
<!--                                        type="checkbox"-->
<!--                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"-->
<!--                                    >-->
<!--                                    <label for="featured" class="ml-2 block text-sm text-gray-700">-->
<!--                                        Сделать featured-постом-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                                <div class="flex items-center">-->
<!--                                    <input-->
<!--                                        id="comments"-->
<!--                                        name="comments"-->
<!--                                        type="checkbox"-->
<!--                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"-->
<!--                                        checked-->
<!--                                    >-->
<!--                                    <label for="comments" class="ml-2 block text-sm text-gray-700">-->
<!--                                        Разрешить комментарии-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                            </div>-->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex-shrink-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="text-sm text-gray-500">
                        * Обязательные поля
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
<!--                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">-->
<!--                            Предпросмотр-->
<!--                        </button>-->
<!--                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm whitespace-nowrap">-->
<!--                            Опубликовать-->
<!--                        </button>-->
                        <button @click.prevent="storePost" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">
                            Сохранить
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </main>
</template>

<style scoped>

</style>
