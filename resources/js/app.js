require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

const formatDate = dateStr => {
    const date = new Date(dateStr);
    return date.toLocaleDateString();
};

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Lime';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route, formatDate } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#65A30D' });
