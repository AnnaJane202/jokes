<script>
import UserProfileLayout from "@/Layouts/UserProfileLayout.vue";
import {Link} from "@inertiajs/vue3";
import { useForm } from '@inertiajs/vue3'
export default {
    name: 'Appeal',

    layout: UserProfileLayout,

    components: {
        Link
    },

    props: {
        violation: Object,
    },

    data() {
        return {

            form: useForm({
                appeal_reason: '',
                uploaded_file_ids: [],// ← ID загруженных файлов (из предыдущих загрузок)
                details: [],
            }),
            uploadedFiles: [], // Список загруженных файлов
            uploading: false,
        }
    },

    methods: {
        getFileIcon(mimeType) {
            if (mimeType.startsWith('image/')) return '🖼️'
            if (mimeType === 'application/pdf') return '📄'
            if (mimeType.includes('word')) return '📝'
            return '📎'
        },
        formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B'
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB'
        },

        // Выбрать файлы через диалог
        triggerFileInput() {
            this.$refs.fileInput.click()
        },

        // Обработка выбора файлов
        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            this.uploadFiles(files);
            this.$refs.fileInput.value = ''; // Очищаем input
        },

        // Загрузка файлов на сервер
        async uploadFiles(files) {
            if (files.length === 0) return

            this.uploading = true

            for (const file of files) {
                // Проверка размера
                if (file.size > 10 * 1024 * 1024) {
                    alert(`Файл ${file.name} превышает 10 МБ и не будет загружен`);
                    continue
                }

                // Проверка типа
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf',
                    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!allowedTypes.includes(file.type)) {
                    alert(`Файл ${file.name} имеет неподдерживаемый формат`)
                    continue
                }

                await this.uploadFile(file);
            }

            this.uploading = false

        },

        // Загрузка одного файла
        async uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('violation_id', this.violation.id);

            axios.post(route('client.appeal.appeal-evidence'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(res => {
                    console.log(res);

                    // Добавляем загруженный файл в список
                    this.uploadedFiles.push(res.data);

                    // Сохраняем ID файла для отправки в форме
                    this.form.uploaded_file_ids.push(res.data.id);
                })

        },

        submitForm(event) {
            // Очищаем пустые ссылки
            // this.form.evidence_links = this.form.evidence_links.filter(link => link && link.trim() !== '')

            // Если нет ни ссылок, ни загруженных файлов - это нормально

            if (event) {
                event.preventDefault()
                event.stopPropagation()
            }

            console.log('📤 Отправка апелляции...')
            console.log('URL:', route('client.violations.appeal.store', this.violation.id))
            console.log('Данные:', this.form.data())

            console.log('submitForm вызван, событие предотвращено')

            // Отправляем через Inertia с полной отладкой
            this.form.post(route('client.violations.appeal.store', this.violation.id), {
                preserveScroll: true,
                onStart: () => {
                    console.log('🟡 Запрос начат')
                },
                onProgress: (event) => {
                    console.log('🟢 Прогресс:', event)
                },
                onSuccess: (response) => {
                    console.log('✅ Успех!', response)
                },
                onError: (errors) => {
                    console.error('❌ Ошибки:', errors)
                },
                onFinish: (response) => {
                    console.log('🏁 Запрос завершен', response)
                }
            })
        }
    },

    computed: {
        // Вычисляемое свойство для проверки приближения дедлайна
        isDeadlineNear() {
            if (!this.violation.deadline) return false

            const deadlineParts = this.violation.deadline.split('.')
            const deadlineDate = new Date(
                deadlineParts[2], // год
                deadlineParts[1] - 1, // месяц (0-11)
                deadlineParts[0] // день
            )
            const diff = deadlineDate - new Date()
            return diff < 24 * 60 * 60 * 1000 // меньше дня
        }
    },

}
</script>

