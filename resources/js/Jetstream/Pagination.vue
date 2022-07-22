<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/solid'

defineProps({
    paginator: Object,
});
</script>

<template>
    <div class="px-4 py-3 flex items-center justify-between sm:px-0" v-if="paginator.last_page > 1">
        <div class="flex-1 flex justify-between sm:hidden">
            <component
                :is="paginator.prev_page_url ? 'Link' : 'span'"
                :href="paginator.prev_page_url"
                :class="{
                    'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white': true,
                    'hover:bg-gray-50': paginator.prev_page_url,
                }"
            >
                Previous
            </component>
            <component
                :is="paginator.next_page_url ? 'Link' : 'span'"
                :href="paginator.next_page_url"
                :class="{
                    'ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white': true,
                    'hover:bg-gray-50': paginator.next_page_url,
                }"
            >
                Next
            </component>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    {{ ' ' }}
                    <span class="font-medium">{{ paginator.from }}</span>
                    {{ ' ' }}
                    to
                    {{ ' ' }}
                    <span class="font-medium">{{ paginator.to }}</span>
                    {{ ' ' }}
                    of
                    {{ ' ' }}
                    <span class="font-medium">{{ paginator.total }}</span>
                    {{ ' ' }}
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <component
                        v-for="(link, index) in paginator.links"
                        :key="index"
                        :is="link.url ? 'link' : 'span'"
                        :href="link.url"
                        :class="{
                            'relative inline-flex items-center py-2 border text-sm font-medium': true,
                            'rounded-l-md': index === 0,
                            'rounded-r-md': index === paginator.links.length - 1,
                            'px-4': link.label.match(/^\d+$/),
                            'px-2': !link.label.match(/^\d+$/),
                            'z-10 bg-lime-50 border-lime-500 text-lime-600': link.active,
                            'bg-white border-gray-300 text-gray-500': !link.active,
                            'hover:bg-gray-50': link.url && !link.active,
                        }"
                    >
                        <template v-if="index === 0">
                            <span class="sr-only">Previous</span>
                            <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                        </template>
                        <template v-else-if="index === paginator.links.length - 1">
                            <span class="sr-only">Next</span>
                            <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                        </template>
                        <template v-else>
                            {{ link.label }}
                        </template>
                    </component>
                </nav>
            </div>
        </div>
    </div>
</template>
