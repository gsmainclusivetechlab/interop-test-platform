<template>
    <ol class="breadcrumb" v-if="items.length">
        <li
            v-for="item in items"
            class="breadcrumb-item"
            v-bind:class="{ active: item.active }"
        >
            <inertia-link v-if="item.url" :href="item.url">
                {{ item.name }}
            </inertia-link>
            <template v-else>
                {{ item.name }}
            </template>
        </li>
    </ol>
</template>

<script>
export default {
    props: {
        items: {
            type: Array,
            required: true,
        },
    },
};
</script>
