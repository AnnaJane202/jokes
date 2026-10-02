<script>
import {Link} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import LikeButton from "@/Components/LikeButton.vue";
import ReportMenuButton from "@/Components/ReportMenuButton.vue";
import Pagination from "@/Components/Pagination.vue";

export default {
    name: 'Index',
    layout: MainLayout,

    props: {
       categories: Array,
        posts: Object,
        recommendedUsers: Array,
        topAuthors: Array,
        type: String,
        // id: Number,
    },


    components: {
        Pagination,
        Link,
        LikeButton,
        ReportMenuButton,

    },

    data() {
        return {
            isOpen: false,
        }
    },

    methods: {
        toggleMenu() {
            this.isOpen = !this.isOpen
            console.log(this.isOpen)
        },
        closeMenu(event) {
            // Проверяем, что клик был вне компонента
            if (this.$refs.menuContainer && !this.$refs.menuContainer.contains(event.target)) {
                this.isOpen = false
            }
        },
        // reportPost(postId) {
        //     this.$inertia.get(route('reports.create', ['post', postId]))
        // }
    },

    mounted() {
        document.addEventListener('click', this.closeMenu)
    },
    beforeUnmount() {
        document.removeEventListener('click', this.closeMenu)
    },
}
</script>

<template>
    <!-- Боковая панель -->
    <aside class="w-full md:w-1/4 mb-6 md:mb-0 md:pr-6 space-y-6">
        <!-- Меню категорий -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Категории</h2>
            <ul v-for="category in categories" class="space-y-2">
<!--                <li><a href="#" class="text-gray-700 hover:text-blue-600 transition flex items-center"><i class="fas fa-code mr-2"></i>{{ category.title }}</a></li>-->
                <Link
                    :href="route('client.categories.show', category.slug)"
                    class="text-gray-700 hover:text-blue-600 transition flex items-center"
                >
                    <i class="fas fa-code mr-2"></i>{{ category.title }}
                </Link>

            </ul>
        </div>

        <!-- Лучшие авторы -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Лучшие авторы</h2>
            <div class="space-y-4">
                <!-- Автор 1 -->
                <div v-for="author in topAuthors" class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img :src="author.avatar" class="w-10 h-10 rounded-full mr-1" />

<!--                        <img src="https://i.pravatar.cc/40?img=5" alt="Аватар автора" class="rounded-full mr-3">-->
                        <div>
                            <p class="font-medium">{{ author.name }}</p>
                            <p class="text-sm text-gray-500">{{ author.posts_count }}</p>
                        </div>
                    </div>
<!--                    <div class="flex items-center text-red-500">-->
<!--                        <i class="fas fa-heart mr-1"></i>-->
<!--                        <span>1.2K</span>-->
<!--                    </div>-->
                </div>

            </div>
        </div>
        <!-- Рекомендуемые пользователи -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold text-gray-800">👥 Пользователи</h2>
                <Link :href="route('client.users.index')" class="text-sm text-blue-600 hover:underline">
                    Все
                </Link>
            </div>
            <div v-if="recommendedUsers && recommendedUsers.length" class="space-y-3">
                <div v-for="user in recommendedUsers" :key="user.id"
                     class="flex items-center justify-between">
                    <Link :href="route('client.users.show', user.id)" class="flex items-center gap-2">
                        <img :src="user.avatar" class="w-8 h-8 rounded-full" />
                        <span class="text-sm font-medium">{{ user.name }}</span>
                    </Link>
                    <ReportMenuButton type="user" :id="user.id" />
                </div>
            </div>
            <div v-else class="text-gray-400 text-sm">
                Нет пользователей для отображения
            </div>
        </div>
    </aside>

    <!-- Посты в колонку -->
    <section class="w-full md:w-3/4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Последние посты</h2>
<!--        <pre>{{ JSON.stringify(posts, null, 2) }}</pre>-->
        <div v-for="post in posts.data" class="space-y-6">
            <!-- Пост 1 -->
            <div class="bg-white rounded-lg shadow overflow-hidden transition-transform hover:scale-[1.01]">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
<!--                        <h3 class="text-xl font-bold text-gray-800">Как начать изучать React в 2023 году</h3>-->
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ post.category[0].title}}</span>
                        <ReportMenuButton :type="type" :id="post.id" />

                    </div>
                    <p class="text-gray-600 mb-4">{{ post.content }}</p>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <img :src="post.user.avatar" class="w-10 h-10 rounded-full mr-1" />
<!--                            <img src="https://i.pravatar.cc/40?img=1" alt="Аватар автора" class="rounded-full mr-2">-->
                            <span class="text-gray-700">{{ post.user.name }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
<!--                            <div class="flex items-center mr-4">-->
<!--                                <button class="flex items-center hover:text-red-500 transition">-->
<!--                                    <i class="far fa-heart mr-1"></i>-->
<!--                                    <span>24</span>-->
<!--                                </button>-->
<!--                            </div>-->

                            <like-button
                                :post="post"
                                :initial-is-liked="post.is_liked_by_user"
                                :initial-likes-count="post.likes_count"

                            />


<!--                            <div class="flex items-center mr-4">-->
<!--                                <i class="far fa-comment mr-1"></i>-->
<!--                                <span>8</span>-->
<!--                            </div>-->
                            <div class="text-sm text-gray-500">
                                <i class="far fa-clock mr-1"></i>
                                <span>{{ post.updated_at }}</span>
                            </div>



                            <!-- Кнопка "Пожаловаться" -->
                            <!-- Выпадающее меню (три точки) -->
<!--                            <div class="relative" @click.stop>-->
<!--                                <button @click="toggleMenu(post.id)" class="text-gray-400 hover:text-gray-600">-->
<!--                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">-->
<!--                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />-->
<!--                                    </svg>-->
<!--                                </button>-->


<!--                            </div>-->
                        </div>
                    </div>
                    <div class="flex justify-end items-end mb-4">
                        <Link :href="route('client.posts.show', post.id)" class="flex items-center text-gray-600 hover:text-blue-600">
                            <i class="far fa-comment mr-1"></i>
                            <span>Комментарии: {{ post.comments_count }}</span>
                        </Link>
                    </div>
                </div>
            </div>

        </div>

        <!-- Пагинация -->
        <div class="px-6 py-4 border-t">
            <Pagination :links="posts.links" />
        </div>
    </section>
</template>

<style scoped>

</style>
