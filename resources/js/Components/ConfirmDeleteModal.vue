<script>
export default {
    name: 'ConfirmDeleteModal',

    props: {
        show: { type: Boolean, default: false },
        title: { type: String, default: 'Подтвердите удаление' },
        message: { type: String, default: 'Это действие необратимо. Продолжить?' },
        confirmText: { type: String, default: 'Удалить' },
        cancelText: { type: String, default: 'Отмена' },
        processing: { type: Boolean, default: false },
    },

    emits: ['confirm', 'close'],

    methods: {
        onConfirm() {
            if (this.processing) return;
            this.$emit('confirm');
        },
        onClose() {
            if (this.processing) return;
            this.$emit('close');
        },
        handleEscape(e) {
            if (e.key === 'Escape' && this.show) {
                this.onClose();
            }
        },
    },

    mounted() {
        document.addEventListener('keydown', this.handleEscape);
    },

    beforeUnmount() {
        document.removeEventListener('keydown', this.handleEscape);
    },
};
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                @click.self="onClose"
            >
                <Transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                        <!-- Иконка и заголовок -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 mb-1">
                                    {{ title }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ message }}
                                </p>
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex justify-end gap-3 mt-6">
                            <button
                                type="button"
                                @click="onClose"
                                :disabled="processing"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 transition"
                            >
                                {{ cancelText }}
                            </button>
                            <button
                                type="button"
                                @click="onConfirm"
                                :disabled="processing"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition flex items-center gap-2"
                            >
                                <svg v-if="processing" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                {{ processing ? 'Удаление...' : confirmText }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>

</style>
