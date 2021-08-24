<template>
    <app-layout :title="suite.name">
        <template #header>
            <div class="sm:flex w-full relative">
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
                        {{ test ? test.name : 'Create Test' }}
                    </h2>
                </div>
                <div class="flex mt-3 sm:absolute sm:right-0 sm:-my-1" v-if="test">
                    <jet-secondary-button @click="archive">
                        Archive <span class="sr-only">test</span>
                    </jet-secondary-button>
                </div>
            </div>
        </template>

        <div class="container py-4 lg:py-6">
            <jet-form-section @submitted="save">
                <template #title>
                    Test Details
                </template>

                <template #description>
                    Add the information required to complete the test.
                </template>

                <template #form>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="name" value="Name" />
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required :autofocus="!test" />
                        <jet-input-error :message="form.errors.name" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <PrioritySelector v-model="form.priority" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="description" value="Description" />
                        <textarea id="description" class="input block mt-1 w-full" v-model="form.description"></textarea>
                        <jet-input-error :message="form.errors.description" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="steps" value="Steps" />
                        <textarea id="steps" class="input block mt-1 w-full" v-model="form.steps" rows="8"></textarea>
                        <jet-input-error :message="form.errors.steps" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </jet-button>
                </template>
            </jet-form-section>
        </div>
    </app-layout>
</template>

<script>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
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
import PrioritySelector from './Partials/PrioritySelector.vue'

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
        PrioritySelector,
    },
    setup(props) {
        const newStep = ref(null)
        const form = useForm({
            name: props.test?.name,
            priority: props.test?.priority || 'normal',
            description: props.test?.description,
            steps: props.test?.steps,
        })

        const save = () => {
            if (props.test) {
                form.patch(route('tests.update', [props.test.id]))
            } else {
                form.post(route('test-suites.tests.store', [props.suite.id]))
            }
        }

        const archive = () => {
            if (props.test) {
                Inertia.delete(route('tests.destroy', [props.test.id]))
            }
        }

        return {
            newStep,
            form,
            save,
            archive,
        }
    },
}
</script>
