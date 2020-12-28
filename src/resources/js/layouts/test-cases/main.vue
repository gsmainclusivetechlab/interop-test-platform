<template>
    <layout>
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="page-pretitle">
                                {{ $t('page.pre-title') }}
                            </div>
                            <div class="page-pretitle font-weight-normal">
                                <breadcrumb
                                    class="breadcrumb-bullets"
                                    :items="breadcrumbs"
                                ></breadcrumb>
                            </div>
                            <h2 class="page-title">
                                <b>{{ testCase.name }}</b>
                            </h2>
                        </div>
                    </div>
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
    },
    computed: {
        breadcrumbs() {
            return [
                {
                    name: this.$t('breadcrumb.test-cases'),
                    url: route('admin.test-cases.index'),
                },
                { name: this.testCase.name },
            ];
        },
        navLinks() {
            return [
                {
                    title: this.$t('breadcrumb.info'),
                    route: 'admin.test-cases.info.show',
                    condition:
                        route().current('admin.test-cases.info.show') ||
                        route().current('admin.test-cases.info.edit'),
                },
                {
                    title: this.$t('breadcrumb.test-steps'),
                    route: 'admin.test-cases.test-steps.index',
                    condition:
                        route().current('admin.test-cases.test-steps.index') ||
                        route().current('admin.test-cases.test-steps.create') ||
                        route().current('admin.test-cases.test-steps.edit'),
                },
                {
                    title: this.$t('breadcrumb.groups'),
                    route: 'admin.test-cases.groups.index',
                    condition:
                        route().current('admin.test-cases.groups.index') ||
                        route().current('admin.test-cases.groups.edit'),
                },
                {
                    title: this.$t('breadcrumb.versions'),
                    route: 'admin.test-cases.versions.index',
                    condition: route().current(
                        'admin.test-cases.versions.index'
                    ),
                },
            ];
        },
    },
};
</script>
<i18n src="@locales/pages/admin/index.json"></i18n>
<i18n src="@locales/layout/test-cases/main.json"></i18n>
