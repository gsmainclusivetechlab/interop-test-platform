<template>
    <div class="page">
        <app-header />

        <div class="content">
            <div class="container-fluid">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
import AppHeader from '@/layouts/includes/app-header';

export default {
    components: {
        AppHeader
    }
};
</script>
