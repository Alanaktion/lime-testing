<template>
    <div
        :class="{
            'btn border-transparent cursor-pointer': true,

            'text-lime-800 focus:border-lime-500 focus:ring-lime-300 shadow-xs': result === 'pass',
            'text-red-800 focus:border-red-500 focus:ring-red-300 shadow-xs': result === 'fail',
            'btn--secondary': result === 'skip',

            'bg-lime-50': result === 'pass' && !selected,
            'bg-red-50': result === 'fail' && !selected,

            'ring-3': selected,
            'bg-lime-200 border-lime-600 ring-lime-300': result === 'pass' && selected,
            'bg-red-200 border-red-500 ring-red-300': result === 'fail' && selected,
            'bg-gray-200 border-gray-500 ring-gray-300': result === 'skip' && selected,
        }"
    >
        <slot></slot>
    </div>
</template>

<script>
export default {
    props: {
        result: String,
        selected: Boolean,
    },
}
</script>
