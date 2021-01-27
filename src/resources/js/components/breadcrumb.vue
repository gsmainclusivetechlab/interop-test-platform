<template>
    <ol class="breadcrumb" v-if="items.length">
        <li
            v-for="(item, i) in items"
            class="breadcrumb-item"
            :class="{ active: item.active }"
            :key="i"
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
