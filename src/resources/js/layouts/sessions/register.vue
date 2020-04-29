<template>
    <layout>
        <div class="page-header">
            <h1 class="page-title text-center">
                <b>Create new session</b>
            </h1>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="steps steps-counter steps-primary mb-5">
                        <span
                            class="step-item"
                            :class="{
                                active:
                                    route().current(
                                        'sessions.register.create'
                                    ) ||
                                    route().current('sessions.register.edit')
                            }"
                        >
                            <span class="d-inline-block mt-2">
                                Session info
                            </span>
                        </span>
                        <span
                            class="step-item"
                            :class="{
                                active: route().current(
                                    'sessions.register.config'
                                )
                            }"
                        >
                            <span class="d-inline-block mt-2">
                                Configure components
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col">
                    <diagram>
                        graph LR;
                        <template v-for="component in scenario.components.data">
                            {{ component.id }}({{ component.name }});
                            <template v-for="connection in component.connections">
                                {{ component.id }}
                                {{
                                    component.simulated && connection.simulated
                                        ? '-->'
                                        : '-.->'
                                }}
                                {{ connection.id }};
                            </template>
                        </template>
                    </diagram>
                    <div class="d-flex justify-content-center mt-1">
                        <div class="d-inline-flex align-items-center mx-2">
                            <span class="ic-arrow-right mr-2"></span>
                            <small>Simulated</small>
                        </div>
                        <div class="d-inline-flex align-items-center mx-2">
                            <span
                                class="ic-arrow-right mr-2 border-dashed"
                            ></span>
                            <small>Not Simulated</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <slot />
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import Diagram from '@/components/diagram';

export default {
    components: {
        Layout,
        Diagram
    },
    props: {
        scenario: {
            type: Object,
            required: true
        }
    }
};
</script>
