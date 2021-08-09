<template>
    <div class="flex items-center">
        <application-mark class="h-full" aria-hidden="true" />
        <span class="text-2xl font-bold ml-3">Lime</span>
        <span class="text-2xl text-gray-500 ml-2">Testing Suite</span>
    </div>
</template>

<script>
import ApplicationMark from './ApplicationMark.vue';

export default {
    components: {
        ApplicationMark,
    },
}
</script>
