<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3'

export default {
    name: 'Review',

    layout: AdminLayout,

    props: {
        userViolations: Array,
        violation: Object,
        repeatCount: Number,
        penaltyOptions: Array,
    },

    data() {
        return {
            form: useForm({
                decision: 'reject',
                new_penalty_type: 'warning',
                new_duration_days: 7,
                moderator_comment: '',

            })
        }
    },

    methods: {
        submitReview() {
            this.form.post(route('admin.appeals.process-review', this.violation.id));
        }
    },

    components: {
        Link,
    },


    watch: {
        'form.new_duration_days'(value) {
            // Автоматически ограничиваем значение
            if (value > 365) {
                this.form.new_duration_days = 365
            }
            if (value < 1) {
                this.form.new_duration_days = 1
            }
        }
    }
}
</script>

<template>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Хлебные крошки -->
            <div class="mb-4">
                <Link :href="route('admin.violations.index')" class="text-blue-600 hover:underline">
                    ← Назад к списку
                </Link>
            </div>

            <!-- Информация о нарушении -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <h2 class="text-xl font-bold mb-4">📋 Информация о нарушении</h2>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Тип нарушения</p>
                        <p class="font-medium">{{ violation.type_label }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Текущее наказание</p>
                        <p class="font-medium text-red-600">{{ violation.penalty_label }}</p>
                        <p v-if="violation.duration_days" class="text-sm">
                            Срок: {{ violation.duration_days }} дней
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Дата нарушения</p>
                        <p>{{ violation.created_at }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Окончание блокировки</p>
                        <p>{{ violation.active_until || '—' }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Причина нарушения</p>
                    <p class="mt-1 p-3 bg-gray-50 rounded">{{ violation.reason }}</p>
                </div>
            </div>

            <!-- Информация о пользователе -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <h2 class="text-xl font-bold mb-4">👤 Информация о пользователе</h2>

                <div class="flex items-start gap-6">
<!--                    <img :src="violation.user.avatar" class="h-16 w-16 rounded-full" v-if="violation.user.avatar">-->
                    <div>
                        <p><strong>Имя:</strong> {{ violation.user.name }}</p>
                        <p><strong>Email:</strong> {{ violation.user.email }}</p>
                        <p><strong>Регистрация:</strong> {{ violation.user.registered_at }}</p>
                        <p><strong>Всего нарушений:</strong> {{ violation.user.total_violations }}</p>
                    </div>
                </div>

                <!-- История нарушений -->
                <div v-if="userViolations.length" class="mt-4">
                    <h3 class="font-medium mb-2">История нарушений (последние 10)</h3>
                    <div class="max-h-48 overflow-y-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-1 text-left">Дата</th>
                                <th class="px-3 py-1 text-left">Тип</th>
                                <th class="px-3 py-1 text-left">Наказание</th>
                                <th class="px-3 py-1 text-left">Статус</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="v in userViolations" :key="v.id">
                                <td class="px-3 py-1">{{ v.created_at }}</td>
                                <td class="px-3 py-1">{{ v.type_label }}</td>
                                <td class="px-3 py-1">{{ v.penalty_label }}</td>
                                <td class="px-3 py-1">
                      <span :class="{
                        'text-red-600': v.status === 'active',
                        'text-green-600': v.status === 'pardoned'
                      }">
                        {{ v.status }}
                      </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-if="repeatCount > 0" class="mt-2 text-sm text-orange-600">
                        ⚠️ Повторных нарушений этого типа: {{ repeatCount }}
                    </p>
                </div>
            </div>

            <!-- Апелляция пользователя -->
            <div class="bg-yellow-50 rounded-lg shadow mb-6 p-6 border border-yellow-200">
                <h2 class="text-xl font-bold mb-4">⚖️ Апелляция пользователя</h2>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Причина апелляции</p>
                    <p class="mt-1 p-3 bg-white rounded">{{ violation.appeal.reason }}</p>
                </div>

                <div v-if="violation.appeal.evidence?.length" class="mb-4">
                    <p class="text-sm text-gray-600">Доказательства</p>
                    <div class="mt-2 space-y-1">
                        <a
                            v-for="(evidence, idx) in violation.appeal.evidence"
                            :key="idx"
                            :href="evidence.url"
                            target="_blank"
                            class="block text-blue-600 hover:underline"
                        >
                            📎 Ссылка
                        </a>
                    </div>
<!--                    <div class="grid grid-cols-3 gap-4">-->
<!--                        <a-->
<!--                            v-for="evidence in violation.appeal.evidence"-->
<!--                            :key="evidence.id"-->
<!--                            :href="evidence.url"-->
<!--                            target="_blank"-->
<!--                            class="block border rounded overflow-hidden hover:shadow-md transition"-->
<!--                        >-->
<!--                            <img-->
<!--                                v-if="isImage(evidence.mime_type)"-->
<!--                                :src="evidence.url"-->
<!--                                :alt="evidence.original_name"-->
<!--                                class="w-full h-32 object-cover"-->
<!--                            >-->
<!--                            <div v-else class="flex items-center justify-center h-32 bg-gray-100">-->
<!--                                <span class="text-4xl">📄</span>-->
<!--                            </div>-->
<!--                            <div class="p-2 text-xs text-gray-600 truncate">-->
<!--                                {{ evidence.original_name }}-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </div>-->
                </div>

                <div>
                    <p class="text-sm text-gray-600">Дата подачи</p>
                    <p>{{ violation.appeal.submitted_at }}</p>
                </div>
            </div>

             Форма принятия решения
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">📝 Принять решение</h2>

                <form @submit.prevent="submitReview">
                    <!-- Варианты решений -->
                    <div class="space-y-4 mb-6">
                        <label class="flex items-start">
                            <input type="radio" v-model="form.decision" value="approve" class="mt-1">
                            <div class="ml-3">
                                <span class="font-medium text-green-700">Одобрить апелляцию</span>
                                <p class="text-sm text-gray-600">Наказание будет полностью снято, аккаунт восстановлен</p>
                            </div>
                        </label>

                        <label class="flex items-start">
                            <input type="radio" v-model="form.decision" value="modify" class="mt-1">
                            <div class="ml-3">
                                <span class="font-medium text-orange-700">Смягчить наказание</span>
                                <p class="text-sm text-gray-600">Изменить тип или срок наказания</p>
                            </div>
                        </label>

                        <label class="flex items-start">
                            <input type="radio" v-model="form.decision" value="reject" class="mt-1">
                            <div class="ml-3">
                                <span class="font-medium text-red-700">Отклонить апелляцию</span>
                                <p class="text-sm text-gray-600">Наказание останется без изменений</p>
                            </div>
                        </label>
                    </div>

                    <!-- Настройки наказания (при смягчении) -->
                    <div v-if="form.decision === 'modify'" class="ml-6 mb-6 p-4 bg-gray-50 rounded">
                        <h3 class="font-medium mb-3">Новое наказание</h3>

                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Тип наказания</label>
                            <select
                                v-model="form.new_penalty_type"
                                class="border rounded-md p-2 w-full"
                            >
                                <option
                                    v-for="option in penaltyOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>

<!--                        <div v-if="form.new_penalty_type === 'temp_ban'" class="mb-3">-->
<!--                            <label class="block text-sm font-medium mb-1">Длительность (дней)</label>-->
<!--                            <input type="number" v-model="form.new_duration_days" min="1" max="365" class="border rounded-md p-2 w-full">-->
<!--                        </div>-->

                        <div v-if="form.new_penalty_type === 'temp_ban'" class="mb-3">
                            <label class="block text-sm font-medium mb-1">
                                Длительность (дней)
                                <span class="text-xs text-gray-500">(от 1 до 365)</span>
                            </label>
                            <input
                                type="number"
                                v-model="form.new_duration_days"
                                min="1"
                                max="365"
                                class="border rounded-md p-2 w-full"
                                :class="{ 'border-red-500': form.errors.new_duration_days }"
                            >
                            <p v-if="form.errors.new_duration_days" class="text-red-500 text-sm mt-1">
                                {{ form.errors.new_duration_days }}
                            </p>
                            <p v-else-if="form.new_duration_days > 365" class="text-yellow-500 text-sm mt-1">
                                ⚠️ Максимальный срок блокировки — 365 дней
                            </p>
                        </div>
                    </div>

                    <!-- Комментарий -->
                    <div class="mb-6">
                        <label class="block font-medium mb-2">Комментарий для пользователя (необязательно)</label>
                        <textarea v-model="form.moderator_comment" rows="4" class="w-full border rounded-md p-2"
                                  placeholder="Объясните пользователю причину вашего решения..."></textarea>
                    </div>

                    <!-- Уведомление -->
<!--                    <div class="mb-6">-->
<!--                        <label class="flex items-center">-->
<!--                            <input type="checkbox" v-model="form.notify_user" class="rounded">-->
<!--                            <span class="ml-2 text-sm">Отправить уведомление пользователю</span>-->
<!--                        </label>-->
<!--                    </div>-->

                    <!-- Кнопки -->
                    <div class="flex justify-end gap-3">
                        <Link :href="route('admin.violations.index')" class="px-4 py-2 border rounded-md hover:bg-gray-50">
                            Отмена
                        </Link>
                        <button type="submit" :disabled="form.processing"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50">
                            {{ form.processing ? 'Сохранение...' : 'Принять решение' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
