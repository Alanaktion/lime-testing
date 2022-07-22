<template>
    <Head :title="$page.props.appName" />

    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <div class="relative pt-6 px-4 sm:px-6 lg:px-8">
                    <nav class="relative flex items-center justify-between sm:h-10 lg:justify-start" aria-label="Global">
                        <a href="/">
                            <span class="sr-only">{{ $page.props.appName }}</span>
                            <JetApplicationMark class="h-8 w-auto sm:h-10" />
                        </a>
                    </nav>
                </div>

                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block text-lime-500 xl:inline">Lime</span>
                            {{ ' ' }}
                            <span class="block xl:inline">Testing Suite</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Easily manage your test cases, run them, and keep track of your results.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow sm:mr-3" v-if="route().has('register')">
                                <a :href="route('register')" class="w-full flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-lime-500 hover:bg-lime-600 md:py-4 md:text-lg md:px-10 transition">
                                    Sign up
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0">
                                <a :href="route('login')" class="w-full flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-lime-700 bg-lime-100 hover:bg-lime-200 md:py-4 md:text-lg md:px-10 transition">
                                    Log in
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="A team of people working together on a software project at a table with MacBook Pro notebook computers." />
        </div>
    </div>

    <div class="py-12 xl:py-20 bg-gray-50">
        <div class="container px-4">
            <div class="lg:text-center">
                <h2 class="text-base text-lime-600 font-semibold tracking-wide uppercase">Test Suites</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Organize your testing
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Collect your test cases in test suites, and run them all together.
                </p>
            </div>

            <div class="mt-10 xl:mt-16">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <div v-for="feature in features" :key="feature.name" class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-lime-100 text-lime-600 shadow-sm">
                                <component :is="feature.icon" class="h-6 w-6" aria-hidden="true" />
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ feature.name }}</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            {{ feature.description }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { BadgeCheckIcon, ClipboardListIcon, CollectionIcon, UserGroupIcon } from '@heroicons/vue/outline'
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'

const features = [
  {
    name: 'Test Suites',
    description: 'Keep your tests organized in collections, known as test suites. They can be a specific application, feature, or whatever you need.',
    icon: CollectionIcon,
  },
  {
    name: 'Teams',
    description: 'Manage teams of users, each with their own test suites. You can designate users as testers, allowing them to run tests but not manage the team.',
    icon: UserGroupIcon,
  },
  {
    name: 'Tests',
    description: 'Each test can include a description and a list of steps to complete it, ensuring your testers know exactly what actions to perform.',
    icon: ClipboardListIcon,
  },
  {
    name: 'Results',
    description: 'Quickly see the current testing status of all of your test suites, and easily access the complete testing history with each test result.',
    icon: BadgeCheckIcon,
  },
]
export default {
    components: {
        Head,
        Link,
        Popover,
        PopoverButton,
        PopoverPanel,
        JetApplicationMark,
    },
    setup() {
        return {
            features,
        }
    },
}
</script>
