<template>
    <div class="page">
        <slot />
    </div>
</template>

<script>
    import Gtag from '@/components/gtag';
    import Notifications from '@/components/notifications';

    export default {
        mixins: [
            Gtag,
            Notifications,
        ],
    };
</script>
