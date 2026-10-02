<script>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import { debounce } from 'lodash';
import { router } from '@inertiajs/vue3';
export default {
    name: 'Index',

    layout: AdminLayout,

    components: {
        Link,
        Pagination,
    },

    props: {
        reports: Object,
        filters: Object,
        stats: Object,
        violationTypes: Array,
        statusOptions: Array,
    },

    data() {
        return {
            localFilters: {
                status: this.filters.status ?? '',
                type: this.filters.type ?? '',
                search: this.filters.search ?? '',
            },
        };
    },

    methods: {
        applyFilters() {
            router.get(route('admin.reports.index'), this.localFilters, {
                preserveState: true,
                preserveScroll: true,
            });
        },
        resetFilters() {
            this.localFilters = { status: '', type: '', search: '' };
            this.applyFilters();
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('ru-RU', {
                day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit',
            });
        },
        statusClass(status) {
            return {
                pending: 'bg-yellow-100 text-yellow-800',
                reviewing: 'bg-blue-100 text-blue-800',
                resolved: 'bg-green-100 text-green-800',
                rejected: 'bg-red-100 text-red-800',
            }[status] || 'bg-gray-100 text-gray-800';
        },
    },

    // ДЕБАНС ВЫНОСИМ В created ИЛИ mounted
    created() {
        this.applyFiltersDebounced = debounce(this.applyFilters, 300);
    },
    // Чтобы избежать утечек, можно отменить debounce при уничтожении
    beforeUnmount() {
        this.applyFiltersDebounced.cancel();
    },
}
</script>

<template>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Заголовок + статистика -->
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h1 class="text-2xl font-bold">Жалобы</h1>
                    <div class="flex gap-4 text-sm">
                        <span class="text-gray-600">Всего: {{ stats.total }}</span>
                        <span class="text-yellow-600">В ожидании: {{ stats.pending }}</span>
                        <span class="text-green-600">Решено: {{ stats.resolved }}</span>
                        <span class="text-red-600">Отклонено: {{ stats.rejected }}</span>
                    </div>
                </div>

                <!-- Фильтры -->
                <div class="px-6 py-3 border-b bg-gray-50 flex flex-wrap gap-3 items-center">
                    <select v-model="localFilters.status" @change="applyFilters" class="border rounded p-2 text-sm">
                        <option value="">Все статусы</option>
                        <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                    <select v-model="localFilters.type" @change="applyFilters" class="border rounded p-2 text-sm">
                        <option value="">Все типы</option>
                        <option v-for="t in violationTypes" :key="t.value" :value="t.value">
                            {{ t.label }}
                        </option>
                    </select>
                    <input
                        v-model="localFilters.search"
                        @input="applyFiltersDebounced"
                        placeholder="Поиск по причине..."
                        class="border rounded p-2 text-sm flex-1 min-w-[200px]"
                    />
                    <button @click="resetFilters" class="text-sm text-gray-600 hover:text-gray-800">Сбросить</button>
                </div>

                <!-- Таблица -->
                <div v-if="reports.data.length === 0" class="p-8 text-center text-gray-500">
                    Нет жалоб
                </div>
                <div v-else class="overflow-x-auto">
                    <table  class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Отправитель</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">На кого</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Тип</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Причина</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <tr v-for="report in reports.data" :key="report.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">#{{ report.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ report.reporter?.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ report.reported_user?.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ report.type_label }}</td>
                            <td class="px-6 py-4 text-sm truncate max-w-xs">{{ report.reason }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" :class="statusClass(report.status)">
                                        {{ report.status_label }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ formatDate(report.created_at) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <Link
                                    v-if="report.status === 'pending' || report.status === 'reviewing'"
                                    :href="route('admin.reports.show', report.id)"
                                    class="text-blue-600 hover:underline"
                                >
                                    Рассмотреть
                                </Link>
                                <Link
                                    v-else
                                    :href="route('admin.reports.show', report.id)"
                                    class="text-gray-500 hover:underline"
                                >
                                    Просмотр
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                <!-- Пагинация -->
                <div class="px-6 py-4 border-t">
                    <Pagination :links="reports.links" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
