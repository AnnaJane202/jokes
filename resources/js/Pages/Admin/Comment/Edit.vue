<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
export default {
    name: 'Edit',
    layout: AdminLayout,

    components: { AdminLayout, Link },
    props: {
        comment: Object,
    },
    setup(props) {
        const form = useForm({
            content: props.comment.content,
        });

        function submit() {
            form.put(route('admin.comments.update', props.comment.id));
        }

        return { form, submit };
    },

}
</script>

<template>
    <div class="p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Редактирование комментария #{{ comment.id }}</h1>

        <form @submit.prevent="submit" class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium">Пользователь</label>
                <span class="block mt-1">{{ comment.user?.name }}</span>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Пост</label>
                <Link :href="route('admin.posts.show', comment.post_id)" class="text-blue-600 hover:underline">
                    Пост #{{ comment.post_id }}
                </Link>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Содержание</label>
                <textarea v-model="form.content" rows="5" class="w-full border rounded p-2"></textarea>
                <div v-if="form.errors.content" class="text-red-600 text-sm">{{ form.errors.content }}</div>
            </div>

            <div class="flex gap-3">
                <Link :href="route('admin.comments.index')" class="px-4 py-2 bg-gray-200 rounded">Отмена</Link>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                    Сохранить
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
