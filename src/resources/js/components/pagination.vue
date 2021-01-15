<template>
    <div v-if="meta.total" class="d-flex align-items-center">
        <p class="m-0 text-muted">
            {{ $t('comment[0]') }}
            <span>
                {{ meta.from }}
            </span>
            {{ $t('comment[1]') }}
            <span>
                {{ meta.to }}
            </span>
            {{ $t('comment[2]') }}
            <span>
                {{ meta.total }}
            </span>
            {{ $tc('comment[3]', meta.total) }}
        </p>
        <ul v-if="meta.total > meta.per_page" class="pagination m-0 ml-auto">
            <li v-if="links.prev" class="page-item">
                <inertia-link class="page-link" :href="links.prev">
                    <icon name="arrow-left"></icon>
                    {{ $t('buttons.prev') }}
                </inertia-link>
            </li>
            <li v-else class="page-item disabled">
                <span class="page-link">
                    <icon name="arrow-left"></icon>
                    {{ $t('buttons.prev') }}
                </span>
            </li>
            <li
                v-for="(n, i) in meta.last_page"
                class="page-item"
                :class="{ active: meta.current_page === n }"
                :key="i"
            >
                <inertia-link
                    class="page-link"
                    :href="route().url()"
                    :data="{ page: n }"
                >
                    {{ n }}
                </inertia-link>
            </li>
            <li v-if="links.next" class="page-item">
                <inertia-link class="page-link" :href="links.next">
                    {{ $t('buttons.next') }}
                    <icon name="arrow-right"></icon>
                </inertia-link>
            </li>
            <li v-else class="page-item disabled">
                <span class="page-link">
                    {{ $t('buttons.next') }}
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
            required: true,
        },
        links: {
            type: Object,
            required: true,
        },
    },
};
</script>
<i18n src="@locales/components/pagination.json"></i18n>
