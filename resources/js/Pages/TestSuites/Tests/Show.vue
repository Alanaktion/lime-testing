<template>
    <app-layout :title="suite.name">
        <template #header>
            <div class="flex flex-wrap items-center">
                <Link :href="route('test-suites.index')" class="font-semibold text-xl text-gray-800 leading-tight">
                    Test Suites
                </Link>
                <ChevronRightIcon class="h-4 w-4 mt-0.5 text-gray-500 mx-2" />
                <Link :href="route('test-suites.show', suite.id)" class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ suite.name }}
                </Link>
                <ChevronRightIcon class="h-4 w-4 mt-0.5 text-gray-500 mx-2" />
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ test.name }}
                </h2>
            </div>
        </template>

        <div class="container py-4 lg:py-6">
            <div class="flex justify-end mb-3">
                <jet-button @click="save">Save</jet-button>
            </div>

            <jet-form-section @submitted="save">
                <template #title>
                    Basic Information
                </template>

                <template #description>
                    Set the name and description of the test.
                </template>

                <template #form>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="name" value="Name" />
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                        <jet-input-error :message="form.errors.name" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="description" value="Description" />
                        <textarea id="description" class="border-gray-300 focus:border-lime-500 focus:ring focus:ring-lime-300 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="form.description"></textarea>
                        <jet-input-error :message="form.errors.description" class="mt-2" />
                    </div>
                </template>
            </jet-form-section>

            <jet-section-border />

            <jet-form-section @submitted="save" class="mt-10 sm:mt-0">
                <template #title>
                    Test Steps
                </template>

                <template #description>
                    Add the steps required to complete the test.
                </template>

                <template #form>
                    <div class="col-span-6 pt-1" v-if="form.steps.length">
                        <div
                            v-for="(step, index) in form.steps"
                            :key="index"
                            class="pb-1"
                        >
                            {{ step }}
                        </div>
                    </div>
                    <div class="col-span-6 text-center py-1 text-gray-600" v-else>
                        No steps added yet.
                    </div>
                </template>

                <template #actions>
                    <form @submit.prevent="addStep" class="flex-1 flex items-stretch">
                        <jet-label for="newStep" value="New step text" class="sr-only" />
                        <jet-input
                            id="newStep" type="text" class="w-full"
                            v-model="newStep"
                            placeholder="Add a step"
                        />

                        <jet-secondary-button
                            type="submit"
                            class="mt-2 w-full sm:mt-0 sm:w-auto sm:ml-2 flex-shrink-0"
                        >
                            Add Step
                        </jet-secondary-button>
                    </form>
                </template>
            </jet-form-section>
        </div>
    </app-layout>
</template>

<script>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { ChevronRightIcon } from '@heroicons/vue/outline'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'

export default {
    props: ['suite', 'test'],
    components: {
        Link,
        AppLayout,
        ChevronRightIcon,
        JetFormSection,
        JetSectionBorder,
        JetButton,
        JetSecondaryButton,
        JetInput,
        JetInputError,
        JetLabel,
    },
    setup(props) {
        const newStep = ref(null)
        const form = useForm({
            name: props.test.name,
            description: props.test.description,
            steps: props.test.steps,
        })

        const addStep = () => {
            if (!newStep.value) {
                return
            }
            form.steps.push(newStep.value)
            newStep.value = ''
        }

        const save = () => {
            form.patch(route('test-suites.tests.update', [props.suite.id, props.test.id]))
        }

        return {
            newStep,
            form,
            addStep,
            save,
        }
    },
}
</script>
