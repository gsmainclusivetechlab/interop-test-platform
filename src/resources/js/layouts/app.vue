<template>
    <div class="page">
<!--        HEADER-->
<!--        NAVBAR-->

        <inertia-link :href="route('logout')" method="post">
            Logout
        </inertia-link>

        <div class="content">
            <div class="container-fluid">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {};
</script>
