<template>
    <div v-if="meta.total"  class="d-flex align-items-center">
        <p class="m-0 text-muted">
            Showing
            <span>
                {{ meta.from }}
            </span>
            to
            <span>
                {{ meta.to }}
            </span>
            of
            <span>
                {{ meta.total }}
            </span>
            entries
        </p>
        <ul v-if="meta.total > meta.per_page" class="pagination m-0 ml-auto">
            <li v-if="links.prev" class="page-item">
                <inertia-link class="page-link" :href="links.prev">
                    <icon name="arrow-left"></icon>
                    Prev
                </inertia-link>
            </li>
            <li v-else class="page-item disabled">
                <span class="page-link">
                    <icon name="arrow-left"></icon>
                    Prev
                </span>
            </li>
            <li v-for="n in meta.last_page" class="page-item" v-bind:class="{'active': meta.current_page === n}">
                <inertia-link class="page-link" :href="route().url()" :data="{page: n}">
                    {{ n }}
                </inertia-link>
            </li>
            <li v-if="links.next" class="page-item">
                <inertia-link class="page-link" :href="links.next">
                    Next
                    <icon name="arrow-right"></icon>
                </inertia-link>
            </li>
            <li v-else class="page-item disabled">
                <span class="page-link">
                    Next
                    <icon name="arrow-right"></icon>
                </span>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        meta: {
            type: Object,
            required: true
        },
        links: {
            type: Object,
            required: true
        },
    }
};
</script>
