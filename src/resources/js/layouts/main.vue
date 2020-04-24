<template>
    <layout>
        <b-navbar class="flex-wrap py-0" tag="header" toggleable="md">
            <div class="container-fluid py-3">
                <b-navbar-toggle target="header-nav"></b-navbar-toggle>

                <div class="col-md-3 d-flex align-items-center">
                    <inertia-link :href="route('home')">
                        <img
                            src="/assets/images/logo.png"
                            class="navbar-brand-image h-5"
                            alt="Interoperability Test Platform"
                        />
                    </inertia-link>
                </div>

                <div class="col d-none d-md-block text-center text-primary">
                    <h1 class="col-login__title mb-1">
                        Inclusive Tech Lab
                    </h1>
                    <h2 class="col-login__subtitle mb-0">
                        Interoperability Test Platform
                    </h2>
                </div>

                <div class="col-md-3 d-flex justify-content-end">
                    <b-navbar-nav class="flex-row">
                        <b-nav-item-dropdown
                            right
                            no-caret
                            menu-class="dropdown-menu-arrow"
                            toggle-class="align-items-center"
                            v-if="$page.auth.user.is_admin"
                        >
                            <template v-slot:button-content>
                                <icon name="settings" />
                            </template>

                            <li>
                                <inertia-link
                                    :href="route('admin.users.index')"
                                    class="text-reset dropdown-item"
                                >
                                    Users
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link
                                    :href="route('admin.sessions.index')"
                                    class="text-reset dropdown-item"
                                >
                                    Sessions
                                </inertia-link>
                            </li>
                            <li>
                                <inertia-link
                                    :href="route('admin.scenarios.index')"
                                    class="text-reset dropdown-item"
                                >
                                    Scenarios
                                </inertia-link>
                            </li>
                        </b-nav-item-dropdown>

                        <b-nav-item-dropdown
                            right
                            no-caret
                            menu-class="dropdown-menu-arrow"
                            toggle-class="align-items-center"
                        >
                            <template v-slot:button-content>
                            <span class="avatar flex-shrink-0">
                                <icon name="user" />
                            </span>
                                <span
                                    class="ml-2 d-none d-lg-inline-block text-truncate"
                                >
                                <span class="text-default">
                                    {{ $page.auth.user.name }}
                                </span>
                            </span>
                            </template>
                            <li>
                                <inertia-link
                                    :href="route('settings.profile.edit')"
                                    class="text-reset dropdown-item"
                                >
                                    Settings
                                </inertia-link>
                            </li>
                            <b-dropdown-divider></b-dropdown-divider>
                            <li>
                                <inertia-link
                                    :href="route('logout')"
                                    method="post"
                                    class="text-reset dropdown-item"
                                >
                                    Logout
                                </inertia-link>
                            </li>
                        </b-nav-item-dropdown>
                    </b-navbar-nav>
                </div>
            </div>

            <b-collapse id="header-nav" class="border-top" is-nav>
                <div
                    class="container-fluid d-flex align-items-center justify-content-between py-1"
                >
                    <b-navbar-nav tag="nav">
                        <li
                            class="nav-item"
                            v-bind:class="{ active: route().current('home') }"
                        >
                            <inertia-link :href="route('home')" class="nav-link">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block"
                            >
                                <icon name="activity" />
                            </span>
                                <span class="nav-link-title">
                                Dashboard
                            </span>
                            </inertia-link>
                        </li>
                        <li
                            class="nav-item"
                            v-bind:class="{ active: route().current('sessions.*') }"
                        >
                            <inertia-link
                                :href="route('sessions.index')"
                                class="nav-link"
                            >
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block"
                            >
                                <icon name="box" />
                            </span>
                                <span class="nav-link-title">
                                Sessions
                            </span>
                            </inertia-link>
                        </li>
                        <li
                            class="nav-item"
                            v-bind:class="{ active: route().current('tutorials') }"
                        >
                            <inertia-link
                                :href="route('tutorials')"
                                class="nav-link"
                            >
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block"
                            >
                                <icon name="help" />
                            </span>
                                <span class="nav-link-title">
                                Tutorials
                            </span>
                            </inertia-link>
                        </li>
                        <li class="nav-item">
                            <a
                                href="https://www.gsma.com/lab"
                                class="nav-link"
                                target="_blank"
                            >
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block"
                            >
                                <icon name="link" />
                            </span>
                                <span class="nav-link-title">
                                The Lab
                            </span>
                            </a>
                        </li>
                    </b-navbar-nav>

                    <inertia-link
                        :href="route('sessions.register.create')"
                        class="btn btn-outline-primary"
                    >
                        <icon name="plus" />
                        New Session
                    </inertia-link>
                </div>
            </b-collapse>
        </b-navbar>
        <main class="content">
            <div class="container-fluid">
                <slot />
            </div>
        </main>
        <footer class="footer m-0">
            <div class="container-fluid">
                <div class="row text-center align-items-center flex-row-reverse">
                    <div class="col-lg-auto ml-lg-auto">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                <a href="https://www.gsma.com/aboutus/legal" class="link-secondary" target="_blank">
                                    Legal
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://www.gsma.com/aboutus/legal/cookie-policy" class="link-secondary" target="_blank">
                                    Cookies
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        {{ `Copyright © ${new Date().getFullYear()}` }}
                        <a href="https://www.gsma.com/" class="link-secondary" target="_blank">GSMA</a>.
                        All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </layout>
</template>

<script>
    import Layout from '@/layouts/app';

    export default {
        components: {
            Layout,
        },
    };
</script>