<template>
<!--    <Head title="Подача апелляции" />-->

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Информация о нарушении -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <h2 class="text-xl font-bold mb-4">Детали нарушения</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Тип нарушения</p>
                        <p class="font-medium">{{ violation.type_label }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Наказание</p>
                        <p class="font-medium">{{ violation.penalty_label }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Дата</p>
                        <p class="font-medium">{{ violation.created_at }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Срок подачи</p>
                        <p class="font-medium" :class="{ 'text-red-600': isDeadlineNear }">
                            {{ violation.deadline }}
                        </p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-sm text-gray-600">Причина нарушения</p>
                    <p class="mt-1 p-3 bg-gray-50 rounded">{{ violation.reason }}</p>
                </div>
            </div>

            <!-- Форма апелляции -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Подача апелляции</h2>


                <form @submit.prevent="submitForm">
                    <!-- Причина апелляции -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Объясните, почему вы не согласны с нарушением
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="form.appeal_reason"
                            rows="6"
                            class="w-full rounded-md border-gray-300"

                            placeholder="Опишите подробно, почему вы считаете, что нарушение вынесено ошибочно..."
                            maxlength="2000"
                        ></textarea>
                        <div class="flex justify-between mt-1">
<!--                <span class="text-sm text-red-600" v-if="form.errors.reason">-->
<!--                  {{ form.errors.reason }}-->
<!--                </span>-->
                            <span class="text-sm text-gray-500 ml-auto">
                  /2000
                </span>
                            <div v-if="form.errors.reason" class="text-red-600 text-sm mt-1">
                                {{ form.errors.reason }}
                            </div>
                        </div>
                    </div>

                    <!-- Доказательства -->
                    <!-- ЗАГРУЗКА ДОКАЗАТЕЛЬСТВ (улучшенная версия) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Доказательства (скриншоты, документы)
                        </label>

                        <!-- Список уже загруженных файлов -->
                        <div v-if="uploadedFiles.length > 0" class="mb-3">
                            <div v-for="(file, index) in uploadedFiles" :key="file.id"
                                 class="flex items-center justify-between p-2 bg-gray-50 rounded mb-2">
                                <div class="flex items-center">
                                    <span class="text-gray-600 mr-2">{{ getFileIcon(file.mime_type) }}</span>
                                    <a :href="file.url" target="_blank" class="text-blue-600 hover:underline text-sm">
                                        {{ file.original_name }}
                                    </a>
                                    <span class="text-xs text-gray-500 ml-2">
                      ({{ formatFileSize(file.size) }})
                    </span>
                                </div>
<!--                                <button-->
<!--                                    type="button"-->
<!--                                    @click="deleteUploadedFile(file.id, index)"-->
<!--                                    class="text-red-600 hover:text-red-800 text-sm"-->
<!--                                    :disabled="deletingFile === file.id"-->
<!--                                >-->
<!--                                    {{ deletingFile === file.id ? 'Удаление...' : 'Удалить' }}-->
<!--                                </button>-->
                            </div>
                        </div>

                        <!-- Область для загрузки новых файлов -->
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer"
                            @click="triggerFileInput"

                        >
                            <input
                                ref="fileInput"
                                type="file"
                                multiple
                                accept="image/*,.pdf,.doc,.docx"
                                class="hidden"
                                @change="handleFileSelect"
                            >

                            <div class="text-gray-500">
                                <div class="text-3xl mb-2">📎</div>
                                <p class="text-sm">
                                    Перетащите файлы сюда или <span class="text-blue-600">нажмите для выбора</span>
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Поддерживаются: изображения (jpg, png, gif), PDF, DOC (макс. 10 МБ)
                                </p>
                            </div>
                        </div>

                        <!-- Индикатор загрузки -->
                        <div v-if="uploading" class="mt-3">
                            <div class="flex items-center">
                                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600 mr-2"></div>
                                <span class="text-sm text-gray-600">Загрузка файлов...</span>
                            </div>
                        </div>


                    </div>






                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-3">
<!--                        <Link-->
<!--                            :href="route('profile.violations.show', violation.id)"-->
<!--                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md hover:bg-gray-50"-->
<!--                        >-->
<!--                            Отмена-->
<!--                        </Link>-->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Отправка...' : 'Подать апелляцию' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
