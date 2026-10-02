<script>
import UserProfileLayout from '@/Layouts/UserProfileLayout.vue';
import { Link } from '@inertiajs/vue3';

export default {
    name: 'ViolationShow',
    layout: UserProfileLayout,
    components: { Link },
    props: {
        violation: Object,
    },
    computed: {
        // Можно ли подать апелляцию
        canAppeal() {
            return this.violation.status === 'active'
                && !this.violation.appeal_reason
                && this.violation.days_left >= 0;
        },
        // Показать дедлайн, если близко
        isDeadlineNear() {
            return this.violation.days_left !== null && this.violation.days_left <= 3;
        },
    },
    methods: {
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
    },
};
</script>

<template>
    <div class="space-y-6">
        <!-- Хлебные крошки -->
        <div>
            <Link
                :href="route('client.violations.index')"
                class="text-blue-600 hover:underline text-sm"
            >
                ← Мои нарушения
            </Link>
        </div>

        <!-- Карточка нарушения -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h1 class="text-xl font-bold">Нарушение #{{ violation.id }}</h1>
                <span class="px-3 py-1 text-sm rounded-full bg-gray-100">
                    {{ violation.status_label }}
                </span>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Тип нарушения</p>
                        <p class="font-medium">{{ violation.type_label }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Наказание</p>
                        <p class="font-medium">{{ violation.penalty_label }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Дата вынесения</p>
                        <p>{{ formatDate(violation.created_at) }}</p>
                    </div>
                    <div v-if="violation.active_until">
                        <p class="text-sm text-gray-500">Действует до</p>
                        <p>{{ violation.active_until }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Причина</p>
                    <p class="p-3 bg-gray-50 rounded">{{ violation.reason }}</p>
                </div>

                <!-- Дедлайн подачи апелляции -->
                <div v-if="canAppeal && isDeadlineNear" class="p-3 bg-red-50 border border-red-200 rounded">
                    <p class="text-red-700 text-sm">
                        ⏰ Осталось <strong>{{ violation.days_left }}</strong> дн. на подачу апелляции
                    </p>
                </div>

                <!-- Апелляция (если подана) -->
                <div v-if="violation.appeal_reason" class="border-t pt-4">
                    <h3 class="font-medium mb-2">Ваша апелляция</h3>
                    <p class="p-3 bg-yellow-50 rounded">{{ violation.appeal_reason }}</p>

                    <div v-if="violation.appeal_decision_comment" class="mt-3">
                        <p class="text-sm text-gray-500 mb-1">Решение модератора</p>
                        <p class="p-3 bg-blue-50 rounded">{{ violation.appeal_decision_comment }}</p>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="px-6 py-4 border-t bg-gray-50 flex flex-wrap gap-3 justify-between">
                <Link
                    :href="route('client.violations.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                >
                    ← Назад к списку
                </Link>

                <!-- ✅ Кнопка "Подать апелляцию" -->
                <Link
                    v-if="canAppeal"
                    :href="route('client.violations.appeal', violation.id)"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                >
                    ⚖️ Подать апелляцию
                </Link>

                <!-- Если апелляция уже подана -->
                <span
                    v-else-if="violation.status === 'appealed'"
                    class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded"
                >
                    Апелляция на рассмотрении
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
