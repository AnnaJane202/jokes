<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import {Link} from "@inertiajs/vue3";

export default {
    name: 'AppealsList',

    layout: AdminLayout,

    props: {
        appeals: Object,
        tab: String,
    },

    // data() {
    //     return {
    //         appealsData: this.appeals
    //     }
    // },

    components: {
        Pagination,
        Link,
    },

    methods: {
        getDaysLeftText(daysLeft) {
            if (daysLeft === 0) return 'сегодня'
            if (daysLeft < 0) return 'просрочено'
            if (daysLeft === 1) return 'завтра'
            return `${daysLeft} дн.`
        },

        async quickApprove(appeal) {
            // axios.post(route('admin.appeals.quick-approve', appeal))
            //     .then (res => {
            //         console.log(res);
            //
            //         this.appealsData = this.appealsData.filter( appealData => appealData.id !== appeal.id)
            //
            //
            //     });

            await router.post(route('admin.appeals.quick-approve', appeal), {}, {
                // preserveScroll: true,
                onSuccess: () => {
                    // Inertia сам обновит всё, что нужно
                }
            })
        },

        async quickReject(appeal) {
            await router.post(route('admin.appeals.quick-reject', appeal), {}, {
                // preserveScroll: true,
                onSuccess: () => {
                    // Inertia сам обновит всё, что нужно
                }
            })
        }
    }
}
</script>

<template>
    <div class="space-y-4">
        <!-- Срочные апелляции (просроченные) -->
<!--        <div v-if="urgentAppeals.length > 0" class="bg-red-50 rounded-lg p-4 border border-red-200">-->
<!--            <h3 class="font-semibold text-red-800 mb-3">⚠️ Срочные апелляции</h3>-->
<!--            <div class="space-y-2">-->
<!--                <AppealCard-->
<!--                    v-for="appeal in urgentAppeals"-->
<!--                    :key="appeal.id"-->
<!--                    :appeal="appeal"-->
<!--                    type="urgent"-->
<!--                    @review="reviewAppeal"-->
<!--                />-->
<!--            </div>-->
<!--        </div>-->

        <!-- Обычные апелляции -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-yellow-50">
                <h2 class="text-lg font-semibold text-yellow-800">
                    ⚖️ Ожидают рассмотрения
                </h2>
            </div>

            <div class="divide-y">
                <div v-for="appeal in appeals.data.data" :key="appeal.id" class="p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
<!--                                <img :src="appeal.user.avatar" class="h-10 w-10 rounded-full">-->
                                <div>
                                    <div class="font-medium">{{ appeal.user.name }}</div>
                                    <div class="text-sm text-gray-500">{{ appeal.user.email }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div>
                                    <span class="text-xs text-gray-500">Нарушение:</span>
                                    <span class="ml-2 text-sm">{{ appeal.type_label }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Наказание:</span>
                                    <span class="ml-2 text-sm">{{ appeal.penalty_label }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Подана:</span>
                                    <span class="ml-2 text-sm">{{ appeal.appealed_at }}</span>
                                </div>
<!--                                <div>-->
<!--                                    <span class="text-xs text-gray-500">Дедлайн:</span>-->
<!--                                    <span class="ml-2 text-sm" :class="{ 'text-red-600': isDeadlineNear(appeal.deadline) }">-->
<!--                    {{ appeal.deadline }}-->
<!--                  </span>-->
<!--                                </div>-->

                                <div
                                    class="p-4 border rounded"
                                    :class="{
        'bg-red-50 border-red-300': appeal.is_deadline_near && appeal.days_left > 0,
        'bg-red-100 border-red-500': appeal.days_left <= 0
      }"
                                >
                                    <div class="flex justify-between">
<!--                                        <h3 class="font-medium">{{ appeal.user.name }}</h3>-->
                                        <div :class="{ 'text-red-600 font-bold': appeal.is_deadline_near }">
                                            Срок: {{ appeal.deadline }}
                                            <span v-if="appeal.days_left !== null" class="text-sm ml-1">
            ({{ getDaysLeftText(appeal.days_left) }})
          </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded p-3 mb-3">
                                <p class="text-sm text-gray-700 font-medium mb-1">Причина апелляции:</p>
                                <p class="text-sm">{{ appeal.appeal_reason }}</p>
                            </div>

                            <div v-if="appeal.appeal_evidence && appeal.appeal_evidence.length > 0" class="mb-3">
                                <span class="text-xs text-gray-500">📎 Доказательства:</span>
                                <div class="mt-1 flex gap-2">
                                    <a
                                        v-for="(evidence, idx) in appeal.appeal_evidence"
                                        :key="idx"
                                        :href="evidence.url"
                                        target="_blank"
                                        class="text-sm text-blue-600 hover:underline"
                                    >
                                        Ссылка {{ idx + 1 }}
                                    </a>
                                </div>
                            </div>
                        </div>

<!--                        <div class="ml-4 flex flex-col gap-2">-->
<!--                            <button-->
<!--                                @click="$emit('review', appeal)"-->
<!--                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm"-->
<!--                            >-->
<!--                                Рассмотреть-->
<!--                            </button>-->

<!--                        </div>-->
                        <Link
                            :href="route('admin.appeals.review', appeal.id)"
                            class="px-3 py-1 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-700 transition"
                        >
                            🔍 Подробнее
                        </Link>

                        <button
                            @click="quickApprove(appeal)"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                        >
                            Быстро одобрить
                        </button>
                        <button
                            @click="quickReject(appeal)"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-blue-700 text-sm"
                        >
                            Быстро отклонить
                        </button>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t">
                <Pagination
                    :links="appeals.links"
                    :meta="appeals.meta"
                    :tab="tab"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
