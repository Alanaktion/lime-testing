<template>
    <div class="bg-white shadow overflow-hidden border border-gray-200 sm:rounded-lg mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-4 sm:px-6 py-4">
            <div>
                <div class="flex flex-wrap items-center gap-4 mb-1">
                    <div class="font-semibold">{{ test.name }}</div>
                    <div
                        v-if="test.priority !== 'normal'"
                        :class="{
                            'uppercase rounded py-1 px-2 text-xs font-semibold': true,
                            'bg-gray-100': test.priority === 'optional',
                            'bg-red-100 text-red-800': test.priority === 'high',
                        }"
                        :title="test.priority === 'optional'
                            ? 'This test is optional and will not affect the overall test run result.'
                            : null"
                    >
                        {{ test.priority === 'high' ? 'High priority' : 'Optional' }}
                    </div>
                </div>
                <div class="whitespace-pre-wrap">{{ test.description }}</div>
            </div>
            <div class="whitespace-pre-wrap">{{ test.steps }}</div>
        </div>
        <div class="bg-gray-50 border-t">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-4 sm:px-6 py-4">
                <RadioGroup class="flex gap-2" v-model="result" v-if="!closed">
                    <RadioGroupLabel class="sr-only">Result</RadioGroupLabel>
                    <RadioGroupOption class="focus:outline-none" v-slot="{ checked }" value="pass">
                        <result-button result="pass" :selected="checked">Pass</result-button>
                    </RadioGroupOption>
                    <RadioGroupOption class="focus:outline-none" v-slot="{ checked }" value="fail">
                        <result-button result="fail" :selected="checked">Fail</result-button>
                    </RadioGroupOption>
                    <RadioGroupOption class="focus:outline-none" v-slot="{ checked }" value="skip">
                        <result-button result="skip" :selected="checked">Skip</result-button>
                    </RadioGroupOption>
                </RadioGroup>
                <div v-else>
                    <span class="font-semibold mr-1">Result:</span>
                    <result-badge :result="result" />
                </div>
                <div v-if="!closed">
                    <label :for="`comments-${test.id}`" class="sr-only">
                        Task result comments
                    </label>
                    <textarea :id="`comments-${test.id}`" class="input w-full"
                        placeholder="Comments"
                        v-model="comment"
                    />
                </div>
                <div v-else-if="comment">
                    <div class="font-semibold mb-1">Comment:</div>
                    <div class="whitespace-pre-wrap">{{ comment }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, watch } from 'vue'
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupOption,
} from '@headlessui/vue'
import ResultBadge from './ResultBadge.vue'
import ResultButton from './ResultButton.vue'

export default {
    props: ['runId', 'test', 'runTest', 'closed'],
    components: {
        RadioGroup,
        RadioGroupLabel,
        RadioGroupOption,
        ResultBadge,
        ResultButton,
    },
    setup(props) {
        const result = ref(props.runTest?.result)
        const comment = ref(props.runTest?.comment)

        if (!props.closed) {
            const updateRoute = route('runs.run-test.update', [props.runId, props.test.id])

            watch(result, val => {
                axios.patch(updateRoute, { result: val })
            })

            watch(comment, _.debounce(val => {
                axios.patch(updateRoute, { comment: val })
            }, 300))
        }

        return {
            result,
            comment,
        }
    },
}
</script>
