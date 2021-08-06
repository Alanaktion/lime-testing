<template>
    <app-layout :title="suite.name">
        <template #header>
            <div class="flex items-center">
                <Link href="/test-suites" class="font-semibold text-xl text-indigo-500 leading-tight">
                    Test Suites
                </Link>
                <ChevronRightIcon class="h-4 w-4 mt-1 text-gray-500 mx-2" />
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ suite.name}}
                </h2>
            </div>
        </template>

        <div class="container py-4 lg:py-8">
            //
        </div>
    </app-layout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ChevronRightIcon } from '@heroicons/vue/outline'

export default {
    props: ['suite'],
    components: {
        Link,
        AppLayout,
        ChevronRightIcon,
    },
    setup() {
        return {
            //
        }
    },
}
</script>
