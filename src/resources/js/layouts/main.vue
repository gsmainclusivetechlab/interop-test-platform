<template>
    <layout>
        <b-navbar class="flex-wrap py-0" tag="header" toggleable="md">
            <div class="container-fluid py-3">
                <b-navbar-toggle target="header-nav"></b-navbar-toggle>

                <div class="col-md-3 d-flex align-items-center">
                    <inertia-link :href="route('home')">
                        <img
                            src="/assets/images/logo.png"
                            class="navbar-brand-image h-3"
                            :alt="$t('layout.main.main-nav.title')"
                        />
                    </inertia-link>
                </div>

                <div class="col d-none d-md-block text-center text-primary">
                    <h1 class="col-login__title mb-1" style="color: black">
                        {{ $t('layout.main.main-nav.title') }}
                    </h1>
                    <h2 class="col-login__subtitle mb-0">
                        {{ $t('layout.main.main-nav.subtitle') }}
                    </h2>
                </div>

                <div class="col-md-3 d-flex justify-content-end">
                    <label
                        class="d-flex align-items-center align-self-center flex-shrink-0 toggle cursor-pointer mb-0 mr-2"
                    >
                        <input
                            class="sr-only toggle-input"
                            type="checkbox"
                            @change.prevent="$inertia.post(route('dark-mode'))"
                            :checked="$page.props.app.dark_mode"
                        />
                        <span
                            class="toggle-switch d-flex justify-content-between align-items-center rounded-pill"
                            :class="{ enabled: $page.props.app.dark_mode }"
                        >
                            <span class="toggle-switch-icon">
                                <icon name="sun" />
                            </span>
                            <span class="toggle-switch-icon">
                                <icon name="moon" />
                            </span>
                        </span>
                    </label>
                    <locale-changer class="align-self-center mr-2" />
                    <b-navbar-nav class="flex-row">
                        <b-nav-item-dropdown
                            right
                            no-caret
                            menu-class="dropdown-menu-arrow"
                            toggle-class="align-items-center"
                            class="admin-settings-dropdown mr-2"
                            v-if="
                                $page.props.auth.user.can.users.viewAny ||
                                $page.props.auth.user.can.groups.viewAny ||
                                $page.props.auth.user.can.sessions.viewAny ||
                                $page.props.auth.user.can.api_specs.viewAny ||
                                $page.props.auth.user.can.components.viewAny ||
                                $page.props.auth.user.can.use_cases.viewAny ||
                                $page.props.auth.user.can.audit_log.viewAny ||
                                $page.props.auth.user.can.message_log.viewAny ||
                                $page.props.auth.user.can.test_cases.viewAny ||
                                $page.props.auth.user.can.faqs.viewAny
                            "
                        >
                            <template v-slot:button-content>
                                <icon name="settings" />
                            </template>

                            <li v-if="$page.props.auth.user.can.users.viewAny">
                                <inertia-link
                                    :href="route('admin.users.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.users') }}
                                </inertia-link>
                            </li>
                            <li v-if="$page.props.auth.user.can.groups.viewAny">
                                <inertia-link
                                    :href="route('admin.groups.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.groups') }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.sessions.viewAny
                                "
                            >
                                <inertia-link
                                    :href="route('admin.sessions.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.sessions') }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.sessions.viewAny
                                "
                            >
                                <inertia-link
                                    :href="
                                        route('admin.compliance-sessions.index')
                                    "
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.cert-sessions') }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.api_specs.viewAny
                                "
                            >
                                <inertia-link
                                    :href="route('admin.api-specs.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.api-specs') }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.components.viewAny
                                "
                            >
                                <inertia-link
                                    :href="route('admin.components.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.components') }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.use_cases.viewAny
                                "
                            >
                                <inertia-link
                                    :href="route('admin.use-cases.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.use-cases') }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.scenarios.viewAny
                                "
                            >
                                <inertia-link
                                    :href="route('admin.scenarios.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.scenarios') }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.test_cases.viewAny
                                "
                            >
                                <inertia-link
                                    :href="route('admin.test-cases.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.test-cases') }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.message_log
                                        .viewAny
                                "
                            >
                                <inertia-link
                                    :href="route('admin.logs.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.logs') }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.questionnaire
                                        .create
                                "
                            >
                                <inertia-link
                                    :href="route('admin.questionnaire.import')"
                                    class="text-reset dropdown-item"
                                >
                                    {{
                                        $t(
                                            'layout.main.menu.import-question-def'
                                        )
                                    }}
                                </inertia-link>
                            </li>
                            <li
                                v-if="
                                    $page.props.auth.user.can.implicit_suts
                                        .viewAny
                                "
                            >
                                <inertia-link
                                    :href="route('admin.implicit-suts.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.implicit-sut') }}
                                </inertia-link>
                            </li>
                            <li v-if="$page.props.auth.user.can.faqs.viewAny">
                                <inertia-link
                                    :href="route('admin.faqs.index')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.menu.faqs') }}
                                </inertia-link>
                            </li>
                        </b-nav-item-dropdown>

                        <b-nav-item-dropdown
                            right
                            no-caret
                            menu-class="dropdown-menu-arrow"
                            toggle-class="align-items-center pr-0"
                            class="user-settings-dropdown"
                        >
                            <template v-slot:button-content>
                                <span class="avatar flex-shrink-0">
                                    <icon name="user" />
                                </span>
                                <span
                                    class="ml-2 d-none d-lg-inline-block text-truncate"
                                >
                                    <span class="text-default">
                                        {{
                                            string(
                                                $page.props.auth.user.name
                                            ).truncate(30)
                                        }}
                                    </span>
                                </span>
                            </template>
                            <li>
                                <inertia-link
                                    :href="route('settings.profile')"
                                    class="text-reset dropdown-item"
                                >
                                    {{ $t('layout.main.user-menu.settings') }}
                                </inertia-link>
                            </li>
                            <b-dropdown-divider></b-dropdown-divider>
                            <li>
                                <inertia-link
                                    :href="route('logout')"
                                    method="post"
                                    class="text-reset dropdown-item"
                                    as="button"
                                >
                                    {{ $t('layout.main.user-menu.logout') }}
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
                            :class="{ active: route().current('home') }"
                        >
                            <inertia-link
                                :href="route('home')"
                                class="nav-link"
                            >
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"
                                >
                                    <icon name="activity" />
                                </span>
                                <span class="nav-link-title">
                                    {{ $t('layout.main.main-nav.dashboard') }}
                                </span>
                            </inertia-link>
                        </li>
                        <li
                            class="nav-item"
                            :class="{
                                active: route().current('sessions.*'),
                            }"
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
                                    {{ $t('layout.main.main-nav.sessions') }}
                                </span>
                            </inertia-link>
                        </li>
                        <li
                            class="nav-item"
                            :class="{
                                active: route().current('groups.*'),
                            }"
                        >
                            <inertia-link
                                :href="route('groups.index')"
                                class="nav-link"
                            >
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"
                                >
                                    <icon name="users" />
                                </span>
                                <span class="nav-link-title">
                                    {{ $t('layout.main.main-nav.groups') }}
                                </span>
                            </inertia-link>
                        </li>
                        <li
                            class="nav-item"
                            :class="{
                                active: route().current('tutorials'),
                            }"
                        >
                            <inertia-link
                                :href="route('tutorials')"
                                class="nav-link"
                            >
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"
                                >
                                    <icon name="info-circle" />
                                </span>
                                <span class="nav-link-title">
                                    {{ $t('layout.main.main-nav.tutorials') }}
                                </span>
                            </inertia-link>
                        </li>
                        <li class="nav-item">
                            <a
                                href="https://docs.itp.gsmainclusivetechlab.io/"
                                class="nav-link"
                                target="_blank"
                            >
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"
                                >
                                    <icon name="book" />
                                </span>
                                <span class="nav-link-title">
                                    {{ $t('layout.main.main-nav.doc') }}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                :href="$t('layout.main.main-nav.external-link')"
                                class="nav-link"
                                target="_blank"
                            >
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"
                                >
                                    <icon name="link" />
                                </span>
                                <span class="nav-link-title">{{
                                    $t('layout.main.main-nav.external')
                                }}</span>
                            </a>
                        </li>
                        <li
                            class="nav-item"
                            :class="{
                                active: route().current('faq'),
                            }"
                            v-if="$page.props.auth.user.can.faqs.viewContent"
                        >
                            <inertia-link :href="route('faq')" class="nav-link">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"
                                >
                                    <icon name="help" />
                                </span>
                                <span class="nav-link-title">
                                    {{ $t('layout.main.main-nav.faq') }}
                                </span>
                            </inertia-link>
                        </li>
                    </b-navbar-nav>

                    <inertia-link
                        :href="route('sessions.register.type')"
                        v-if="$page.props.app.available_session_modes_count > 0"
                        class="btn btn-outline-primary"
                    >
                        <icon name="plus" />
                        {{ $t('layout.main.buttons.new-session') }}
                    </inertia-link>
                </div>
            </b-collapse>
        </b-navbar>
        <main class="content">
            <div class="container-fluid d-flex flex-column">
                <slot />
            </div>
        </main>
        <footer class="footer m-0">
            <div class="container-fluid">
                <div
                    class="row text-center align-items-center flex-row-reverse"
                >
                    <div class="col-lg-auto ml-lg-auto">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                <a
                                    :href="$t('layout.main.footer.link')"
                                    class="link-secondary"
                                    target="_blank"
                                >
                                    Legal
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a
                                    :href="
                                        $t('layout.main.footer.cookies-link')
                                    "
                                    class="link-secondary"
                                    target="_blank"
                                >
                                    Cookies
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        {{ `Copyright © ${new Date().getFullYear()}` }}
                        <a
                            :href="$t('layout.main.footer.copyright-link')"
                            class="link-secondary"
                            target="_blank"
                        >
                            {{ $t('layout.main.footer.copyright-name') }} </a
                        >. All rights reserved.
                        <span v-if="$page.props.app.platform_version">
                            Version {{ $page.props.app.platform_version }}
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </layout>
</template>

<script>
    import Layout from '@/layouts/app';
    import LocaleChanger from '@/components/locale-changer';

    export default {
        components: {
            Layout,
            LocaleChanger,
        },
    };
</script>
