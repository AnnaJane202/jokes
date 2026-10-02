<script>
import UserProfileLayout from '@/Layouts/UserProfileLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import { debounce } from 'lodash';

export default {
    name: 'ViolationIndex',
    layout: UserProfileLayout,
    components: { Link, Pagination },
    props: {
        violations: Object,
        filters: Object,
        statuses: Object,
        types: Object,
    },
    data() {
        return {
            localFilters: {
                status: this.filters.status || '',
                type: this.filters.type || '',
            },
        };
    },
    methods: {
        applyFilters: debounce(function () {
            router.get(route('client.violations.index'), this.localFilters, {
                preserveState: true,
                preserveScroll: true,
            });
        }, 300),

        resetFilters() {
            this.localFilters = { status: '', type: '' };
            this.applyFilters();
        },

        formatDate(date) {
            if (!date) return '—';
            return new Date(date).toLocaleDateString('ru-RU', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        },

        statusClass(status) {
            return {
                active: 'bg-red-100 text-red-800',
                appealed: 'bg-yellow-100 text-yellow-800',
                pardoned: 'bg-green-100 text-green-800',
                rejected: 'bg-gray-100 text-gray-800',
                modified: 'bg-blue-100 text-blue-800',
                expired: 'bg-gray-100 text-gray-500',
            }[status] || 'bg-gray-100 text-gray-800';
        },

        canAppeal(violation) {
            return violation.status === 'active'
                && !violation.appeal_reason
                && violation.days_left >= 0;
        },
    },
};

</script>

<template>
    <div class="space-y-6">
        <!-- Заголовок -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Мои нарушения</h1>
        </div>

        <!-- Фильтры -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex flex-wrap gap-3">
                <select
                    v-model="localFilters.status"
                    @change="applyFilters"
                    class="border rounded-lg p-2 text-sm"
                >
                    <option value="">Все статусы</option>
                    <option v-for="(label, value) in statuses" :key="value" :value="value">
                        {{ label }}
                    </option>
                </select>

                <select
                    v-model="localFilters.type"
                    @change="applyFilters"
                    class="border rounded-lg p-2 text-sm"
                >
                    <option value="">Все типы</option>
                    <option v-for="(label, value) in types" :key="value" :value="value">
                        {{ label }}
                    </option>
                </select>

                <button
                    v-if="localFilters.status || localFilters.type"
                    @click="resetFilters"
                    class="text-sm text-gray-500 hover:text-gray-700 underline"
                >
                    Сбросить
                </button>
            </div>
        </div>

        <!-- Список нарушений -->
        <div v-if="violations.data && violations.data.length" class="space-y-3">
            <div
                v-for="violation in violations.data"
                :key="violation.id"
                class="bg-white rounded-lg shadow hover:shadow-md transition"
                :class="{ 'border-l-4 border-red-500': violation.status === 'active' }"
            >
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-400">#{{ violation.id }}</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100">
                                {{ violation.type_label }}
                            </span>
                            <span
                                class="px-2 py-1 text-xs rounded-full"
                                :class="statusClass(violation.status)"
                            >
                                {{ violation.status_label }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400">
                            {{ formatDate(violation.created_at) }}
                        </span>
                    </div>

                    <p class="text-gray-700 mb-3">{{ violation.reason }}</p>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                        <span>
                            <strong>Наказание:</strong> {{ violation.penalty_label }}
                            <span v-if="violation.duration_days"> ({{ violation.duration_days }} дн.)</span>
                        </span>

                        <span v-if="violation.active_until">
                            <strong>Действует до:</strong> {{ violation.active_until }}
                        </span>

                        <span v-if="violation.admin">
                            <strong>Модератор:</strong> {{ violation.admin.name }}
                        </span>
                    </div>

                    <!-- Действия -->
                    <div class="flex flex-wrap items-center gap-3 mt-4 pt-3 border-t">
                        <Link
                            :href="route('client.violations.show', violation.id)"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                        >
                            Подробнее
                        </Link>

                        <Link
                            v-if="canAppeal(violation)"
                            :href="route('client.violations.appeal', violation.id)"
                            class="text-red-600 hover:text-red-800 text-sm font-medium"
                        >
                            ⚖️ Подать апелляцию
                        </Link>

                        <span
                            v-if="violation.status === 'appealed'"
                            class="text-yellow-600 text-sm"
                        >
                            Апелляция на рассмотрении
                        </span>

                        <span
                            v-if="violation.status === 'pardoned'"
                            class="text-green-600 text-sm"
                        >
                            ✅ Наказание снято
                        </span>

                        <span
                            v-if="canAppeal(violation) && violation.days_left <= 3"
                            class="text-red-500 text-xs"
                        >
                            ⏰ Осталось {{ violation.days_left }} дн. на апелляцию
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Пустое состояние -->
        <div v-else class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-5xl mb-4">🎉</div>
            <h3 class="text-lg font-medium text-gray-700 mb-2">Нарушений нет</h3>
            <p class="text-gray-500 text-sm">
                У вас чистая история — так держать!
            </p>
        </div>

        <!-- Пагинация -->
        <Pagination
            v-if="violations.links && violations.links.length > 3"
            :links="violations.links"
            :meta="violations.meta"
        />
    </div>
</template>

<style scoped>

</style>
