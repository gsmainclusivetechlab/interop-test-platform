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
                        <b>{{ group.name }}</b>
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
                                        :href="route('groups.show', group.id)"
                                        class="nav-link rounded-0"
                                        :class="{
                                            active: route().current(
                                                'groups.show'
                                            ),
                                        }"
                                    >
                                        Sessions
                                    </inertia-link>
                                </li>
                                <li class="nav-item">
                                    <inertia-link
                                        :href="
                                            route(
                                                'groups.users.index',
                                                group.id
                                            )
                                        "
                                        class="nav-link rounded-0"
                                        :class="{
                                            active: route().current(
                                                'groups.users.*'
                                            ),
                                        }"
                                    >
                                        Members
                                    </inertia-link>
                                </li>
                                <li class="nav-item" v-if="group.can.admin">
                                    <inertia-link
                                        :href="
                                            route(
                                                'groups.user-invitations.index',
                                                group.id
                                            )
                                        "
                                        class="nav-link rounded-0"
                                        :class="{
                                            active: route().current(
                                                'groups.user-invitations.*'
                                            ),
                                        }"
                                    >
                                        Invitations
                                    </inertia-link>
                                </li>
                                <li class="nav-item">
                                    <inertia-link
                                        :href="
                                            route(
                                                'groups.environments.index',
                                                group.id
                                            )
                                        "
                                        class="nav-link rounded-0"
                                        :class="{
                                            active: route().current(
                                                'groups.environments.*'
                                            ),
                                        }"
                                    >
                                        Environments
                                    </inertia-link>
                                </li>
                                <li class="nav-item">
                                    <inertia-link
                                        :href="
                                            route(
                                                'groups.certificates.index',
                                                group.id
                                            )
                                        "
                                        class="nav-link rounded-0"
                                        :class="{
                                            active: route().current(
                                                'groups.certificates.*'
                                            ),
                                        }"
                                    >
                                        Certificates
                                    </inertia-link>
                                </li>
                                <li
                                    v-if="group.can.admin"
                                    class="nav-item"
                                >
                                    <inertia-link
                                        :href="
                                            route(
                                                'groups.plugins.index',
                                                group.id
                                            )
                                        "
                                        class="nav-link rounded-0"
                                        :class="{
                                            active: route().current(
                                                'groups.plugins.*'
                                            ),
                                        }"
                                    >
                                        Simulator Plugins
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
            title: this.group.name,
        };
    },
    props: {
        group: {
            type: Object,
            required: true,
        },
        breadcrumbs: {
            type: Array,
            required: false,
            default: function () {
                return [
                    { name: 'Groups', url: route('groups.index') },
                    { name: this.group.name },
                ];
            },
        },
    },
};
</script>
