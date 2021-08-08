<template>
    <Link :href="'/'">
        <ClipboardListIcon class="text-lime-500 w-16 h-16" />
    </Link>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';
    import { ClipboardListIcon } from '@heroicons/vue/outline'

    export default {
        components: {
            Link,
            ClipboardListIcon,
        },
    }
</script>
