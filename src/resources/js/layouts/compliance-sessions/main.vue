<template>
    <layout>
        <div class="row">
            <div class="col-md-12">
                <div class="row pb-2 align-items-center">
                    <div class="page-pretitle font-weight-normal">
                        <breadcrumb
                            class="breadcrumb-bullets"
                            :items="breadcrumbs"
                        ></breadcrumb>
                    </div>
                    <h1 class="page-title mw-100 mr-2 text-break">
                        <b>{{ title }}</b>
                    </h1>
                </div>
                <div class="row">
                    <div class="col">
                        <div
                            class="d-flex align-items-baseline border-bottom mb-4"
                        >
                            <ul class="nav nav-tabs mx-0 border-0">
                                <li class="nav-item">
                                    <inertia-link
                                        :href="
                                            route(
                                                'admin.compliance-sessions.index'
                                            )
                                        "
                                        class="nav-link rounded-0"
                                        v-bind:class="{
                                            active:
                                                route().current(
                                                    'admin.compliance-sessions.index'
                                                ) && status === undefined,
                                        }"
                                    >
                                        All
                                    </inertia-link>
                                </li>
                                <li class="nav-item">
                                    <inertia-link
                                        :href="
                                            route(
                                                'admin.compliance-sessions.index',
                                                { status: 'in_verification' }
                                            )
                                        "
                                        class="nav-link rounded-0"
                                        v-bind:class="{
                                            active:
                                                route().current(
                                                    'admin.compliance-sessions.index'
                                                ) &&
                                                status === 'in_verification',
                                        }"
                                    >
                                        In verification
                                    </inertia-link>
                                </li>
                            </ul>
                        </div>
                        <slot />
                    </div>
                </div>
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
    metaInfo() {
        return {
            title: this.title,
        };
    },
    props: {
        title: {
            type: String,
            required: true,
        },
        status: {
            type: String,
            required: false,
        },
        breadcrumbs: {
            type: Array,
            required: false,
            default: function () {
                return [
                    {
                        name: 'Compliance sessions',
                        url: route('admin.compliance-sessions.index'),
                    },
                    { name: this.title },
                ];
            },
        },
    },
};
</script>
