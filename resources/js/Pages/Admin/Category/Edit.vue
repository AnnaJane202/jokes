<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: 'Edit',

    layout: AdminLayout,

    props: {
        category: Object
    },

    components: {
        Link
    },

    data(){
        return {
            success: false
        }
    },

    methods: {
        updateCategory()
        {

            axios.patch(route('admin.categories.update', this.category), this.category)
                .then( res => {
                    console.log(res);
                    this.success = true;
                });
        }
    },

    watch: {
        category: {
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
                    <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">Редактирование категории</h2>
                </div>
            </div>

            <!-- Форма - растягивается на доступное пространство -->
            <div class="flex-1 overflow-auto">
                <div class="p-4 sm:p-6 space-y-6">
                    <!-- Заголовок поста -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title *
                        </label>
                        <input
                            v-model="category.title"
                            type="text"
                            id="title"
                            name="title"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Введите title"
                            required
                        >
                    </div>

                    <!-- URL slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            URL slug *
                        </label>
                        <div class="flex rounded-lg shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    site.com/categories/
                                </span>
                            <input
                                v-model="category.slug"
                                type="text"
                                id="slug"
                                name="slug"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="url-slug"
                            >
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
                        <button @click.prevent="updateCategory" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm whitespace-nowrap">
                             Обновить
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </main>

</template>

<style scoped>

</style>
