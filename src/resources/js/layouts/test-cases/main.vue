<template>
    <layout>
        <div class="row">
            <div class="col-12">
                <div class="page-pretitle font-weight-normal">
                    <breadcrumb
                        class="breadcrumb-bullets"
                        :items="breadcrumbs"
                    ></breadcrumb>
                </div>
                <div class="page-header">
                    <h1 class="page-title">
                        <b>{{ testCase.name }}</b>
                    </h1>
                </div>
                <div class="d-flex align-items-baseline border-bottom mb-4">
                    <ul class="nav nav-tabs mx-0 border-0">
                        <li
                            class="nav-item"
                            v-for="(link, i) in navLinks"
                            :key="i"
                        >
                            <inertia-link
                                :href="route(link.route, testCase.id)"
                                class="nav-link rounded-0"
                                :class="{
                                    active: link.condition,
                                }"
                            >
                                {{ link.title }}
                            </inertia-link>
                        </li>
                    </ul>
                </div>
                <slot />
            </div>
        </div>
    </layout>
</template>
<script>
import Layout from '@/layouts/main';

export default {
    components: {
        Layout,
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
        breadcrumbs: {
            type: Array,
            required: false,
            default: function () {
                return [
                    {
                        name: 'Test Cases',
                        url: route('admin.test-cases.index'),
                    },
                    { name: this.testCase.name },
                ];
            },
        },
    },
    data() {
        return {
            navLinks: [
                {
                    title: 'Info',
                    route: 'admin.test-cases.info.show',
                    condition:
                        route().current('admin.test-cases.info.show') ||
                        route().current('admin.test-cases.info.edit'),
                },
                {
                    title: 'Test steps',
                    route: 'admin.test-cases.test-steps.index',
                    condition: route().current(
                        'admin.test-cases.test-steps.index'
                    ),
                },
                {
                    title: 'Groups',
                    route: 'admin.test-cases.groups.index',
                    condition:
                        route().current('admin.test-cases.groups.index') ||
                        route().current('admin.test-cases.groups.edit'),
                },
                {
                    title: 'Versions',
                    route: 'admin.test-cases.versions.index',
                    condition: route().current(
                        'admin.test-cases.versions.index'
                    ),
                },
            ],
        };
    },
};
</script>
