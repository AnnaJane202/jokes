<script>
import { router } from '@inertiajs/vue3';

export default {
    props: {
        postId: Number,
        parentId: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            content: '',
            loading: false,
        };
    },
    methods: {
        submit() {
            if (!this.content.trim()) return;
            this.loading = true;
            router.post(route('client.posts.comments.store', this.postId), {
                content: this.content,
                parent_id: this.parentId,
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.content = '';
                    this.loading = false;
                    this.$emit('added');
                },
                onError: (errors) => {
                    console.error(errors);
                    this.loading = false;
                },
            });
        },
    },
};
</script>

<template>
    <form @submit.prevent="submit" class="mb-6">
        <div class="flex items-start gap-3">
            <img :src="$page.props.auth.user.avatar" class="w-10 h-10 rounded-full" />
            <div class="flex-1">
                <textarea
                    v-model="content"
                    rows="3"
                    class="w-full border rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Напишите комментарий..."
                ></textarea>
                <div class="flex justify-end mt-2">
                    <button
                        type="submit"
                        :disabled="!content.trim() || loading"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ loading ? 'Отправка...' : 'Отправить' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>

<style scoped>

</style>
