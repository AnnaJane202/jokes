<script>

import { useForm, Link, Head } from '@inertiajs/vue3'

export default {
    name: 'Create',

    components: {
        Link,
        Head,
    },

    props: {
        reportable: Object,
        reportTypes: Array,
    },

    data() {
        return {
            form: useForm({
                reportable_type: this.reportable.type,
                reportable_id: this.reportable.id,
                type: '',
                reason: '',
                evidence_ids: [],
            }),
            uploadedFiles: [],
            uploading: false,
        }
    },

    methods: {
        getIcon(type) {
            const icons = {
                spam: '📢',
                abuse: '😠',
                harassment: '🚫',
                copyright: '©️',
                illegal: '⚖️',
                fraud: '🤥',
                nsfw: '🔞',
                doxing: '🔍',
                misinformation: '📰',
                admin_abuse: '👑',
                other: '❓',
            }
            return icons[type] || '📌'
        },

        async handleFileUpload(event) {
            const files = Array.from(event.target.files)
            for (const file of files) {
                await this.uploadFile(file)
            }
            event.target.value = ''
        },

        async uploadFile(file) {
            this.uploading = true

            const formData = new FormData()
            formData.append('file', file)

            try {
                const url = route('client.reports.upload-evidence');
                const response = await axios.post(url, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                this.uploadedFiles.push(response.data)
                this.form.evidence_ids.push(response.data.id)
            } catch (error) {
                console.error('Ошибка загрузки:', error)
                alert('Не удалось загрузить файл: ' + file.name)
            } finally {
                this.uploading = false
            }
        },

        async deleteFile(fileId) {
            if (!confirm('Удалить файл?')) return

            try {
                // await axios.delete(`/api/reports/evidence/${fileId}`)
                await axios.delete(route('reports.evidence.delete', fileId))
                this.uploadedFiles = this.uploadedFiles.filter(f => f.id !== fileId)
                this.form.evidence_ids = this.form.evidence_ids.filter(id => id !== fileId)
            } catch (error) {
                console.error('Ошибка удаления:', error)
            }
        },

        submit() {
            this.form.post(route('client.reports.store'))
        }
    }
}
</script>

<template>
    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-4">📢 Подача жалобы</h1>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p><strong>Контент:</strong> {{ reportable.title }}</p>
                    <p><strong>Автор:</strong> {{ reportable.author }}</p>
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block font-medium mb-2">Тип жалобы *</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                v-for="type in reportTypes"
                                :key="type.value"
                                type="button"
                                @click="form.type = type.value"
                                class="p-3 border rounded-lg text-left transition"
                                :class="{
                                        'border-blue-500 bg-blue-50': form.type === type.value,
                                        'border-gray-200 hover:bg-gray-50': form.type !== type.value
                                    }"
                            >
                                <div class="text-xl">{{ getIcon(type.value) }}</div>
                                <div class="text-sm font-medium">{{ type.label }}</div>
                            </button>
                        </div>
                        <p v-if="form.errors.type" class="text-red-500 text-sm mt-1">
                            {{ form.errors.type }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Причина жалобы *</label>
                        <textarea
                            v-model="form.reason"
                            rows="4"
                            class="w-full border rounded-md p-2"
                            placeholder="Опишите, почему вы считаете этот контент нарушением..."
                        ></textarea>
                        <p v-if="form.errors.reason" class="text-red-500 text-sm mt-1">
                            {{ form.errors.reason }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Доказательства</label>

                        <div v-if="uploadedFiles.length > 0" class="mb-2 space-y-1">
                            <div v-for="file in uploadedFiles" :key="file.id"
                                 class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <div class="flex items-center gap-2">
                                    <span>{{ file.icon }}</span>
                                    <a :href="file.url" target="_blank" class="text-blue-600 hover:underline text-sm">
                                        {{ file.original_name }}
                                    </a>
                                    <span class="text-xs text-gray-500">({{ file.size }})</span>
                                </div>
                                <button type="button" @click="deleteFile(file.id)" class="text-red-500">
                                    ✕
                                </button>
                            </div>
                        </div>

                        <input
                            ref="fileInput"
                            type="file"
                            multiple
                            accept="image/*,.pdf,.doc,.docx"
                            @change="handleFileUpload"
                            class="block w-full text-sm text-gray-500"
                        >

                        <div v-if="uploading" class="mt-2 text-sm text-gray-500">
                            Загрузка...
                        </div>

                        <p class="text-xs text-gray-500 mt-1">
                            Поддерживаются: изображения, PDF, DOC (макс. 10 МБ)
                        </p>
                    </div>

                    <div class="flex justify-end gap-3">
<!--                        <Link :href="route('reports.my')" class="px-4 py-2 border rounded-md">-->
<!--                            Отмена-->
<!--                        </Link>-->
                        <Link :href="route('post.index')" class="px-4 py-2 border rounded-md">
                            Отмена
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing || uploading"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Отправка...' : 'Отправить жалобу' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
