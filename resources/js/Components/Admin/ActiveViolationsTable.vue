<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Pagination from "@/Components/Pagination.vue";

export default {
    name: 'ActiveViolationsTable',

    layout: AdminLayout,

    props: {
        violations: Object,
        tab: String,
    },

    data() {
        return {
            filters: {},
        }
    },

    components: {
        Pagination,
    }
}
</script>

<template>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Активные нарушения</h2>

                <!-- Фильтры -->
                <div class="flex gap-2">
                    <select v-model="filters.type" class="text-sm border rounded px-2 py-1">
                        <option value="">Все типы</option>
                        <option value="spam">Спам</option>
                        <option value="abuse">Оскорбления</option>
                        <option value="harassment">Травля</option>
                    </select>

                    <select v-model="filters.severity" class="text-sm border rounded px-2 py-1">
                        <option value="">Любая серьезность</option>
                        <option value="high">Высокая</option>
                        <option value="medium">Средняя</option>
                        <option value="low">Низкая</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Пользователь
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Тип
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Причина
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Дата
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <tr v-for="violation in violations.data.data" :key="violation.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
<!--                            <img :src="violation.user.avatar" class="h-8 w-8 rounded-full mr-3">-->
                            <div>
                                <div class="font-medium">{{ violation.user.name }}</div>
                                <div class="text-sm text-gray-500">{{ violation.user.email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 text-xs rounded-full">
                {{ violation.type_label }}
              </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-md truncate">{{ violation.reason }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        {{ violation.created_at }}
                    </td>
<!--                    <td class="px-6 py-4 whitespace-nowrap text-sm">-->
<!--                        <button-->
<!--                            @click="$emit('review', violation)"-->
<!--                            class="text-blue-600 hover:text-blue-800 mr-3"-->
<!--                        >-->
<!--                            Рассмотреть-->
<!--                        </button>-->
<!--                        <button-->
<!--                            @click="$emit('ban', violation)"-->
<!--                            class="text-red-600 hover:text-red-800"-->
<!--                        >-->
<!--                            Забанить-->
<!--                        </button>-->
<!--                    </td>-->
                </tr>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t">
            <Pagination
                :links="violations.links"
                :meta="violations.meta"
                :tab="tab"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
