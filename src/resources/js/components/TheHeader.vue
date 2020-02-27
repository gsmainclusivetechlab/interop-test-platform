<template>
    <header>
        <div class="header py-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-2">
                        <b-navbar-brand href="./" class="p-0">
                            <img
                                src="images/logo.png"
                                class="h-7"
                                alt="Interoperability Test Platform"
                            />
                        </b-navbar-brand>
                    </div>
                    <div class="col text-center text-primary">
                        <h1 class="col-login__title mb-1">
                            Inclusive Tech Lab
                        </h1>
                        <h2 class="col-login__subtitle mb-0">
                            Interoperability Test Platform
                        </h2>
                    </div>
                    <div class="col-3 d-flex ml-auto">
                        <b-navbar-nav class="ml-auto">
                            <b-nav-item-dropdown
                                class="ml-auto p-0"
                                menu-class="dropdown-menu-arrow"
                                right
                                toggle-class="pr-0 leading-none"
                            >
                                <template v-slot:button-content>
                                    <span class="avatar flex-shrink-0">
                                        <i class="fe fe-user"></i>
                                    </span>
                                    <span
                                        class="ml-2 d-none d-lg-inline-block text-truncate"
                                    >
                                        <span class="text-default"
                                            >GSMA Admin</span
                                        >
                                    </span>
                                </template>
                                <b-dropdown-item href="#">
                                    <i class="dropdown-icon fe fe-user"></i>
                                    Profile
                                </b-dropdown-item>
                                <b-dropdown-item href="#">
                                    <i class="dropdown-icon fe fe-settings"></i>
                                    Settings
                                </b-dropdown-item>
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-form
                                    action="#"
                                    method="POST"
                                    form-class="p-0"
                                >
                                    <input
                                        type="hidden"
                                        name="_token"
                                        value="C4tYxwiprKBp8vq97z6R7347IGUihYBGCwkXtpel"
                                    />
                                    <button class="dropdown-item" type="submit">
                                        <i
                                            class="dropdown-icon fe fe-log-out"
                                        ></i>
                                        Logout
                                    </button>
                                </b-dropdown-form>
                            </b-nav-item-dropdown>
                        </b-navbar-nav>
                    </div>
                    <button
                        v-b-toggle.header-nav
                        type="button"
                        aria-label="Toggle navigation"
                        aria-controls="header-nav"
                        aria-expanded="false"
                        class="header-toggler d-lg-none ml-3 ml-lg-0 navbar-toggler"
                    >
                        <span class="header-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </div>

        <b-collapse id="header-nav" class="header d-lg-flex p-0">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-3 ml-auto my-3 my-lg-0 text-lg-right">
                        <router-link
                            :to="{ name: 'sessions.register' }"
                            exact
                            class="btn btn-outline-primary"
                        >
                            <i class="fe fe-plus mr-2"></i>
                            New Session
                        </router-link>
                    </div>
                    <div class="col-lg order-lg-first">
                        <ul
                            class="nav nav-tabs border-0 flex-column flex-lg-row"
                        >
                            <li class="nav-item">
                                <router-link
                                    :to="{ name: 'dashboard' }"
                                    exact
                                    class="nav-link"
                                >
                                    <i class="fe fe-activity"></i>
                                    Dashboard
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link
                                    :to="{ name: 'sessions' }"
                                    exact
                                    class="nav-link"
                                >
                                    <i class="fe fe-box"></i>
                                    Sessions
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="#" class="nav-link">
                                    <i class="fe fe-help-circle"></i>
                                    Tutorial
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <a
                                    href="https://www.gsma.com/lab"
                                    class="nav-link"
                                    target="_blank"
                                >
                                    <i class="fe fe-link"></i>
                                    The Lab
                                </a>
                            </li>
                            <b-nav-item-dropdown
                                menu-class="dropdown-menu-arrow"
                            >
                                <template v-slot:button-content>
                                    <i class="fe fe-lock"></i>
                                    Administration
                                </template>
                                <b-dropdown-item href="#">
                                    Users
                                </b-dropdown-item>
                                <b-dropdown-item href="#">
                                    Sessions
                                </b-dropdown-item>
                            </b-nav-item-dropdown>
                        </ul>
                    </div>
                </div>
            </div>
        </b-collapse>
    </header>
</template>

<script>
import { BNavbarNav, BNavbarBrand, BNavItemDropdown } from 'bootstrap-vue';

export default {
    components: {
        BNavbarNav,
        BNavbarBrand,
        BNavItemDropdown,
    },
};
</script>

<style></style>
