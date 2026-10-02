<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import ActiveViolationsTable from "@/Components/Admin/ActiveViolationsTable.vue";
import AppealsList from "@/Components/Admin/AppealsList.vue";

export default {
    name: 'Index',

    layout: AdminLayout,

    props: {
        stats: Object,
        activeViolations: Object,
        pendingAppeals: Object,
        tab: String,
    },

    data() {
        return {
            activeTab: this.tab,
        }
    },

    methods: {

    },

    components: {
        Link,
        ActiveViolationsTable,
        AppealsList


    }

}
</script>

<template>
    <div class="p-6">
        <!-- Заголовок -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Управление нарушениями</h1>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-8">
<!--                <button-->
<!--                    @click="activeTab = 'active'"-->
<!--                    class="py-2 px-1 border-b-2 font-medium text-sm"-->
<!--                    :class="{-->
<!--              'border-blue-500 text-blue-600': activeTab === 'active',-->
<!--              'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'active'-->
<!--            }"-->
<!--                >-->
<!--                    🔴 Активные нарушения-->
<!--                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800">-->
<!--              {{ stats.active }}-->
<!--            </span>-->
<!--                </button>-->

                <Link
                    :href="route('admin.violations.index', { tab: 'active', page: 1 })"
                    class="py-2 px-1 border-b-2"
                    :class="{
            'border-blue-500 text-blue-600': tab === 'active',
            'border-transparent text-gray-500': tab !== 'active'
          }"
                >
                    Активные нарушения
                </Link>

                <Link
                    :href="route('admin.violations.index', { tab: 'appeals', page: 1 })"
                    class="py-2 px-1 border-b-2 font-medium text-sm"
                    :class="{
              'border-blue-500 text-blue-600': activeTab === 'appeals',
              'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'appeals'
            }"
                >
                    ⚖️ Апелляции
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800">
              {{ stats.appeals }}
            </span>
                </Link>

                <button
                    @click="activeTab = 'history'"
                    class="py-2 px-1 border-b-2 font-medium text-sm"
                    :class="{
              'border-blue-500 text-blue-600': activeTab === 'history',
              'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'history'
            }"
                >
                    📜 История нарушений
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-800">
              {{ stats.total }}
            </span>
                </button>

                <button
                    @click="activeTab = 'banned'"
                    class="py-2 px-1 border-b-2 font-medium text-sm"
                    :class="{
              'border-blue-500 text-blue-600': activeTab === 'banned',
              'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'banned'
            }"
                >
                    🚫 Забаненные пользователи
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800">
              {{ stats.banned }}
            </span>
                </button>
            </nav>
        </div>

        <!-- Контент вкладок -->

         1. Активные нарушения
        <div v-if="activeTab === 'active'">
            <ActiveViolationsTable
                :violations="activeViolations"
                :tab="tab"

            />
        </div>

         2. Апелляции (приоритетные)
        <div v-if="activeTab === 'appeals'">
            <AppealsList
                :appeals="pendingAppeals"
                :tab="tab"

            />
        </div>

<!--        @review="reviewAppeal"-->

        <!-- 3. История всех нарушений -->
<!--        <div v-if="activeTab === 'history'">-->
<!--            <ViolationsHistoryTable-->
<!--                :violations="allViolations"-->
<!--                :filters="filters"-->
<!--                @filter="applyFilters"-->
<!--            />-->
<!--        </div>-->

        <!-- 4. Забаненные пользователи -->
<!--        <div v-if="activeTab === 'banned'">-->
<!--            <BannedUsersList-->
<!--                :users="bannedUsers"-->
<!--                @unban="unbanUser"-->
<!--            />-->
<!--        </div>-->
    </div>
</template>

<style scoped>

</style>
