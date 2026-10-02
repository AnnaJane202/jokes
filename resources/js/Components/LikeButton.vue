<script>
export default {
    name: 'LikeButton',

    props: {
        post: Object,
        initialIsLiked: {
            type: Boolean,
            default: false
        },
        initialLikesCount: {
            type: Number,
            default: 0
        }
    },

    data() {
        return {
            isLiked: this.initialIsLiked,
            likesCount: this.initialLikesCount,
            processing: false,
            showAuthMessage: false,
        }
    },

    computed: {
        // Делаем auth вычисляемым свойством
        auth() {
            return this.$page.props.auth
        },

        // Проверка авторизации
        isAuthenticated() {
            return !!this.$page.props.auth?.user
        }
    },

    methods: {
        toggleLike() {
            if (!this.isAuthenticated) {
                this.showAuthMessage = true
                return
            }

            if (this.processing) return;

            this.processing = true;
            axios.post(route('posts.like.toggle', this.post.id))
                .then ( res => {
                    console.log(res);

                    this.isLiked = !this.isLiked;
                    this.likesCount = this.isLiked ? this.likesCount + 1 : this.likesCount - 1;

                    this.processing = false;
                })
        }
    }
}
</script>

<template>
    <div class="like-wrapper">
        <button
            @click="toggleLike"
            :class="['like-btn', { 'liked': isLiked }]"
            :disabled="processing"
        >
            <span class="like-icon">❤️</span>
            <span class="likes-count">{{ likesCount }}</span>
        </button>

        <!-- Уведомление для неавторизованных -->

    </div>
    <div v-if="showAuthMessage" class="auth-message">
    Чтобы поставить лайк, пожалуйста, <a :href="route('login')" class="text-sky-600">войдите</a>
</div>
</template>

<style scoped>
.like-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    //border: 1px solid #ddd;
    border-radius: 20px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.like-btn:hover {
    background: #f5f5f5;
}

.like-btn.liked {
    background: #fff0f0;
    border-color: #ff6b6b;
    color: #ff6b6b;
}

.like-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.like-icon {
    font-size: 16px;
}

.likes-count {
    font-size: 14px;
    font-weight: 500;
}
</style>
