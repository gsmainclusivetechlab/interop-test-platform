<template>
    <layout>
        <app-header />
        <main class="content">
            <div class="container-fluid">
                <slot />
            </div>
            <app-footer />
        </main>
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
        AppFooter,
    }
};
</script>
