<template>
    <layout>
        <app-header />

        <main class="content">
            <div class="container-fluid">
                <slot />
            </div>
        </main>

        <app-footer />
    </layout>
</template>

<script>
import Layout from '@/layouts/default';
import AppHeader from '@/layouts/includes/app-header';
import AppFooter from '@/layouts/includes/app-footer';

export default {
    components: {
        Layout,
        AppHeader,
        AppFooter
    }
};
</script>
