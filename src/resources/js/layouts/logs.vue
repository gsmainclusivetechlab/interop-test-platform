<template>
    <layout>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <b-navbar-nav>
                        <li>
                            <inertia-link
                                :href="route('admin.logs.index')"
                                class="text-reset dropdown-item"
                            >
                                {{ $t('layout.main.menu.message-log') }}
                            </inertia-link>
                        </li>
                        <li>
                            <inertia-link
                                :href="route('admin.logs.audit-log')"
                                class="text-reset dropdown-item"
                            >
                                {{ $t('layout.main.menu.audit-log') }}
                            </inertia-link>
                        </li>
                        <li>
                            <inertia-link
                                :href="route('admin.logs.application-log')"
                                class="text-reset dropdown-item"
                            >
                                {{ $t('layout.main.menu.application-log') }}
                            </inertia-link>
                        </li>
                        <li>
                            <inertia-link
                                :href="route('admin.logs.queue-log')"
                                class="text-reset dropdown-item"
                            >
                                {{ $t('layout.main.menu.queue-log') }}
                            </inertia-link>
                        </li>
                        <!--                        <li>-->
                        <!--                            {{ $t('layout.main.menu.nginx-logs')}}-->
                        <!--                            <ul>-->
                        <!--                                <li>-->
                        <!--                                    <inertia-link-->
                        <!--                                        :href="route('admin.logs.queue-log')"-->
                        <!--                                        class="text-reset dropdown-item"-->
                        <!--                                    >-->
                        <!--                                        {{ $t('layout.main.menu.queue-log') }}-->
                        <!--                                    </inertia-link>-->
                        <!--                                </li>-->
                        <!--                                <li>-->
                        <!--                                    <inertia-link-->
                        <!--                                        :href="route('admin.logs.queue-log')"-->
                        <!--                                        class="text-reset dropdown-item"-->
                        <!--                                    >-->
                        <!--                                        {{ $t('layout.main.menu.queue-log') }}-->
                        <!--                                    </inertia-link>-->
                        <!--                                </li>-->
                        <!--                            </ul>-->
                        <!--                        </li>-->
                    </b-navbar-nav>
                </div>
            </div>
            <div class="col-md-9">
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
