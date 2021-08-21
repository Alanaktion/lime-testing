<template>
    <app-layout :title="suite.name">
        <template #header>
            <div class="sm:flex w-full relative">
                <div class="flex items-center">
                    <Link :href="route('test-suites.index')" class="font-semibold text-xl text-gray-800 leading-tight">
                        Test Suites
                    </Link>
                    <ChevronRightIcon class="h-4 w-4 mt-0.5 text-gray-500 mx-2" />
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ suite.name }}
                    </h2>
                </div>
                <div class="flex mt-3 sm:absolute sm:right-0 sm:-my-1">
                    <jet-secondary-button @click="editing = true">
                        Rename <span class="sr-only">test suite</span>
                    </jet-secondary-button>
                    <jet-secondary-button @click="archive" class="ml-2">
                        Archive <span class="sr-only">test suite</span>
                    </jet-secondary-button>
                </div>
            </div>
        </template>

        <div class="container py-4 lg:py-6">
            <div class="flex justify-end mb-3 px-4 sm:px-0">
                <Link :href="route('test-suites.tests.create', suite.id)" class="btn btn--primary">
                    Add Test
                </Link>
            </div>

            <div class="bg-white shadow overflow-hidden border border-gray-200 sm:rounded-lg mb-6">
                <div v-if="tests.length">
                    <Draggable
                        v-model="tests"
                        item-key="id"
                        handle=".handle"
                        ghost-class="opacity-50"
                        @update="updateSort"
                    >
                        <template #item="{element}">
                            <div class="flex items-center px-6 py-4 border-b last:border-b-0">
                                <div class="handle px-4 py-3 -ml-4 cursor-move" title="Sort test">
                                    <span class="sr-only">Drag to sort test</span>
                                    <MenuIcon class="w-6 h-6 text-gray-600" />
                                </div>
                                <div class="flex-1 sm:flex items-center mr-4">
                                    <div class="relative flex-1">
                                        <Link :href="route('tests.show', element.id)" class="font-semibold">
                                            {{ element.name }}
                                            <div class="absolute inset-0"></div>
                                        </Link>
                                        <div class="text-gray-600">{{ getStepCount(element) }} steps</div>
                                    </div>
                                    <div>
                                        Added {{ formatDate(element.created_at) }}
                                        <span v-if="element.user">
                                            by {{ element.user.name }}
                                        </span>
                                    </div>
                                </div>
                                <Link :href="route('tests.show', element.id)" class="text-lime-600" title="Edit">
                                    <span class="sr-only">Edit test</span>
                                    <PencilAltIcon class="w-6 h-6" />
                                </Link>
                            </div>
                        </template>
                    </Draggable>
                </div>
                <div class="text-center py-6 lg:py-12" v-else>
                    No tests added yet.
                </div>
            </div>
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
                        <jet-input-error :message="form.errors.name" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <jet-label for="description" value="Description" />
                        <textarea id="description" class="input block mt-1 w-full" v-model="form.description"></textarea>
                        <jet-input-error :message="form.errors.description" class="mt-2" />
                    </div>
                </form>
            </template>

            <template #footer>
                <jet-secondary-button @click="cancel">
                    Cancel
                </jet-secondary-button>
                <jet-button class="ml-2" @click="save">
                    Save
                </jet-button>
            </template>
        </jet-dialog-modal>
    </app-layout>
</template>

<script>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { ChevronRightIcon, PencilAltIcon, MenuIcon } from '@heroicons/vue/outline'
import Draggable from 'vuedraggable'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'

export default {
    props: ['suite', 'tests'],
    components: {
        Link,
        AppLayout,
        ChevronRightIcon,
        PencilAltIcon,
        MenuIcon,
        Draggable,
        JetDialogModal,
        JetButton,
        JetSecondaryButton,
        JetInput,
        JetInputError,
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
            Inertia.delete(route('test-suites.destroy', props.suite.id))
        }

        const getStepCount = test => {
            if (!test.steps) {
                return 0
            }
            return test.steps.trim().split(/\r\n|\r|\n/).length
        }

        const updateSort = ({ newIndex }) => {
            const item = props.tests[newIndex]
            let sort
            if (newIndex === 0) {
                sort = props.tests[1].sort_order - 1
            } else if (newIndex === props.tests.length - 1) {
                sort = props.tests[newIndex - 1].sort_order + 1
            } else {
                sort = (props.tests[newIndex - 1].sort_order + props.tests[newIndex + 1].sort_order) / 2
            }
            axios.patch(route('tests.update', item.id), {
                sort_order: sort,
            }).then(() => {
                item.sort_order = sort
            })
        }

        return {
            editing,
            form,
            cancel,
            save,
            archive,
            getStepCount,
            updateSort,
        }
    },
}
</script>
