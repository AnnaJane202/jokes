<script>
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: 'Index',

    components: {
        Link,
        ProfileLayout
    },

    props: {
        banReason: {
            type: String,
            default: null
        },
        bannedUntil: {
            type: String,
            default: null
        },
        violationId: {
            type: Number,
            required: true
        }
    }
}
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-8">
            <div class="text-center">
                <div class="text-6xl mb-4">🚫</div>
                <h1 class="text-2xl font-bold text-red-600 mb-4">
                    Ваш аккаунт заблокирован
                </h1>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 text-left">
                    <p class="text-sm font-medium text-red-800">Причина блокировки:</p>
                    <p class="text-sm text-red-700 mt-1">
                        {{ banReason || 'Нарушение правил' }}
                    </p>

                    <p v-if="bannedUntil" class="text-sm text-red-700 mt-2">
                        Блокировка действует до:
                        <strong>{{ bannedUntil }}</strong>
                    </p>
                    <p v-else class="text-sm text-red-700 mt-2">
                        ⚠️ Блокировка постоянная
                    </p>
                </div>

                <!-- ✅ Кнопка подачи апелляции -->
                <Link
                    :href="route('client.violations.appeal', violationId)"
                    class="block w-full px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition mb-3"
                >
                    📝 Подать апелляцию
                </Link>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="block w-full px-4 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                >
                    Выйти из системы
                </Link>
            </div>

            <p class="text-xs text-gray-500 text-center mt-6">
                Апелляция будет рассмотрена в течение 7 дней.
                Вы получите уведомление на email.
            </p>
        </div>
    </div>
</template>

<style scoped>

</style>
