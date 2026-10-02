<script>

import UserProfileLayout from '@/Layouts/UserProfileLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    layout: UserProfileLayout,
    components: { UserProfileLayout, DeleteUserForm, },
    props: {
        user: Object,
    },
    data() {
        return {
            form: useForm({
                name: this.user.name,
                email: this.user.email,
                current_password: '',
                new_password: '',
                new_password_confirmation: '',
                avatar: null,
            }),
        };
    },
    methods: {
        onAvatarChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.form.avatar = file;
                // Автоматическая загрузка аватара
                this.uploadAvatar();
            }
        },
        uploadAvatar() {
            if (!this.form.avatar) return;

            const formData = new FormData();
            formData.append('avatar', this.form.avatar);

            axios.post(route('client.profile.avatar.update'), formData, {
                headers: {'Content-Type': 'multipart/form-data'},
            })
                .then(() => {
                    this.$inertia.reload({only: ['user']});
                    alert('Аватар обновлён');
                })
                .catch((error) => {
                    console.error(error);
                    alert('Ошибка загрузки аватара');
                });
        },
        submit() {
            this.form.patch(route('client.profile.update'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.form.current_password = '';
                    this.form.new_password = '';
                    this.form.new_password_confirmation = '';
                },
            });
        },
    }
}

</script>

<template>
    <div class="w-full max-w-2xl mx-auto py-6">
        <form @submit.prevent="submit" class="bg-white rounded-lg shadow p-6 space-y-4">
            <!-- Аватар -->
            <div>
                <label class="block text-sm font-medium">Аватар</label>
                <div class="flex items-center gap-4 mt-1">
                    <img :src="user.avatar" class="w-16 h-16 rounded-full" />
                    <input type="file" @change="onAvatarChange" accept="image/*" />
                </div>
            </div>

            <!-- Имя -->
            <div>
                <label class="block text-sm font-medium">Имя</label>
                <input v-model="form.name" type="text" class="w-full border rounded p-2" />
                <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input v-model="form.email" type="email" class="w-full border rounded p-2" />
                <div v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</div>
            </div>

            <!-- Смена пароля -->
            <div>
                <label class="block text-sm font-medium">Текущий пароль (если меняете)</label>
                <input v-model="form.current_password" type="password" class="w-full border rounded p-2" />
                <div v-if="form.errors.current_password" class="text-red-600 text-sm">{{ form.errors.current_password }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium">Новый пароль</label>
                <input v-model="form.new_password" type="password" class="w-full border rounded p-2" />
                <div v-if="form.errors.new_password" class="text-red-600 text-sm">{{ form.errors.new_password }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium">Подтверждение пароля</label>
                <input v-model="form.new_password_confirmation" type="password" class="w-full border rounded p-2" />
            </div>

            <!-- Кнопка -->
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" :disabled="form.processing">
                {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
            </button>
        </form>
    </div>

</template>

<style scoped>

</style>
