<template>
    <app-layout :title="suite.name">
        <template #header>
            <div class="sm:flex w-full">
                <div class="flex items-center">
                    <Link :href="route('test-suites.index')" class="font-semibold text-xl text-gray-800 leading-tight">
                        Test Suites
                    </Link>
                    <ChevronRightIcon class="h-4 w-4 mt-0.5 text-gray-500 mx-2" />
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ suite.name}}
                    </h2>
                </div>
                <div class="flex mt-3 sm:mt-0 sm:ml-auto">
                    <jet-secondary-button @click="editing = true" class="mr-2">
                        Rename <span class="sr-only">test suite</span>
                    </jet-secondary-button>
                    <button
                        @click="archive"
                        type="button"
                        class="inline-flex items-center px-3 py-2 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition"
                    >
                        Archive <span class="sr-only">test suite</span>
                    </button>
                </div>
            </div>
        </template>

        <div class="container py-4">
            No tests added yet.
        </div>

        <jet-dialog-modal :show="editing" @close="cancel">
            <template #title>
                Edit Test Suite
            </template>

            <template #content>
                <form @submit.prevent="save">
                    <div>
                        <jet-label for="name" value="Name" />
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                        <div class="text-red-600 mt-2" v-if="form.errors.name">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div class="mt-4">
                        <jet-label for="description" value="Description" />
                        <textarea id="description" class="border-gray-300 focus:border-lime-500 focus:ring focus:ring-lime-300 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="form.description"></textarea>
                        <div class="text-red-600 mt-2" v-if="form.errors.description">
                            {{ form.errors.description }}
                        </div>
                    </div>
                </form>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="cancel">
                    Cancel
                </jet-secondary-button>
                <jet-button class="ml-2" @click.native="save">
                    Save
                </jet-button>
            </template>
        </jet-dialog-modal>
    </app-layout>
</template>

<script>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { ChevronRightIcon } from '@heroicons/vue/outline'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'

export default {
    props: ['suite'],
    components: {
        Link,
        AppLayout,
        ChevronRightIcon,
        JetDialogModal,
        JetButton,
        JetSecondaryButton,
        JetInput,
        JetLabel,
    },
    setup(props) {
        const editing = ref(false)
        const form = useForm({
            name: props.suite.name,
            description: props.suite.description,
        })

        const cancel = () => {
            form.name = props.suite.name
            form.description = props.suite.description
            editing.value = false
        }

        const save = () => {
            form.patch(route('test-suites.update', props.suite.id), {
                onSuccess: () => {
                    editing.value = false
                },
            })
        }

        const archive = () => {
            //
        }

        return {
            editing,
            form,
            cancel,
            save,
            archive,
        }
    },
}
</script>
