<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="page-pretitle">
                                Administration
                            </div>
                            <h2 class="page-title">
                                {{ scenario.data.name}}
                            </h2>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs mb-5">
                    <li class="nav-item">
                        <inertia-link
                            :href="route('admin.scenarios.show', scenario.data.id)"
                            class="nav-link"
                            v-bind:class="{'active': route().current('admin.scenarios.show')}"
                        >
                            Overview
                        </inertia-link>
                    </li>
                    <li class="nav-item">
                        <inertia-link
                            :href="route('admin.scenarios.components.index', scenario.data.id)"
                            class="nav-link"
                            v-bind:class="{'active': route().current('admin.scenarios.components.index')}"
                        >
                            Components
                        </inertia-link>
                    </li>
                    <li class="nav-item">
                        <inertia-link
                            :href="route('admin.scenarios.use-cases.index', scenario.data.id)"
                            class="nav-link"
                            v-bind:class="{'active': route().current('admin.scenarios.use-cases.index')}"
                        >
                            Use Cases
                        </inertia-link>
                    </li>
                    <li class="nav-item">
                        <inertia-link
                            :href="route('admin.scenarios.test-cases.index', scenario.data.id)"
                            v-bind:class="{'active': route().current('admin.scenarios.test-cases.index')}"
                            class="nav-link"
                        >
                            Test Cases
                        </inertia-link>
                    </li>
                </ul>
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        scenario: {
            type: Object,
            required: true,
        },
    }
};
</script>
