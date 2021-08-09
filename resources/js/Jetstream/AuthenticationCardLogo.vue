<template>
    <Link :href="'/'">
        <span class="sr-only">{{ $page.props.appName }}</span>
        <application-mark class="w-16 h-16" aria-hidden="true" />
    </Link>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';
    import ApplicationMark from './ApplicationMark.vue';

    export default {
        components: {
            Link,
            ApplicationMark,
        },
    }
</script>
