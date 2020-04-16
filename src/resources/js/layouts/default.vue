<template>
    <div class="page">
        <slot />

        <notifications />
    </div>
</template>

<script>
import Notifications from '@/components/notifications';

export default {
    components: {
        Notifications
    }
};
</script>
