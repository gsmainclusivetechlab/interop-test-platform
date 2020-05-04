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
                            {{ testCase.name}}
                        </h2>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs mb-5">
                <li class="nav-item">
                    <inertia-link
                        :href="route('admin.test-cases.show', testCase.id)"
                        class="nav-link rounded-0"
                        v-bind:class="{'active': route().current('admin.test-cases.show')}"
                    >
                        Overview
                    </inertia-link>
                </li>
                <li class="nav-item">
                    <inertia-link
                        :href="route('admin.test-cases.test-steps.index', testCase.id)"
                        class="nav-link rounded-0"
                        v-bind:class="{'active': route().current('admin.test-cases.test-steps.*')}"
                    >
                        Test Steps
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
                title: this.testCase.name,
            }
        },
        props: {
            testCase: {
                type: Object,
                required: true,
            },
        }
    };
</script>
