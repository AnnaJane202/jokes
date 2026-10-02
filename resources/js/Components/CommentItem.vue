<script>
import { router } from '@inertiajs/vue3';
import CommentForm from '@/Components/CommentForm.vue';
import ReportMenuButton from "@/Components/ReportMenuButton.vue";

export default {
    components: { CommentForm, ReportMenuButton },
    props: {
        comment: {
            type: Object,
            required: true,
        },
        postId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            showReplyForm: false,
            editMode: false,
            editContent: this.comment.content,
        };
    },
    computed: {
        canEdit() {
            return this.$page.props.auth.user.id === this.comment.user.id;
        },
        canDelete() {
            return this.$page.props.auth.user.id === this.comment.user.id || this.$page.props.auth.user.is_admin;
        },
    },
    created() {
        // Гарантируем, что children всегда массив
        if (!Array.isArray(this.comment.children)) {
            this.comment.children = [];
        }
    },
    methods: {
        handleReplyAdded() {
            this.showReplyForm = false;
            this.$emit('reply-added'); // без аргументов
        },
        update() {
            if (!this.editContent.trim()) return;
            router.put(route('client.posts.comments.update', { post: this.postId, comment: this.comment.id }), {
                content: this.editContent,
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.editMode = false;
                    this.$emit('updated', { ...this.comment, content: this.editContent });
                },
            });
        },
        destroy() {
            if (confirm('Удалить комментарий?')) {
                router.delete(route('client.posts.comments.destroy', { post: this.postId, comment: this.comment.id }), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$emit('deleted', this.comment.id);
                    },
                });
            }
        },
        removeChild(childId) {
            this.comment.children = this.comment.children.filter(c => c.id !== childId);
        },
        updateChild(updated) {
            const idx = this.comment.children.findIndex(c => c.id === updated.id);
            if (idx !== -1) this.comment.children[idx] = updated;
        },
    },
};
</script>

<template>
    <div class="flex gap-3">
        <img :src="comment.user.avatar" class="w-8 h-8 rounded-full" />
        <div class="flex-1">
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-medium text-sm">{{ comment.user.name }}</span>
                    <span class="text-xs text-gray-500">{{ comment.created_at }}</span>
                    <ReportMenuButton type="comment" :id="comment.id" class="ml-auto" />
                </div>
                <p class="text-gray-700 text-sm">{{ comment.content }}</p>
            </div>
            <div class="flex items-center gap-4 mt-1 text-xs text-gray-500">
                <button @click="showReplyForm = !showReplyForm" class="hover:text-blue-600">
                    Ответить
                </button>
                <button v-if="canEdit" @click="editMode = !editMode" class="hover:text-blue-600">
                    Редактировать
                </button>
                <button v-if="canDelete" @click="destroy" class="hover:text-red-600">
                    Удалить
                </button>
            </div>

            <!-- Форма ответа -->
            <div v-if="showReplyForm" class="mt-3 pl-6">
                <CommentForm
                    :post-id="postId"
                    :parent-id="comment.id"
                    @added="handleReplyAdded"
                />
            </div>

            <!-- Редактирование -->
            <div v-if="editMode" class="mt-3">
                <form @submit.prevent="update">
                    <textarea v-model="editContent" rows="2" class="w-full border rounded p-2 text-sm"></textarea>
                    <div class="flex gap-2 mt-1">
                        <button type="submit" class="text-sm bg-blue-600 text-white px-3 py-1 rounded">Сохранить</button>
                        <button @click="editMode = false" class="text-sm bg-gray-300 px-3 py-1 rounded">Отмена</button>
                    </div>
                </form>
            </div>

            <!-- Дочерние комментарии -->
            <div v-if="comment.children && comment.children.length" class="mt-3 space-y-3 pl-6">
                <CommentItem
                    v-for="child in comment.children"
                    :key="child.id"
                    :comment="child"
                    :post-id="postId"
                    @deleted="removeChild"
                    @updated="updateChild"
                    @reply-added="handleReplyAdded"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
