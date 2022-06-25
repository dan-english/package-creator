import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { ZiggyVue } from 'ziggy';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: (name) => {

        const matched = /@(.*)::/.exec(name);

        if (matched === null) {
            return require(`./Pages/${name}`).default;
        }

        const module = matched[1];
        const pageName = name.replace(matched[0], "");
        // return require(`../../packages/${module}/Assets/Pages/${pageName}.vue`)


        // return require(`./Pages/Modules/${module}/${pageName}.vue`) //inside the pages folder
        // return require(`./Modules/${module}/${pageName}.vue`)  //inside the js folder

        // return require(`../Modules/${module}/${pageName}.vue`) // inside the resources folder
        // return require(`../../Modules/${module}/${pageName}.vue`) //in root of project
        // return require(`../../Modules/${module}/${pageName}.vue`) //in root of project
        // return require(`../../packages/Modules/${module}/${pageName}.vue`) //in root of project

        // return require(`../../packages/Webhooks/Modules/${module}/${pageName}.vue`) //in root of project
        // return require(`../../packages/${module}/Modules/${module}/${pageName}.vue`) //in root of project

        // return require(`../../packages/${module}/Assets/${module}/${pageName}.vue`) //in root of project
        return require(`../../packages/${module}/Assets/Pages/${pageName}.vue`) //in root of project


    },


    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
