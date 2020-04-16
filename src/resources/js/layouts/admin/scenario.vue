<template>
    <layout>
        <div class="col-md-12">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="page-pretitle">
                            Administration
                        </div>
                        <h2 class="page-title">
                            {{ scenario.name}}
                        </h2>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs mb-5">
                <li class="nav-item">
                    <inertia-link
                        :href="route('admin.scenarios.show', scenario.id)"
                        class="nav-link rounded-0"
                        v-bind:class="{'active': route().current('admin.scenarios.show')}"
                    >
                        Overview
                    </inertia-link>
                </li>
                <li class="nav-item">
                    <inertia-link
                        :href="route('admin.scenarios.components.index', scenario.id)"
                        class="nav-link rounded-0"
                        v-bind:class="{'active': route().current('admin.scenarios.components.*')}"
                    >
                        Components
                    </inertia-link>
                </li>
                <li class="nav-item">
                    <inertia-link
                        :href="route('admin.scenarios.use-cases.index', scenario.id)"
                        class="nav-link rounded-0"
                        v-bind:class="{'active': route().current('admin.scenarios.use-cases.*')}"
                    >
                        Use Cases
                    </inertia-link>
                </li>
                <li class="nav-item">
                    <inertia-link
                        :href="route('admin.scenarios.test-cases.index', scenario.id)"
                        v-bind:class="{'active': route().current('admin.scenarios.test-cases.*')}"
                        class="nav-link rounded-0"
                    >
                        Test Cases
                    </inertia-link>
                </li>
            </ul>
            <slot></slot>
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/main';

    export default {
        components: {
            Layout,
        },
        metaInfo() {
            return {
                title: this.scenario.name,
            }
        },
        props: {
            scenario: {
                type: Object,
                required: true,
            },
        }
    };
</script>
