<template>
    <div
        :class="{
            'inline-flex capitalize px-3 py-px text-sm justify-center font-semibold rounded-full': true,
            'text-lime-700 bg-lime-100': result === 'pass',
            'text-red-700 bg-red-100': result === 'fail',
            'text-gray-700 bg-gray-100': result === 'skip',
        }"
    >
        {{ result }}
    </div>
</template>

<script>
export default {
    props: ['result'],
}
</script>
