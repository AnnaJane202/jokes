<script>
import UserProfileLayout from '@/Layouts/UserProfileLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

export default {
    name: 'AppealIndex',
    layout: UserProfileLayout,
    components: { Link, Pagination },
    props: {
        appeals: Object,
        filters: Object,
    },
    methods: {
        formatDate(date) {
            if (!date) return '—';
            return new Date(date).toLocaleDateString('ru-RU', {
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        },
        statusClass(status) {
            return {
                appealed: 'bg-yellow-100 text-yellow-800',
                pardoned: 'bg-green-100 text-green-800',
                active: 'bg-red-100 text-red-800',
                rejected: 'bg-gray-100 text-gray-800',
            }[status] || 'bg-gray-100 text-gray-800';
        },
    },
};
</script>

<template>
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-gray-800">Мои апелляции</h1>

        <div v-if="appeals.data && appeals.data.length" class="space-y-3">
            <div
                v-for="appeal in appeals.data"
                :key="appeal.id"
                class="bg-white rounded-lg shadow p-5"
            >
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-400">#{{ appeal.id }}</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100">
                            {{ appeal.type_label }}
                        </span>
                        <span
                            class="px-2 py-1 text-xs rounded-full"
                            :class="statusClass(appeal.status)"
                        >
                            {{ appeal.status_label }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-400">
                        {{ formatDate(appeal.created_at) }}
                    </span>
                </div>

                <p class="text-gray-700 mb-3">
                    <strong>Наказание:</strong> {{ appeal.penalty_label }}
                </p>

                <div class="p-3 bg-yellow-50 rounded mb-3">
                    <p class="text-sm text-gray-600 mb-1">Ваша апелляция:</p>
                    <p>{{ appeal.appeal_reason }}</p>
                </div>

                <div v-if="appeal.appeal_decision_comment" class="p-3 bg-blue-50 rounded">
                    <p class="text-sm text-gray-600 mb-1">Решение модератора:</p>
                    <p>{{ appeal.appeal_decision_comment }}</p>
                </div>

                <div class="mt-3">
                    <Link
                        :href="route('client.violations.show', appeal.id)"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                    >
                        Подробнее →
                    </Link>
                </div>
            </div>
        </div>

        <div v-else class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-5xl mb-4">⚖️</div>
            <h3 class="text-lg font-medium text-gray-700 mb-2">Апелляций нет</h3>
            <p class="text-gray-500 text-sm">Вы ещё не подавали апелляции</p>
        </div>

        <Pagination
            v-if="appeals.links && appeals.links.length > 3"
            :links="appeals.links"
            :meta="appeals.meta"
        />
    </div>
</template>

<style scoped>

</style>
