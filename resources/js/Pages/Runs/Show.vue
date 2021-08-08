<template>
    <app-layout :title="`Test Run - ${run.test_suite.name}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Test Run - {{ run.test_suite.name }}
            </h2>
            <div class="mt-3">
                <div class="flex items-center" v-if="run.completed_at">
                    Completed {{ formatDate(run.completed_at) }} by {{ run.user.name }}
                    <result-badge class="ml-3" :result="run.result" />
                </div>
                <div v-else>
                    Started {{ formatDate(run.created_at) }} by {{ run.user.name }}
                </div>
            </div>
        </template>

        <div class="container py-4 lg:py-6"
            v-if="run.test_suite?.tests && (
                $page.props.user.id == run.user_id || run.completed_at !== null
            )">
            <run-test
                v-for="test in run.test_suite.tests"
                :key="test.id"
                :run-id="run.id"
                :test="test"
                :run-test="findRunTest(test.id)"
                :closed="run.completed_at !== null"
            />
            <jet-button @click="endTest" v-if="run.completed_at === null">
                End Test
            </jet-button>
        </div>
        <div v-else-if="$page.props.user.id != run.user_id">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-6 lg:py-12 my-8">
                <p class="text-center">
                    Test run unavailable
                </p>
                <p class="text-gray-600 text-center">
                    This test run is still in progress, pending completion by the user that started it.
                </p>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetButton from '@/Jetstream/Button.vue'
import ResultBadge from './Partials/ResultBadge.vue'
import RunTest from './Partials/RunTest.vue'

export default {
    props: ['run'],
    components: {
        AppLayout,
        JetButton,
        ResultBadge,
        RunTest,
    },
    setup(props) {
        const findRunTest = (id) => {
            return props.run.run_tests.find(runTest => runTest.test_id === id)
        }

        const endTest = () => {
            Inertia.patch(route('runs.update', props.run.id))
        }

        return {
            findRunTest,
            endTest,
        }
    },
}
</script>
