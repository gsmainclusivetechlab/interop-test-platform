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
                                {{ testCase.data.name}}
                            </h2>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs mb-5">
                    <li class="nav-item">
                        <inertia-link
                            :href="route('admin.test-cases.show', testCase.data.id)"
                            class="nav-link"
                            v-bind:class="{'active': route().current('admin.test-cases.show')}"
                        >
                            Overview
                        </inertia-link>
                    </li>
                    <li class="nav-item">
                        <inertia-link
                            :href="route('admin.test-cases.test-steps.index', testCase.data.id)"
                            class="nav-link"
                            v-bind:class="{'active': route().current('admin.test-cases.test-steps.index')}"
                        >
                            Test Steps
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
        testCase: {
            type: Object,
            required: true,
        },
    }
};
</script>
