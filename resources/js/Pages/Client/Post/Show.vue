<script>

import MainLayout from '@/Layouts/MainLayout.vue';
import CommentForm from '@/Components/CommentForm.vue';
import CommentItem from '@/Components/CommentItem.vue';
import { router } from '@inertiajs/vue3';

export default {
    layout: MainLayout,
    components: { MainLayout, CommentForm, CommentItem },
    props: {
        post: Object,
        comments: Array,
    },
    methods: {
        addComment(comment) {
            // this.comments.push(comment);

            router.reload({ only: ['comments'] });
        },
        addReply(parentId, reply) {
            // const parent = this.findComment(this.comments, parentId);
            // if (parent) {
            //     if (!Array.isArray(parent.children)) {
            //         parent.children = [];
            //     }
            //     parent.children.push(reply);
            // }

            router.reload({ only: ['comments'] });
        },
        updateComment(updated) {
            this.findAndUpdate(this.comments, updated);
        },
        removeComment(id) {
            this.comments = this.comments.filter(c => c.id !== id);
        },
        findComment(list, id) {
            for (let c of list) {
                if (c.id === id) return c;
                if (c.children) {
                    const found = this.findComment(c.children, id);
                    if (found) return found;
                }
            }
            return null;
        },
        findAndUpdate(list, updated) {
            for (let i = 0; i < list.length; i++) {
                if (list[i].id === updated.id) {
                    list[i] = updated;
                    return;
                }
                if (list[i].children) {
                    this.findAndUpdate(list[i].children, updated);
                }
            }
        },
    },
};
</script>

<template>
    <div class="w-full max-w-4xl mx-auto py-6">
        <!-- Пост -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h1 class="text-2xl font-bold mb-2">{{ post.title }}</h1>
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <span>{{ post.user.name }}</span>
                <span class="mx-2">•</span>
                <span>{{ post.created_at }}</span>
                <span class="mx-2">•</span>
                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ post.category[0]?.title }}</span>
            </div>
            <p class="text-gray-700">{{ post.content }}</p>
        </div>

        <!-- Комментарии -->
        <div>
            <h2 class="text-xl font-bold mb-4">Комментарии ({{ comments.length }})</h2>

            <!-- Форма добавления -->
            <CommentForm :post-id="post.id" @added="addComment" />

            <!-- Список комментариев -->
            <div v-if="comments && comments.length" class="space-y-4 mt-6">
                <CommentItem
                    v-for="comment in comments"
                    :key="comment.id"
                    :comment="comment"
                    :post-id="post.id"
                    @deleted="removeComment"
                    @updated="updateComment"
                    @reply-added="addReply"
                />
            </div>
            <div v-else class="text-gray-500 mt-4">
                Нет комментариев. Будьте первым!
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
