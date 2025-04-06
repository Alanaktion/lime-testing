<template>
    <app-layout title="Test Suites">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Test Suites
            </h2>
        </template>

        <div class="container py-4 lg:py-6">
            <div
                class="bg-white shadow-sm overflow-hidden border border-gray-200 sm:rounded-lg"
                v-if="testSuites.length"
            >
                <div
                    class="flex items-center px-4 sm:px-6 py-4 border-b"
                    v-for="suite in testSuites"
                    :key="suite.id"
                >
                    <div class="flex-1 sm:flex items-center mr-4">
                        <div class="relative flex-1 mb-2 sm:mb-0">
                            <Link :href="route('test-suites.show', suite.id)" class="font-semibold">
                                {{ suite.name }}
                                <div class="absolute inset-0"></div>
                            </Link>
                            <div class="text-gray-600">{{ suite.tests_count }} tests</div>
                        </div>
                        <div class="sm:mx-4" v-if="suite.latest_run">
                            Last run {{ formatDate(suite.latest_run.created_at) }}
                            <result-badge :result="suite.latest_run.result" class="ml-1" />
                        </div>
                        <div>
                            Added {{ formatDate(suite.created_at) }}
                        </div>
                    </div>
                    <Link :href="route('test-suites.show', suite.id)" class="text-lime-600" title="Edit">
                        <span class="sr-only">Edit test suite</span>
                        <PencilAltIcon class="w-6 h-6" />
                    </Link>
                    <button type="button" @click="run(suite)" class="appearance-none border-0 bg-transparent cursor-pointer text-lime-600 ml-2 md:ml-4" title="Run tests">
                        <span class="sr-only">Start a new test run</span>
                        <PlayIcon class="w-6 h-6" />
                    </button>
                </div>

                <div class="bg-gray-50">
                    <form @submit.prevent="submit" class="sm:flex max-w-md px-4 sm:px-6 py-4">
                        <jet-label for="name" value="Suite name" class="sr-only" />
                        <jet-input
                            id="name" type="text" class="w-full"
                            v-model="form.name"
                            placeholder="Suite name" required
                        />

                        <jet-button type="submit" class="mt-2 w-full sm:mt-0 sm:w-auto sm:ml-2 shrink-0">
                            Create Suite
                        </jet-button>
                    </form>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 sm:px-6 py-6 lg:py-12 my-8" v-else>
                <p class="text-center">
                    No test suites
                </p>
                <p class="text-gray-600 text-center">
                    Get started by creating your first test suite.
                </p>
                <form @submit.prevent="submit" class="sm:flex max-w-md mx-auto mt-6">
                    <jet-label for="name" value="Suite name" class="sr-only" />
                    <jet-input
                        id="name" type="text" class="w-full"
                        v-model="form.name"
                        placeholder="Suite name" required autofocus
                    />

                    <jet-button type="submit" class="mt-2 w-full sm:mt-0 sm:w-auto sm:ml-2 shrink-0">
                        Create Suite
                    </jet-button>
                </form>
            </div>

            <div class="mt-4 lg:mt-6 text-center" v-if="archivedCount">
                <Link :href="route('test-suites.archived')" class="btn btn--secondary">
                    Archived test suites
                </Link>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'
import { PencilAltIcon, PlayIcon } from '@heroicons/vue/outline'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import ResultBadge from '../Runs/Partials/ResultBadge.vue'

export default {
    props: ['testSuites', 'archivedCount'],
    components: {
        Link,
        PencilAltIcon,
        PlayIcon,
        AppLayout,
        JetButton,
        JetInput,
        JetLabel,
        ResultBadge,
    },
    setup() {
        const showCreateModal = ref(false)
        const form = reactive({
            name: null,
            description: null,
        })

        const submit = () => {
            Inertia.post(route('test-suites.store'), form)
        }

        const run = suite => {
            Inertia.post(route('runs.store'), {
                test_suite_id: suite.id,
            })
        }

        return {
            showCreateModal,
            form,
            submit,
            run,
        }
    },
}
</script>
