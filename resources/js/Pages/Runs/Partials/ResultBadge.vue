<template>
    <div
        :class="{
            'capitalize px-1 rounded-full': true,
            'text-lime-700 bg-lime-100': status === 'pass',
            'text-red-700 bg-red-100': status === 'fail',
            'text-gray-700 bg-gray-100': status === 'skip',
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
