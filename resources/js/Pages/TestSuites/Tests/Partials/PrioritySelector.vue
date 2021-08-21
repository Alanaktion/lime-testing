<template>
    <RadioGroup v-model="modelValue">
        <RadioGroupLabel class="block font-medium text-sm text-gray-700">
            Priority
        </RadioGroupLabel>
        <div class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px mt-1">
            <RadioGroupOption v-slot="{ checked }" value="optional" class="focus:outline-none focus:ring focus:z-20 ring-lime-300 rounded-l-md">
                <span :class="{
                    'relative inline-flex items-center px-2 py-2 rounded-l-md border text-sm font-medium cursor-pointer': true,
                    'bg-lime-100 hover:bg-lime-200 text-lime-800 border-lime-500 z-10': checked,
                    'hover:bg-gray-50 text-gray-500 border-gray-300': !checked,
                }">
                    Optional
                </span>
            </RadioGroupOption>
            <RadioGroupOption v-slot="{ checked }" value="normal" class="focus:outline-none focus:ring focus:z-20 ring-lime-300">
                <span :class="{
                    'relative inline-flex items-center px-2 py-2 border text-sm font-medium cursor-pointer': true,
                    'bg-lime-100 hover:bg-lime-200 text-lime-800 border-lime-500 z-10': checked,
                    'hover:bg-gray-50 text-gray-500 border-gray-300': !checked,
                }">
                    Normal
                </span>
            </RadioGroupOption>
            <RadioGroupOption v-slot="{ checked }" value="high" class="focus:outline-none focus:ring focus:z-20 ring-red-300 rounded-r-md">
                <span :class="{
                    'relative inline-flex items-center px-2 py-2 rounded-r-md border text-sm font-medium cursor-pointer': true,
                    'bg-red-100 hover:bg-red-200 text-red-800 border-red-500 z-10': checked,
                    'hover:bg-gray-50 text-gray-500 border-gray-300': !checked,
                }">
                    High
                </span>
            </RadioGroupOption>
        </div>
    </RadioGroup>
</template>

<script>
import { watch, toRefs } from 'vue'
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'

export default {
    props: ['modelValue'],
    emits: ['update:modelValue'],
    components: {
        RadioGroup,
        RadioGroupLabel,
        RadioGroupOption,
    },
    setup(props, context) {
        const { modelValue } = toRefs(props)
        watch(modelValue, val => context.emit('update:modelValue', val))
    },
}
</script>
