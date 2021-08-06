<template>
    <div class="flex items-center">
        <ClipboardListIcon class="text-lime-500 h-full" />
        <span class="text-2xl font-bold ml-3">Lime</span>
        <span class="text-2xl text-gray-500 ml-2">Testing Suite</span>
    </div>
</template>

<script>
    import { ClipboardListIcon } from '@heroicons/vue/outline'

    export default {
        components: {
            ClipboardListIcon,
        },
    }
</script>
