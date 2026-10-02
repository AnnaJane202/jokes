<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

export default {
    name: 'Index',

    layout: AdminLayout,

    props: {
        users: Object,
    },

    data() {
        return {
           usersData: this.users.data,
        }
    },

    methods: {
        deleteUser(user)
        {
            axios.delete(route('admin.users.destroy', user.id))
                .then( res => {
                    console.log(res);
                    this.usersData = this.usersData.filter ( userData => userData.id !== user.id);
                });
        }
    },

    components: {
        Pagination,
        Link,
    }

}
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
        <!-- Заголовок таблицы -->
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">Список пользователей</h2>
                <div class="flex space-x-2">
                    <button class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">
                        Добавить пользователя
                    </button>
<!--                    <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm whitespace-nowrap">-->
<!--                        Экспорт-->
<!--                    </button>-->
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
                            Имя
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Роль
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Действия
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Строка 1 -->
                    <tr v-for="user in usersData" class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.id }}</td>
                        <Link :href="route('admin.users.show', user.id)">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.name }}</td>
                        </Link>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.role[0] }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <Link :href="route('admin.users.edit', user.id)">
                                <button class="text-blue-600 hover:text-blue-900 mr-3">Редактировать</button>
                            </Link>
                            <button @click.prevent="deleteUser(user)" class="text-red-600 hover:text-red-900">Удалить</button>
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
                                <h3 class="font-medium text-gray-900">Иван Иванов</h3>
                                <p class="text-sm text-gray-500">ID: 1</p>
                            </div>

                        </div>
                        <div class="text-sm">
                            <p class="text-gray-600">ivan@example.com</p>
                            <p class="text-gray-600">Роль: Администратор</p>
                        </div>
                        <div class="flex space-x-2 pt-2">
                            <button class="flex-1 bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700 transition-colors">
                                Редактировать
                            </button>
                            <button class="flex-1 bg-red-600 text-white py-2 px-3 rounded text-sm hover:bg-red-700 transition-colors">
                                Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Пагинация: links и meta на верхнем уровне -->
        <div class="mt-4">
            <Pagination :links="users.links" :meta="users.meta" />
        </div>

    </div>
</template>

<style scoped>

</style>
