<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import ApplicationMark from './ApplicationMark.vue';
</script>

<template>
    <Link :href="'/'">
        <span class="sr-only">{{ $page.props.appName }}</span>
        <ApplicationMark class="w-16 h-16" aria-hidden="true" />
    </Link>
</template>
