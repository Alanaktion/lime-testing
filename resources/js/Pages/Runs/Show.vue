<template>
    <app-layout :title="`Test Run - ${run.test_suite.name}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Test Run - {{ run.test_suite.name }}
            </h2>
        </template>

        <div class="container py-4 lg:py-6">
            //
        </div>
    </app-layout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

export default {
    props: ['run'],
    components: {
        Link,
        AppLayout,
    },
    setup() {
        return {
            //
        }
    },
}
</script>
