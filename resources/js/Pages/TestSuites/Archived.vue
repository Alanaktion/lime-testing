<template>
    <app-layout title="Test Suites">
        <template #header>
            <div class="flex items-center">
                    <Link :href="route('test-suites.index')" class="font-semibold text-xl text-gray-800 leading-tight">
                        Test Suites
                    </Link>
                <ChevronRightIcon class="h-4 w-4 mt-0.5 text-gray-500 mx-2" />
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Archived
                </h2>
            </div>
        </template>

        <div class="container py-4 lg:py-6">
            <div
                class="bg-white shadow overflow-hidden border border-gray-200 sm:rounded-lg"
                v-if="testSuites.length"
            >
                <div
                    class="flex items-center px-6 py-4 border-b last:border-b-0"
                    v-for="suite in testSuites"
                    :key="suite.id"
                >
                    <div class="mr-auto">
                        <div class="font-semibold">
                            {{ suite.name }}
                        </div>
                        <div class="text-gray-600">{{ suite.tests_count }} tests</div>
                    </div>
                    <div class="mr-4">
                        Archived {{ formatDate(suite.deleted_at) }}
                    </div>
                    <button type="button" @click="restore(suite)" class="appearance-none border-0 bg-transparent cursor-pointer text-lime-600 ml-2 md:ml-4" title="Restore">
                        <span class="sr-only">Restore test suite</span>
                        <ReplyIcon class="w-6 h-6" />
                    </button>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-6 lg:py-12 my-8" v-else>
                <p class="text-center">
                    No test suites archived
                </p>
                <p class="text-gray-600 text-center">
                    You can restore any test suites that have been archived here.
                </p>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'
import { ChevronRightIcon, ReplyIcon } from '@heroicons/vue/outline'
import AppLayout from '@/Layouts/AppLayout.vue'

export default {
    props: ['testSuites'],
    components: {
        Link,
        ChevronRightIcon,
        ReplyIcon,
        AppLayout,
    },
    setup() {
        const restore = suite => {
            Inertia.post(route('test-suites.restore', suite.id))
        }

        const formatDate = dateStr => {
            const date = new Date(dateStr);
            return new Intl.DateTimeFormat('default', {dateStyle: 'short'})
                .format(date);
        }

        return {
            restore,
            formatDate,
        }
    },
}
</script>
