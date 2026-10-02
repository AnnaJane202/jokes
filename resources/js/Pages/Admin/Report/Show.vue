<script>
import {Link} from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from '@inertiajs/vue3';
export default {
    name: 'Show',

    layout: AdminLayout,

    components: {
        Link,
    },

    props: {
        report: Object,
        violationTypes: Array,
        penaltyTypes: Array,
    },

    data() {
        return {
            form: useForm({
                action: '',
                violation_type: '',
                violation_reason: '',
                penalty_type: '',
                duration_days: '',
                moderator_comment: '',
            }),
        };
    },

    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        },
        statusClass(status) {
            return {
                pending: 'bg-yellow-100 text-yellow-800',
                reviewing: 'bg-blue-100 text-blue-800',
                resolved: 'bg-green-100 text-green-800',
                rejected: 'bg-red-100 text-red-800',
            }[status] || 'bg-gray-100 text-gray-800';
        },
        submitResolution() {
            console.log('=== submitResolution ===', {
                action: this.form.action,
                violation_type: this.form.violation_type,
                penalty_type: this.form.penalty_type,
            });

            if (!this.form.action) {
                alert('Выберите действие');
                return;
            }

            try {
                const url = route('admin.reports.resolve', { report: this.report.id });
                console.log('URL:', url);
            } catch (e) {
                console.error('Ошибка построения URL:', e);
                alert('Ошибка: ' + e.message);
                return;
            }

            this.form
                .transform((data) => {
                    console.log('transform данные:', data);
                    if (data.action === 'reject') {
                        delete data.violation_type;
                        delete data.violation_reason;
                        delete data.penalty_type;
                        delete data.duration_days;
                    }
                    return data;
                })
                .post(route('admin.reports.resolve', { report: this.report.id }), {
                    preserveScroll: true,
                    onStart: () => console.log('Запрос начался'),
                    onSuccess: () => console.log('Успех'),
                    onError: (errors) => console.error('Ошибки:', errors),
                    onFinish: () => console.log('Запрос завершён'),
                });
        },
    },


}
</script>

<template>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <Link :href="route('admin.reports.index')" class="text-blue-600 hover:underline">← Назад к списку</Link>
            </div>

            <!-- Информация о жалобе -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Жалоба #{{ report.id }}</h2>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="text-gray-500 text-sm">Отправитель</span>
                        <p>{{ report.reporter?.name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">На кого</span>
                        <p>{{ report.reported_user?.name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Тип</span>
                        <p>{{ report.type_label }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Статус</span>
                        <span class="px-2 py-1 text-xs rounded-full" :class="statusClass(report.status)">
                                {{ report.status_label }}
                            </span>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Дата</span>
                        <p>{{ formatDate(report.created_at) }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Контент</span>
                        <p>{{ report.reportable_type_label }}: <Link :href="report.reportable_url" target="_blank" class="text-blue-600">{{ report.reportable_title }}</Link></p>
                    </div>
                </div>

                <div>
                    <span class="text-gray-500 text-sm">Причина</span>
                    <p class="p-3 bg-gray-50 rounded">{{ report.reason }}</p>
                </div>

                <!-- Доказательства -->
                <div v-if="report.evidence_files?.length" class="mt-4">
                    <span class="text-gray-500 text-sm">Доказательства</span>
                    <div class="flex flex-wrap gap-2 mt-1">
                        <a v-for="file in report.evidence_files" :key="file.id" :href="file.url" target="_blank" class="text-blue-600 hover:underline">
                            📎 {{ file.original_name }}
                        </a>
                    </div>
                </div>

                <!-- Комментарий модератора, если есть -->
                <div v-if="report.moderator_comment" class="mt-4 p-3 bg-blue-50 rounded">
                    <span class="text-gray-500 text-sm">Комментарий модератора</span>
                    <p>{{ report.moderator_comment }}</p>
                </div>
            </div>

            <!-- Форма решения (только если жалоба активна) -->
            <div v-if="report.status === 'pending' || report.status === 'reviewing'" class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4">Принять решение</h3>
                <form @submit.prevent="submitResolution">
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center gap-2">
                                <input type="radio" v-model="form.action" value="create_violation" />
                                <span>Вынести наказание</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" v-model="form.action" value="reject" />
                                <span>Отклонить жалобу</span>
                            </label>
<!--                            <div v-if="form.errors.action" class="text-red-600 text-sm mt-1">-->
<!--                                {{ form.errors.action }}-->
<!--                            </div>-->
                        </div>

                        <div v-if="form.action === 'create_violation'" class="pl-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium">Тип нарушения</label>
                                <select v-model="form.violation_type" class="w-full border rounded p-2">
                                    <option value="">Выберите тип</option>
                                    <option v-for="t in violationTypes" :key="t.value" :value="t.value">
                                        {{ t.label }}
                                    </option>
                                </select>
                                <div v-if="form.errors.violation_type" class="text-red-600 text-sm">{{ form.errors.violation_type }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Причина нарушения</label>
                                <textarea v-model="form.violation_reason" rows="3" class="w-full border rounded p-2"></textarea>
                                <div v-if="form.errors.violation_reason" class="text-red-600 text-sm">{{ form.errors.violation_reason }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Тип наказания</label>
                                <select v-model="form.penalty_type" class="w-full border rounded p-2">
                                    <option value="">Выберите наказание</option>
                                    <option v-for="p in penaltyTypes" :key="p.value" :value="p.value">
                                        {{ p.label }}
                                    </option>
                                </select>
                                <div v-if="form.errors.penalty_type" class="text-red-600 text-sm">{{ form.errors.penalty_type }}</div>
                            </div>
                            <div v-if="form.penalty_type === 'temp_ban'">
                                <label class="block text-sm font-medium">Длительность (дней)</label>
                                <input type="number" v-model="form.duration_days" min="1" max="365" class="w-full border rounded p-2" />
                                <div v-if="form.errors.duration_days" class="text-red-600 text-sm">{{ form.errors.duration_days }}</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Комментарий модератора (необязательно)</label>
                            <textarea v-model="form.moderator_comment" rows="2" class="w-full border rounded p-2"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <Link :href="route('admin.reports.index')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Отмена</Link>
                        <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                            {{ form.processing ? 'Сохранение...' : 'Принять решение' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Если уже обработано -->
            <div v-else class="bg-gray-100 rounded-lg p-6 text-center text-gray-500">
                Жалоба уже обработана
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
