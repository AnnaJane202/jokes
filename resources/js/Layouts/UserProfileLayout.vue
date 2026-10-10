<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link } from '@inertiajs/vue3';

export default {
    name: 'UserProfileLayout',
    layout: MainLayout,
    components: { MainLayout, Link },
    computed: {
        currentRoute() {
            return this.$page.url;
        },
        menuItems() {
            return [
                {
                    label: 'Профиль',
                    icon: '👤',
                    route: 'client.profile.dashboard',
                    pattern: /^\/profile$/,
                },
                {
                    label: 'Мои посты',
                    icon: '📝',
                    route: 'client.profile.posts.index', // ← новый пункт
                    pattern: /^\/profile\/posts/,
                },
                {
                    label: 'Мои нарушения',
                    icon: '📋',
                    route: 'client.violations.index',
                    pattern: /^\/profile\/violations/,
                },
                {
                    label: 'Мои апелляции',
                    icon: '⚖️',
                    route: 'client.appeals.index',
                    pattern: /^\/profile\/appeals/,
                },
                {
                    label: 'Мои жалобы',
                    icon: '📢',
                    route: 'client.reports.index',
                    pattern: /^\/profile\/reports/,
                },
                // {
                //     label: 'Уведомления',
                //     icon: '🔔',
                //     route: 'client.profile.notifications.index',
                //     pattern: /^\/profile\/notifications/,
                // },
            ];
        },
    },
    methods: {
        isActive(item) {
            return item.pattern.test(this.currentRoute);
        },
    },
};
</script>

<template>
    <MainLayout>
        <div class="w-full max-w-7xl mx-auto py-6">
            <div class="flex flex-col md:flex-row gap-6">
                <aside class="w-full md:w-64 flex-shrink-0">
                    <nav class="bg-white rounded-lg shadow p-3 space-y-1">
                        <Link
                            v-for="item in menuItems"
                            :key="item.route"
                            :href="route(item.route)"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition"
                            :class="{
                                'bg-blue-600 text-white': isActive(item),
                                'text-gray-700 hover:bg-gray-100': !isActive(item),
                            }"
                        >
                            <span>{{ item.icon }}</span>
                            <span>{{ item.label }}</span>
                        </Link>
                    </nav>
                </aside>

                <div class="flex-1 min-w-0">
                    <slot />
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>

</style>
