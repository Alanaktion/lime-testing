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
                v-if="runs.length"
            >
                <div
                    class="flex items-center px-6 py-4 border-b last:border-b-0"
                    v-for="run in runs"
                    :key="run.id"
                >
                    <div class="relative flex-1 mr-4">
                        <Link :href="route('runs.show', run.id)" class="font-semibold">
                            {{ run.test_suite.name }}
                            <div class="absolute inset-0"></div>
                        </Link>
                        <span class="text-gray-700 bg-gray-100 px-2 py-px text-sm font-semibold rounded-full ml-3" v-if="run.completed_at === null">
                            Incomplete
                        </span>
                    </div>
                    <div class="mr-4" v-if="run.result">
                        <ResultBadge :result="run.result" />
                    </div>
                    <div v-if="run.completed_at">
                        Completed {{ formatDate(run.completed_at) }} by {{ run.user.name }}
                    </div>
                    <div v-else>
                        Started {{ formatDate(run.created_at) }} by {{ run.user.name }}
                    </div>
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
        </div>
    </app-layout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ResultBadge from './Partials/ResultBadge.vue'

export default {
    props: ['runs'],
    components: {
        Link,
        AppLayout,
        ResultBadge,
    },
}
</script>
