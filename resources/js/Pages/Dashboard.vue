<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="container py-4 lg:py-6">
            <div class="flex items-center self-center px-4 sm:px-0">
                <img :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" class="rounded-full h-16 w-16 object-cover shadow-md">
                <div class="text-xl font-semibold ml-4">
                    {{ $page.props.auth.user.name }}
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg my-6 sm:my-8" v-if="activeRuns.length">
                <div class="bg-gray-50 px-4 sm:px-6 py-4 font-semibold">
                    Active test runs
                </div>
                <div
                    class="relative sm:flex items-center px-4 sm:px-6 py-4 border-t"
                    v-for="run in activeRuns"
                    :key="run.id"
                >
                    <Link :href="route('runs.show', run.id)" class="font-semibold mr-4">
                        {{ run.test_suite.name }}
                        <div class="absolute inset-0"></div>
                    </Link>
                    <div class="ml-auto">
                        {{ run.run_tests_count }} of {{ run.test_suite.tests_count }} tests completed
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg my-6 sm:my-8" v-if="suites.length">
                <div class="bg-gray-50 px-4 sm:px-6 py-4 font-semibold">
                    Start a new test run
                </div>
                <div
                    class="relative flex items-center px-4 sm:px-6 py-4 border-t"
                    v-for="suite in suites"
                    :key="suite.id"
                >
                    <div class="flex-1 sm:flex items-center justify-between">
                        <div class="font-semibold">{{ suite.name }}</div>
                        <div class="sm:mx-4" v-if="suite.latest_run">
                            Last run {{ formatDate(suite.latest_run.created_at) }}
                            <result-badge :result="suite.latest_run.result" class="ml-1" />
                        </div>
                    </div>
                    <button type="button" @click="run(suite)" class="appearance-none border-0 bg-transparent cursor-pointer text-lime-600" title="Run tests">
                        <span class="sr-only">Start a new test run</span>
                        <PlayIcon class="w-6 h-6" />
                        <div class="absolute inset-0"></div>
                    </button>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 sm:px-6 py-6 lg:py-12 my-8" v-else>
                <p class="text-center">
                    No test suites
                </p>
                <p class="text-gray-600 text-center">
                    Create your first test suite before starting testing.
                </p>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import { PlayIcon } from '@heroicons/vue/outline'
import AppLayout from '@/Layouts/AppLayout.vue'
import ResultBadge from '@/Pages/Runs/Partials/ResultBadge.vue'

export default {
    props: ['activeRuns', 'suites'],
    components: {
        Link,
        PlayIcon,
        AppLayout,
        ResultBadge,
    },
    setup() {
        const run = suite => {
            Inertia.post(route('runs.store'), {
                test_suite_id: suite.id,
            })
        }

        return {
            run,
        }
    },
}
</script>
