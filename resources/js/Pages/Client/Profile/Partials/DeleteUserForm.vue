<script>
import { useForm } from '@inertiajs/vue3';

export default {
    name: 'DeleteUserForm',
    data() {
        return {
            confirmingUserDeletion: false,
            form: useForm({
                password: '',
            }),
        };
    },
    methods: {
        startConfirmingUserDeletion() {
            this.confirmingUserDeletion = true;
        },
        stopConfirmingUserDeletion() {
            this.confirmingUserDeletion = false;
            this.form.reset();
        },
        deleteUser() {
            this.form.delete(route('client.profile.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.stopConfirmingUserDeletion();
                },
                onError: () => {
                    // Ошибки валидации отобразятся в form.errors
                },
                onFinish: () => {
                    this.form.reset();
                },
            });
        },
    },
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Удаление аккаунта</h2>
            <p class="mt-1 text-sm text-gray-600">
                После удаления аккаунта все его данные будут безвозвратно удалены.
                Перед удалением сохраните важную информацию.
            </p>
        </header>

        <button
            @click="startConfirmingUserDeletion"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
        >
            Удалить аккаунт
        </button>

        <!-- Модальное окно подтверждения -->
        <div
            v-if="confirmingUserDeletion"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="stopConfirmingUserDeletion"
        >
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Вы уверены, что хотите удалить аккаунт?
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Это действие необратимо. Введите пароль для подтверждения.
                </p>

                <form @submit.prevent="deleteUser">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Пароль
                        </label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-red-500"
                            placeholder="Введите ваш пароль"
                            autocomplete="current-password"
                        />
                        <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="stopConfirmingUserDeletion"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                        >
                            Отмена
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Удаление...' : 'Удалить аккаунт' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
