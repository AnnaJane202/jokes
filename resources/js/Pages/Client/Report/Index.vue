<script>
import UserProfileLayout from "@/Layouts/UserProfileLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
export default {
    name: 'Index',

    layout: UserProfileLayout,

    components: {
        Link,
        Pagination,
    },

    props: {
        reports: Object,
    },

    methods: {
        truncate(text, length) {
            return text?.length > length ? text.substring(0, length) + '...' : text
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('ru-RU')
        }
    }
}
</script>

<template>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h1 class="text-2xl font-bold">Мои жалобы</h1>
                </div>

                <div v-if="reports.data?.length === 0" class="p-8 text-center text-gray-500">
                    У вас пока нет жалоб
                </div>

                <div v-else>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Тип</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Причина</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <tr v-for="report in reports.data" :key="report.id">
                            <td class="px-6 py-4 text-sm">#{{ report.id }}</td>
                            <td class="px-6 py-4 text-sm">{{ report.type_label }}</td>
                            <td class="px-6 py-4 text-sm">{{ truncate(report.reason, 50) }}</td>
                            <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full" :class="{
                                            'bg-yellow-100 text-yellow-800': report.status === 'pending',
                                            'bg-blue-100 text-blue-800': report.status === 'reviewing',
                                            'bg-green-100 text-green-800': report.status === 'resolved',
                                            'bg-red-100 text-red-800': report.status === 'rejected',
                                        }">
                                            {{ report.status_label }}
                                        </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ formatDate(report.created_at) }}</td>
                            <td class="px-6 py-4">
                                <Link :href="route('client.reports.show', report.id)" class="text-blue-600 hover:underline">
                                    Просмотр
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="px-6 py-4 border-t">
                        <Pagination :links="reports.links" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
