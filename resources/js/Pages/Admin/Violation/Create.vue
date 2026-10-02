<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

export default {
    name: 'Create',

    layout: AdminLayout,

    components: {
        Link,
    },

    props: {
        violationTypes: {
            type: Object,
            required: true,
        },
        penaltyTypes: {
            type: Object,
            required: true,
        },
        user: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            form: useForm({
                user_id: this.user.id,
                type: null,
                penalty_type: null,
                duration_days: null,
                reason: '',
                details: {
                    moderator_note: '',
                },
            }),
        };
    },

    computed: {
        // Показывать ли поле длительности
        showDuration() {
            return this.form.penalty_type === 'temp_ban';
        },

        // Автоматически подставляем reason из выбранного типа
        selectedTypeLabel() {
            if (!this.form.type) return '';
            return this.violationTypes[this.form.type] || '';
        },
    },

    methods: {
        submit() {
            // Валидация на клиенте
            if (!this.form.type) {
                alert('Выберите тип нарушения');
                return;
            }
            if (!this.form.penalty_type) {
                alert('Выберите наказание');
                return;
            }
            if (this.showDuration && !this.form.duration_days) {
                alert('Укажите длительность блокировки');
                return;
            }

            // Автоматически заполняем reason
            this.form.reason = this.violationTypes[this.form.type] || this.form.type;

            // Отправляем через Inertia
            this.form.post(route('admin.violations.store'), {
                onSuccess: () => {
                    // Inertia обработает редирект из контроллера
                },
                onError: (errors) => {
                    console.error('Ошибки валидации:', errors);
                },
            });
        },
    },
};
</script>

<template>
    <main class="w-full lg:w-5/6 flex flex-col">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden flex-1 flex flex-col">
            <!-- Заголовок -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">
                        Вынесение нарушения
                    </h2>
                    <Link
                        :href="route('admin.users.show', user.id)"
                        class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm"
                    >
                        ← Назад к пользователю
                    </Link>
                </div>
            </div>

            <!-- Форма -->
            <div class="flex-1 overflow-auto">
                <div class="max-w-4xl mx-auto p-4 sm:p-6">

                    <!-- Информация о пользователе -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Информация о пользователе
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Имя</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ user.name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">ID</p>
                                    <p class="text-lg font-mono text-gray-900">{{ user.id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="text-gray-900">{{ user.email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Дата регистрации</p>
                                    <p class="text-gray-900">{{ user.created_at }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Тип нарушения -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Тип нарушения <span class="text-red-500">*</span>
                        </h3>
                        <select
                            v-model="form.type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        >
                            <option :value="null" disabled>Выберите тип нарушения</option>
                            <option
                                v-for="(label, value) in violationTypes"
                                :key="value"
                                :value="value"
                            >
                                {{ label }}
                            </option>
                        </select>
                        <p v-if="form.errors.type" class="text-red-500 text-sm mt-1">
                            {{ form.errors.type }}
                        </p>
                    </div>

                    <!-- Детальное описание -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Детальное описание нарушения <span class="text-red-500">*</span>
                        </h3>
                        <textarea
                            v-model="form.details.moderator_note"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-vertical"
                            placeholder="Подробно опишите, в чём заключается нарушение, приведите цитаты, укажите контекст..."
                        ></textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            Это описание будет добавлено в историю нарушений пользователя.
                        </p>
                        <p v-if="form.errors['details.moderator_note']" class="text-red-500 text-sm mt-1">
                            {{ form.errors['details.moderator_note'] }}
                        </p>
                    </div>

                    <!-- Наказание -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Применяемое наказание <span class="text-red-500">*</span>
                        </h3>

                        <div class="space-y-3">
                            <label
                                v-for="(label, value) in penaltyTypes"
                                :key="value"
                                class="flex items-center p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition"
                                :class="{ 'ring-2 ring-orange-500 bg-orange-50': form.penalty_type === value }"
                            >
                                <input
                                    type="radio"
                                    :value="value"
                                    v-model="form.penalty_type"
                                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300"
                                >
                                <span class="ml-3 text-sm font-medium text-gray-900">
                                    {{ label }}
                                </span>
                            </label>
                        </div>
                        <p v-if="form.errors.penalty_type" class="text-red-500 text-sm mt-1">
                            {{ form.errors.penalty_type }}
                        </p>
                    </div>

                    <!-- Длительность (только для temp_ban) -->
                    <div v-if="showDuration" class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Длительность блокировки (дней) <span class="text-red-500">*</span>
                        </h3>
                        <input
                            v-model="form.duration_days"
                            type="number"
                            min="1"
                            max="365"
                            class="w-full max-w-xs px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            placeholder="7"
                        >
                        <p class="mt-1 text-sm text-gray-500">От 1 до 365 дней</p>
                        <p v-if="form.errors.duration_days" class="text-red-500 text-sm mt-1">
                            {{ form.errors.duration_days }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- Кнопки -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                    <Link
                        :href="route('admin.users.show', user.id)"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm text-center"
                    >
                        Отмена
                    </Link>
                    <button
                        type="button"
                        @click="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition text-sm disabled:opacity-50"
                    >
                        {{ form.processing ? 'Сохранение...' : '⚠️ Вынести нарушение' }}
                    </button>
                </div>
            </div>
        </div>
    </main>
</template>

<style scoped>

</style>
