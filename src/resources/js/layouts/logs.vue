<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <strong>Logging</strong>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card col-md-2">
                <b-navbar-nav tag="nav" class="mt-4">
                    <ul>
                        <li
                            class="nav-item"
                            :class="{
                                active: route().current('admin.logs.index'),
                            }"
                        >
                            <inertia-link
                                :href="route('admin.logs.index')"
                                class="text-reset nav-link py-2"
                            >
                                {{ $t('layout.main.menu.message-log') }}
                            </inertia-link>
                        </li>
                        <li
                            class="nav-item"
                            :class="{
                                active: route().current('admin.logs.audit-log'),
                            }"
                        >
                            <inertia-link
                                :href="route('admin.logs.audit-log')"
                                class="text-reset nav-link py-2"
                            >
                                {{ $t('layout.main.menu.audit-log') }}
                            </inertia-link>
                        </li>
                        <li
                            class="nav-item"
                            :class="{
                                active: route().current(
                                    'admin.logs.application-log'
                                ),
                            }"
                        >
                            <inertia-link
                                :href="route('admin.logs.application-log')"
                                class="text-reset nav-link py-2"
                            >
                                {{ $t('layout.main.menu.application-log') }}
                            </inertia-link>
                        </li>
                        <li
                            class="nav-item"
                            :class="{
                                active: route().current('admin.logs.queue-log'),
                            }"
                        >
                            <inertia-link
                                :href="route('admin.logs.queue-log')"
                                class="text-reset nav-link py-2"
                            >
                                {{ $t('layout.main.menu.queue-log') }}
                            </inertia-link>
                        </li>
                        <li
                            class="nav-item"
                            :class="{
                                active:
                                    route().current(
                                        'admin.logs.nginx-access-log'
                                    ) ||
                                    route().current(
                                        'admin.logs.nginx-error-log'
                                    ),
                            }"
                        >
                            <span
                                class="text-reset dropdown-toggle py-2"
                                v-b-toggle="'sidebar-nginx-nav'"
                            >
                                {{ $t('layout.main.menu.nginx-logs') }}
                            </span>
                            <b-collapse id="sidebar-nginx-nav" visible>
                                <b-navbar-nav tag="nav">
                                    <ul>
                                        <li
                                            class="nav-item"
                                            :class="{
                                                active: route().current(
                                                    'admin.logs.nginx.access-log'
                                                ),
                                            }"
                                        >
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'admin.logs.nginx.access-log'
                                                    )
                                                "
                                                class="text-reset nav-link py-2"
                                            >
                                                {{
                                                    $t(
                                                        'layout.main.menu.nginx.access-log'
                                                    )
                                                }}
                                            </inertia-link>
                                        </li>
                                        <li
                                            class="nav-item"
                                            :class="{
                                                active: route().current(
                                                    'admin.logs.nginx.error-log'
                                                ),
                                            }"
                                        >
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'admin.logs.nginx.error-log'
                                                    )
                                                "
                                                class="text-reset nav-link py-2"
                                            >
                                                {{
                                                    $t(
                                                        'layout.main.menu.nginx.error-log'
                                                    )
                                                }}
                                            </inertia-link>
                                        </li>
                                    </ul>
                                </b-navbar-nav>
                            </b-collapse>
                        </li>
                    </ul>
                </b-navbar-nav>
            </div>
            <div class="col-md-10">
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
    };
</script>
