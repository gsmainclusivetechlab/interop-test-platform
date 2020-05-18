<template>
    <layout>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="page-header">
                        <h2 class="page-title">
                            <b>Settings</b>
                        </h2>
                    </div>
                    <div class="subheader mb-2">General</div>
                    <div class="list-group list-group-transparent">
                        <inertia-link
                            :href="route('settings.profile')"
                            class="list-group-item list-group-item-action d-flex align-items-center"
                            v-bind:class="{'active' : route().current('settings.profile')}"
                        >
                            Profile
                        </inertia-link>
                        <inertia-link
                            :href="route('settings.password')"
                            class="list-group-item list-group-item-action d-flex align-items-center"
                            v-bind:class="{'active' : route().current('settings.password')}"
                        >
                            Change password
                        </inertia-link>
                    </div>
                </div>
                <div class="col-md-9">
                    <slot></slot>
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
        metaInfo: {
            title: 'Settings'
        },
    };
</script>
