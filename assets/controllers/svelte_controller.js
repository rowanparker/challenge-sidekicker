import { Controller } from 'stimulus';

import {svelteComponents} from "../svelteComponents";

export default class extends Controller {
    connect() {
        if (typeof this.element.dataset === 'undefined') {
            console.error('No dataset available for element.');
            return;
        }

        const component = this.element.dataset.svelteComponent;

        if (typeof component === 'undefined') {
            console.error('No svelte-component value found in dataset for element.');
            return;
        }

        const props = (typeof this.element.dataset.svelteProps !== 'undefined')
            ? JSON.parse(this.element.dataset.svelteProps)
            : null;

        // Load Svelte component
        new svelteComponents[component]({
            target: this.element,
            props: props,
        })
    }
}
