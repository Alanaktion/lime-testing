<template>
    <app-layout title="Test Run History">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Test Run History
            </h2>
        </template>

        <div class="container py-4 lg:py-6">
            <div
                class="bg-white shadow overflow-hidden border border-gray-200 sm:rounded-lg"
                v-if="runs.data.length"
            >
                <div
                    class="flex items-center px-4 sm:px-6 py-4 border-b last:border-b-0"
                    v-for="run in runs.data"
                    :key="run.id"
                >
                    <div class="flex-1 sm:flex items-center">
                        <div class="relative flex-1 mr-4">
                            <Link :href="route('runs.show', run.id)" class="font-semibold">
                                {{ run.test_suite.name }}
                                <div class="absolute inset-0"></div>
                            </Link>
                            <span class="text-gray-700 bg-gray-100 px-2 py-px text-sm font-semibold rounded-full ml-3" v-if="run.completed_at === null">
                                Incomplete
                            </span>
                        </div>
                        <div v-if="run.completed_at">
                            Completed {{ formatDate(run.completed_at) }} by {{ run.user.name }}
                        </div>
                        <div v-else>
                            Started {{ formatDate(run.created_at) }} by {{ run.user.name }}
                        </div>
                    </div>
                    <ResultBadge :result="run.result" class="sm:w-14 ml-4" v-if="run.result" />
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-6 lg:py-12 my-8" v-else>
                <p class="text-center">
                    No run history
                </p>
                <p class="text-gray-600 text-center">
                    Start a test run from the Test Suites view, or from the dashboard.
                </p>
            </div>

            <JetPagination :paginator="runs" class="mt-4" />
        </div>
    </app-layout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ResultBadge from './Partials/ResultBadge.vue'
import JetPagination from '@/Jetstream/Pagination.vue'

export default {
    props: ['runs'],
    components: {
        Link,
        AppLayout,
        ResultBadge,
        JetPagination,
    },
}
</script>
