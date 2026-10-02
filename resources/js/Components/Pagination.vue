<script>
import {Link} from "@inertiajs/vue3";
export default {
    name: 'Pagination',

    props: {
        links: Array,
        meta: Object,
        tab: String,
    },

    components: {
        Link,
    },

    methods: {
        getLabel(label) {
            if (label === '&laquo; Previous') return '← Назад'
            if (label === 'Next &raquo;') return 'Вперед →'
            return label
        }
    }
}
</script>

<template>

    <div class="flex gap-1">
        <template v-for="link in links" :key="link.label">
            <!-- Если есть URL - используем Link -->
            <Link
                v-if="link.url && link.label !== '...'"
                :href="link.url + (tab ? `&tab=${tab}` : '')"
                class="px-3 py-1 border rounded text-sm transition"
                :class="{
          'bg-blue-600 text-white border-blue-600': link.active,
          'hover:bg-gray-50': !link.active
        }"
            >
                {{ getLabel(link.label) }}
            </Link>

            <!-- Если нет URL - обычный span -->
            <span
                v-else-if="link.label !== '...'"
                class="px-3 py-1 border rounded text-sm cursor-not-allowed opacity-50 text-gray-400"
            >
        {{ getLabel(link.label) }}
      </span>

            <!-- Разделитель -->
            <span v-else class="px-3 py-1 text-gray-500">...</span>
        </template>
    </div>
</template>

<style scoped>

</style>
