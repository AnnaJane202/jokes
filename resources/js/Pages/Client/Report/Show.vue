<script>
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
export default {
    name: 'Show',

    layout: ProfileLayout,

    components: {
        Link,
        Pagination,
    },

    props: {
        report: {
            type: Object,
            required: true,
        },
    },

    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString('ru-RU', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            })
        },
    }
}
</script>

<template>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Хлебные крошки -->
            <div class="mb-4">
                <Link :href="route('client.reports.index')" class="text-blue-600 hover:underline">
                    ← Мои жалобы
                </Link>
            </div>

            <!-- Карточка жалобы -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Заголовок -->
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h1 class="text-2xl font-bold">
                        Жалоба #{{ report.id }}
                    </h1>
                    <span
                        class="px-3 py-1 text-sm rounded-full"
                        :class="{
                                'bg-yellow-100 text-yellow-800': report.status === 'pending',
                                'bg-blue-100 text-blue-800': report.status === 'reviewing',
                                'bg-green-100 text-green-800': report.status === 'resolved',
                                'bg-red-100 text-red-800': report.status === 'rejected',
                            }"
                    >
                            {{ report.status_label }}
                        </span>
                </div>

                <!-- Тело -->
                <div class="p-6 space-y-6">
                    <!-- Информация о контенте -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Контент</h3>
                        <p class="mt-1">
                            <span class="font-medium">Тип:</span>
                            {{ report.reportable_type_label }}
                        </p>
                        <p class="mt-1">
                            <span class="font-medium">Название:</span>
                            {{ report.reportable_title || 'Без названия' }}
                        </p>
                        <Link
                            v-if="report.reportable_url && report.reportable_url !== '#'"
                            :href="report.reportable_url"
                            target="_blank"
                            class="text-blue-600 hover:underline text-sm"
                        >
                            Перейти к контенту →
                        </Link>
                    </div>

                    <!-- Детали жалобы -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Тип нарушения</h3>
                            <p class="mt-1">{{ report.type_label }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Дата отправки</h3>
                            <p class="mt-1">{{ formatDate(report.created_at) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Пользователь</h3>
                            <p class="mt-1">{{ report.reported_user?.name || 'Неизвестно' }}</p>
                        </div>
                        <div v-if="report.moderator">
                            <h3 class="text-sm font-medium text-gray-500">Модератор</h3>
                            <p class="mt-1">{{ report.moderator.name }}</p>
                        </div>
                    </div>

                    <!-- Причина -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Причина жалобы</h3>
                        <p class="mt-1 p-3 bg-gray-50 rounded">{{ report.reason }}</p>
                    </div>

                    <!-- Комментарий модератора -->
                    <div v-if="report.moderator_comment">
                        <h3 class="text-sm font-medium text-gray-500">Комментарий модератора</h3>
                        <p class="mt-1 p-3 bg-blue-50 rounded">{{ report.moderator_comment }}</p>
                    </div>

                    <!-- Доказательства -->
                    <div v-if="report.evidence_files && report.evidence_files.length">
                        <h3 class="text-sm font-medium text-gray-500">Доказательства</h3>
                        <div class="mt-2 space-y-2">
                            <a
                                v-for="file in report.evidence_files"
                                :key="file.id"
                                :href="file.url"
                                target="_blank"
                                class="flex items-center gap-2 p-2 bg-gray-50 rounded hover:bg-gray-100 transition"
                            >
                                <span>{{ file.icon || '📎' }}</span>
                                <span class="text-blue-600 hover:underline">{{ file.original_name }}</span>
                                <span class="text-xs text-gray-500 ml-auto">{{ file.size_formatted }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Решение (если есть) -->
<!--                    <div v-if="report.resolution_type" class="border-t pt-4">-->
<!--                        <h3 class="text-sm font-medium text-gray-500">Решение</h3>-->
<!--                        <p class="mt-1">-->
<!--                            {{ report.resolution_type === 'violation_created' ? '✅ Нарушение подтверждено' : '❌ Жалоба отклонена' }}-->
<!--                        </p>-->
<!--                        <p v-if="report.violation" class="mt-1 text-sm">-->
<!--                            <span class="text-gray-500">Нарушение #</span>-->
<!--                            <Link :href="route('profile.violations.show', report.violation.id)" class="text-blue-600 hover:underline">-->
<!--                                {{ report.violation.id }}-->
<!--                            </Link>-->
<!--                        </p>-->
<!--                    </div>-->
                </div>

                <!-- Кнопка назад -->
                <div class="px-6 py-4 border-t bg-gray-50">
                    <Link
                        :href="route('client.reports.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition"
                    >
                        ← Назад к списку
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
