<template>
    <layout>
        <app-header />

        <main class="content">
            <div class="container-fluid">
                <slot />
            </div>
        </main>
    </layout>
</template>

<script>
import Layout from '@/layouts/default';
import AppHeader from '@/layouts/includes/app-header';

export default {
    components: {
        Layout,
        AppHeader
    }
};
</script>
