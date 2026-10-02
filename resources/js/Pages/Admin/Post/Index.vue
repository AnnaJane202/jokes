<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

export default {
    name: 'Index',

    layout: AdminLayout,

    props: {
        posts: Object,
        categories: Array,
    },

    data() {
        return {
            postsData: this.posts.data,
        }
    },


    methods: {
        deletePost(post)
        {
            axios.delete(route('admin.posts.destroy', post))
                .then ( res => {
                    this.postsData = this.postsData.filter (postData => postData.id !== post.id);
                    console.log(res);
                });
        }
    },

    components: {
        Pagination,
        Link
    },
}
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
        <!-- Заголовок таблицы -->
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">Список постов</h2>
                <div class="flex space-x-2">
                    <button class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">
                        <Link :href="route('admin.posts.create')">Добавить пост</Link>
                    </button>
                </div>
            </div>
        </div>

        <!-- Таблица - растягивается на доступное пространство -->
        <div class="flex-1 overflow-hidden">
            <div class="h-full overflow-auto">
                <!-- Десктопная таблица -->
                <table class="min-w-full divide-y divide-gray-200 hidden md:table">
                    <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Author
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Likes
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Действия
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Строка 1 -->
                    <tr v-for="post in postsData" class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ post.id }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <Link :href="route('admin.posts.show', post.id)">{{ post.title }}</Link>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ post.category[0].title }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ post.user.name }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ post.likes }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <span v-if="post.published" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Опубликован
                                        </span>
                                        <span v-if="post.published === 0" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500 text-white">
                                            Не опубликован
                                        </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3"><Link :href="route('admin.posts.edit', post.id)">Редактировать</Link></button>
                            <button @click.prevent="deletePost(post)" class="text-red-600 hover:text-red-900 mr-3">Удалить</button>
<!--                            <button class="text-green-600 hover:text-green-700">Опубликовать</button>-->
                        </td>
                    </tr>


                    </tbody>
                </table>

                <!-- Мобильная версия таблицы (карточки) -->
                <div class="md:hidden p-4 space-y-4">
                    <!-- Карточка 1 -->
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">Анекдот1</h3>
                                <p class="text-sm text-gray-500">ID: 1</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Опубликован
                                    </span>
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-600">Категория1</p>
                            <p class="text-gray-600">Автор: Борис</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 pt-2">
                            <button class="flex-1 bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700 transition-colors whitespace-nowrap">
                                Редактировать
                            </button>
                            <button class="flex-1 bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700 transition-colors whitespace-nowrap">
                                Опубликовать
                            </button>
                            <button class="flex-1 bg-red-600 text-white py-2 px-3 rounded text-sm hover:bg-red-700 transition-colors whitespace-nowrap">
                                Удалить
                            </button>
                        </div>
                    </div>
            </div>

            </div>
        </div>

        <!-- Пагинация -->
<!--        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex-shrink-0">-->
<!--            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">-->
<!--                <div class="text-sm text-gray-700">-->
<!--                    Показано с <span class="font-medium">1</span> по <span class="font-medium">4</span> из <span class="font-medium">24</span> записей-->
<!--                </div>-->
<!--                <div class="flex space-x-2 overflow-x-auto">-->
<!--                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap">-->
<!--                        Назад-->
<!--                    </button>-->
<!--                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 whitespace-nowrap">-->
<!--                        1-->
<!--                    </button>-->
<!--                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap">-->
<!--                        2-->
<!--                    </button>-->
<!--                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap">-->
<!--                        3-->
<!--                    </button>-->
<!--                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap">-->
<!--                        Вперед-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

        <!-- Пагинация -->
        <div class="mt-4">
            <Pagination :links="posts.links" />
        </div>
    </div>
</template>

<style scoped>

</style>
