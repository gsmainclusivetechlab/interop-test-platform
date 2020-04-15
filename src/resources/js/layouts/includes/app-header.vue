<template>
    <b-navbar class="flex-wrap py-0" tag="header" toggleable="md">
        <div class="container-fluid py-2">
            <b-navbar-toggle target="header-nav"></b-navbar-toggle>

            <a href="#" class="p-0 pr-md-3">
                <img
                    src="/assets/images/logo.png"
                    class="navbar-brand-image h-5"
                    alt="Interoperability Test Platform"
                />
            </a>

            <div class="d-none d-md-block text-center text-primary">
                <h1 class="col-login__title mb-1">
                    Inclusive Tech Lab
                </h1>
                <h2 class="col-login__subtitle mb-0">
                    Interoperability Test Platform
                </h2>
            </div>

            <b-navbar-nav>
                <b-nav-item-dropdown
                    right
                    no-caret
                    menu-class="dropdown-menu-arrow"
                    toggle-class="align-items-center"
                >
                    <template v-slot:button-content>
                        <icon name="settings" />
                    </template>

                    <li>
                        <inertia-link
                            :href="route('home')"
                            class="text-reset dropdown-item"
                        >
                            Users
                        </inertia-link>
                    </li>
                    <li>
                        <inertia-link
                            :href="route('home')"
                            class="text-reset dropdown-item"
                        >
                            Sessions
                        </inertia-link>
                    </li>
                    <li>
                        <inertia-link
                            :href="route('home')"
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
                            <span class="text-default">GSMA User</span>
                        </span>
                    </template>
                    <li>
                        <inertia-link
                            :href="route('home')"
                            class="text-reset dropdown-item"
                        >
                            Settings
                        </inertia-link>
                    </li>
                    <b-dropdown-divider></b-dropdown-divider>
                    <li>
                        <inertia-link
                            :href="route('logout')"
                            class="text-reset dropdown-item"
                        >
                            Logout
                        </inertia-link>
                    </li>
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </div>

        <b-collapse id="header-nav" class="border-top" is-nav>
            <div
                class="container-fluid d-flex align-items-center justify-content-between py-1"
            >
                <b-navbar-nav tag="nav">
                    <li class="nav-item active">
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
                    <li class="nav-item">
                        <inertia-link :href="route('home')" class="nav-link">
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
                    <li class="nav-item">
                        <inertia-link :href="route('home')" class="nav-link">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block"
                            >
                                <icon name="link" />
                            </span>
                            <span class="nav-link-title">
                                The Lab
                            </span>
                        </inertia-link>
                    </li>
                </b-navbar-nav>

                <inertia-link
                    :href="route('home')"
                    class="btn btn-outline-primary"
                >
                    <icon name="plus" />
                    New Session
                </inertia-link>
            </div>
        </b-collapse>
    </b-navbar>
</template>
