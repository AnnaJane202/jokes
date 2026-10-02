<script>
import UserProfileLayout from "@/Layouts/UserProfileLayout.vue";
import { Link } from '@inertiajs/vue3';

export default {
    name: 'Dashboard',
    layout: UserProfileLayout,
    components: { Link },
    props: {
        user: Object,
        stats: Object,
    },

    mounted() {
        console.log('Layout:', this.$options.layout?.name);
    }
};
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Личный кабинет</h1>

        <div class="flex items-center gap-4 mb-6">
            <img :src="user.avatar" class="w-20 h-20 rounded-full border" />
            <div>
                <h2 class="text-xl font-semibold">{{ user.name }}</h2>
                <p class="text-gray-500 text-sm">Зарегистрирован: {{ user.created_at }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <Link
                :href="route('client.violations.index')"
                class="bg-red-50 border border-red-200 rounded-lg p-4 hover:bg-red-100 transition"
            >
                <div class="text-2xl font-bold text-red-600">{{ stats.violations }}</div>
                <div class="text-sm text-gray-600">Нарушений</div>
            </Link>

<!--            <Link-->
<!--                :href="route('client.appeals')"-->
<!--                class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 hover:bg-yellow-100 transition"-->
<!--            >-->
<!--                <div class="text-2xl font-bold text-yellow-600">{{ stats.appeals }}</div>-->
<!--                <div class="text-sm text-gray-600">Апелляций</div>-->
<!--            </Link>-->

            <Link
                :href="route('client.reports.index')"
                class="bg-blue-50 border border-blue-200 rounded-lg p-4 hover:bg-blue-100 transition"
            >
                <div class="text-2xl font-bold text-blue-600">{{ stats.reports }}</div>
                <div class="text-sm text-gray-600">Жалоб</div>
            </Link>

<!--            <Link-->
<!--                :href="route('client.profile.notifications.index')"-->
<!--                class="bg-purple-50 border border-purple-200 rounded-lg p-4 hover:bg-purple-100 transition"-->
<!--            >-->
<!--                <div class="text-2xl font-bold text-purple-600">{{ stats.unread_notifications }}</div>-->
<!--                <div class="text-sm text-gray-600">Новых уведомлений</div>-->
<!--            </Link>-->
        </div>
    </div>
</template>

<style scoped>

</style>
