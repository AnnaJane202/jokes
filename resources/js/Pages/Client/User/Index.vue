<script>
import {router, Link} from "@inertiajs/vue3";
import { debounce } from 'lodash';
import Pagination from "@/Components/Pagination.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
export default {
    name: 'Index',

    layout: MainLayout,

    components: {
        Link,
        Pagination,
    },

    props: {
        users: Object,
        filters: Object,
    },
    data() {
        return {
            search: this.filters.search || '',
            type: '',
            id: null,
        };

    },
    methods: {
        applySearch: debounce(function() {
            router.get(route('client.users.index'), { search: this.search }, { preserveState: true });
        }, 300),
    },

}
</script>

<template>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-4">Пользователи</h1>

            <input v-model="search" @input="applySearch" placeholder="Поиск..." class="border rounded p-2 mb-4" />
<!--            <pre>{{ JSON.stringify(users, null, 2) }}</pre>-->
            <div class="flex justify-between">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="user in users.data" :key="user.id" class="bg-white rounded-lg shadow p-4 flex items-center justify-between">
                        <Link :href="route('client.users.show', user.id)" class="flex items-center gap-3">
                                                    <img :src="user.avatar" class="w-10 h-10 rounded-full" />
                            <span>{{ user.name }}</span>
                        </Link>

                        <Link :href="route('client.reports.create', { type: 'user', id: user.id })"
                              class="text-red-500 hover:text-red-700 text-sm">
                            ⚠️ Пожаловаться
                        </Link>

                    </div>
                </div>
                <div>

                </div>
            </div>


            <Pagination :links="users.links" :meta="users.meta" class="mt-4" />
        </div>
    </div>
</template>

<style scoped>

</style>
