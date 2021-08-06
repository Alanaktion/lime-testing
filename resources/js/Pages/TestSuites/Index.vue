<template>
    <app-layout title="Test Suites">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Test Suites
            </h2>
        </template>

        <div class="container py-4 lg:py-8">
            <div
                class="bg-white shadow overflow-hidden border border-gray-200 sm:rounded-lg"
                v-if="testSuites.length"
            >
                <div
                    class="px-6 py-4"
                    v-for="suite in testSuites"
                    :key="suite.id"
                >
                    <Link :href="`/test-suites/${suite.id}`" class="text-indigo-600 font-semibold">
                        {{ suite.name }}
                    </Link>
                    <div class="text-gray-600">{{ suite.tests_count }} tests</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-6 lg:py-12" v-else>
                <p class="text-center">
                    No test suites
                </p>
                <p class="text-gray-600 text-center">
                    Get started by creating your first test suite.
                </p>
                <div class="text-center mt-4 lg:mt-6">
                    <jet-button @click.native="showCreateModal = true">
                        New test suite
                    </jet-button>
                </div>
            </div>
        </div>

        <jet-dialog-modal :show="showCreateModal" @close="showCreateModal = false">
            <template #title>
                Create Test Suite
            </template>

            <template #content>
                <form @submit.prevent="submit">
                    <div>
                        <jet-label for="name" value="Name" />
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                    </div>

                    <div class="mt-4">
                        <jet-label for="description" value="Description" />
                        <textarea id="description" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" @input="$emit('update:modelValue', $event.target.value)" v-model="form.description"></textarea>
                    </div>
                </form>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="showCreateModal = false">
                    Cancel
                </jet-secondary-button>
                <jet-button type="submit" class="ml-2" @click.native="submit">
                    Create
                </jet-button>
            </template>
        </jet-dialog-modal>
    </app-layout>
</template>

<script>
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'

export default {
    props: ['testSuites'],
    components: {
        Link,
        AppLayout,
        JetDialogModal,
        JetButton,
        JetSecondaryButton,
        JetInput,
        JetLabel,
    },
    setup() {
        const showCreateModal = ref(false)
        const form = reactive({
            name: null,
            description: null,
        })

        const submit = () => {
            Inertia.post('/test-suites', form)
        }

        return {
            showCreateModal,
            form,
            submit,
        }
    },
}
</script>
