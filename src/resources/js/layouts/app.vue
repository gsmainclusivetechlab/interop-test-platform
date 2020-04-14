<template>
    <div class="page">
        <inertia-link :href="route('logout')" method="post">Logout</inertia-link>

        <slot></slot>
    </div>
</template>

<script>
    export default {

    }
</script>
