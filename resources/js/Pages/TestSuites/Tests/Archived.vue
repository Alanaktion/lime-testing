<template>
    <app-layout title="Archived Tests">
        <template #header>
            <div class="flex items-center">
                <Link :href="route('test-suites.index')" class="font-semibold text-xl text-gray-800 leading-tight">
                    Test Suites
                </Link>
                <ChevronRightIcon class="h-4 w-4 mt-0.5 text-gray-500 mx-2" />
                <Link :href="route('test-suites.show', suite.id)" class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ suite.name }}
                </Link>
                <ChevronRightIcon class="h-4 w-4 mt-0.5 text-gray-500 mx-2" />
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Archived Tests
                </h2>
            </div>
        </template>

        <div class="container py-4 lg:py-6">
            <div
                class="bg-white shadow-sm overflow-hidden border border-gray-200 sm:rounded-lg"
                v-if="tests.length"
            >
                <div
                    class="flex items-center px-4 sm:px-6 py-4 border-b last:border-b-0"
                    v-for="test in tests"
                    :key="test.id"
                >
                    <div class="flex-1 sm:flex items-center mr-4">
                        <div class="flex-1 mb-2 sm:mb-0">
                            <div class="font-semibold">
                                {{ test.name }}
                            </div>
                            <div class="text-gray-600">{{ getStepCount(test) }} steps</div>
                        </div>
                        <div>
                            Archived {{ formatDate(test.deleted_at) }}
                        </div>
                    </div>
                    <button type="button" @click="restore(test)" class="appearance-none border-0 bg-transparent cursor-pointer text-lime-600 ml-2 md:ml-4" title="Restore">
                        <span class="sr-only">Restore test</span>
                        <ReplyIcon class="w-6 h-6" />
                    </button>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-6 lg:py-12 my-8" v-else>
                <p class="text-center">
                    No tests archived
                </p>
                <p class="text-gray-600 text-center">
                    You can restore any tests that have been archived here.
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
    props: ['suite', 'tests'],
    components: {
        Link,
        ChevronRightIcon,
        ReplyIcon,
        AppLayout,
    },
    setup() {
        const restore = test => {
            Inertia.post(route('tests.restore', test.id))
        }

        const getStepCount = test => {
            if (!test.steps) {
                return 0
            }
            return test.steps.trim().split(/\r\n|\r|\n/).length
        }

        return {
            restore,
            getStepCount,
        }
    },
}
</script>
