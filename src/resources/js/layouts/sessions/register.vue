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
                    <div class="steps steps-counter steps-primary">
                        <span
                            class="step-item"
                            :class="{
                                active: route().current(
                                    'sessions.register.create'
                                )
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
                    <diagram :components="$page.scenario.components.data" />

                    <div class="d-flex justify-content-center">
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
    }
};
</script>
