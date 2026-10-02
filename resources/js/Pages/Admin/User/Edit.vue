<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: 'Edit',

    props: {
        user: Object,
        roles: Array,

    },

    data() {
        return {
            userData: {
                user: this.user,
                roleTitle: this.user.role[0],
            }
        }
    },

    methods: {
        updateUser()
        {

            axios.patch(route('admin.users.update', this.user.id), this.userData)
                .then( res => {
                    console.log(res);
                });
        },
    },
    components: {
        Link
    },

    layout: AdminLayout
}
</script>

<template>
    <!-- Основной блок с формой (на мобильных - под сайдбаром) -->
    <main class="w-full lg:w-5/6 flex flex-col">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
            <!-- Заголовок формы -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">редактирование пользователя</h2>
                    <div class="flex space-x-2">
<!--                        <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">-->
<!--                            Отменить-->
<!--                        </button>-->
                        <button class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">
                            <Link :href="route('admin.posts.index')">Назад</Link>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Форма - растягивается на доступное пространство -->
            <div class="flex-1 overflow-auto">
                <div class="p-4 sm:p-6 space-y-6">
                    <!-- Заголовок поста -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Имя пользователя *
                        </label>
                        <input
                            v-model="user.name"
                            type="text"
                            id="title"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Введите имя"
                            required
                        >
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Email пользователя *
                        </label>
                        <input
                            v-model="user.email"
                            type="text"
                            id="title"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Введите email"
                            required
                        >
                    </div>




                    <!-- Настройки публикации -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Назначение роли</h3>

                        <div class="space-y-4">
                            <!-- Статус -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                    Роль
<!--                                    <span class="text-green-500 ml-3">{{ user.role }}</span>-->

                                </label>
                                <select
                                    v-model="userData.roleTitle"
                                    id="role"
                                    name="role"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                >

                                    <option v-for="role in roles"
                                            :value="role.title"

                                    >
                                        {{ role.title }}
                                    </option>
                                </select>
                            </div>

<!--                             Дата публикации -->
<!--                            <div v-if="post.published !== 0">-->
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
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">
                            Посмотреть профиль
                        </button>
                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm whitespace-nowrap">
                            Заблокировать
                        </button>
                        <button @click="updateUser" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">
                            Сохранить изменения
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<style scoped>

</style>
