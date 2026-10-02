<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: 'Show',

    layout: AdminLayout,

    props: {
        category: Object
    },



    components: {
        Link
    },


}
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
        <!-- Заголовок таблицы -->
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">{{ category.title }}</h2>
<!--                <div class="flex space-x-2">-->
<!--                    <button class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm whitespace-nowrap">-->
<!--                        <Link :href="route('admin.categories.create')">Добавить категорию</Link>-->
<!--                    </button>-->
<!--                </div>-->
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
                            Slug
                        </th>
<!--                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">-->
<!--                            Действия-->
<!--                        </th>-->
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Строка 1 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ category.id }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ category.title }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ category.slug }}</td>
<!--                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">-->
<!--                            <button class="text-blue-600 hover:text-blue-900 mr-3">Редактировать</button>-->
<!--                            <button class="text-red-600 hover:text-red-900">Удалить</button>-->
<!--                        </td>-->
                    </tr>

                    </tbody>
                </table>

                <!-- Мобильная версия таблицы (карточки) -->
                <div class="md:hidden p-4 space-y-4">
                    <!-- Карточка 1 -->
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ category.title }}</h3>
                                <p class="text-sm text-gray-500">ID: {{ category.id }}</p>
                            </div>
                        </div>
<!--                        <div class="flex space-x-2 pt-2">-->
<!--                            <button class="flex-1 bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700 transition-colors">-->
<!--                                Редактировать-->
<!--                            </button>-->
<!--                            <button class="flex-1 bg-red-600 text-white py-2 px-3 rounded text-sm hover:bg-red-700 transition-colors">-->
<!--                                Удалить-->
<!--                            </button>-->
<!--                        </div>-->
                    </div>

                </div>
            </div>
        </div>

        <!-- Пагинация -->
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex-shrink-0">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="text-sm text-gray-700">
                    Показано с <span class="font-medium">1</span> по <span class="font-medium">4</span> из <span class="font-medium">24</span> записей
                </div>
                <div class="flex space-x-2 overflow-x-auto">
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap">
                        Назад
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 whitespace-nowrap">
                        1
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap">
                        2
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap">
                        3
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap">
                        Вперед
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
