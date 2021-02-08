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
            <li v-if="links.prev !== null" class="page-item">
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
            <template v-for="(n, i) in meta.last_page">
                <li
                    v-if="
                        meta.last_page <= navigationLimit.pages ||
                        listPageBySides.some((val) => val === n)
                    "
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
                <li
                    v-else-if="
                        [
                            meta.last_page - (navigationLimit.pageBySides + 2),
                            meta.last_page - navigationLimit.pageBySides,
                        ].some((val) => val === n)
                    "
                    class="page-item"
                    :key="i"
                >
                    <span class="page-link">...</span>
                </li>
                <li
                    v-else-if="
                        meta.last_page - (navigationLimit.pageBySides + 1) === n
                    "
                    class="pagination__navigation"
                    :key="i"
                >
                    <form class="input-group" @submit.prevent="navigateToPage">
                        <input
                            v-model="navigation.pageTo"
                            type="number"
                            class="form-control"
                            :class="{
                                'is-invalid': !checkPageTo,
                            }"
                        />
                        <button type="submit" class="btn btn-outline-primary">
                            Go
                        </button>
                    </form>
                    <span
                        v-if="
                            listPageBySides.every(
                                (val) => val !== meta.current_page
                            )
                        "
                        class="badge"
                        v-b-tooltip.hover.top="'Current page'"
                        >{{ meta.current_page }}</span
                    >
                </li>
            </template>
            <li v-if="links.next !== null" class="page-item">
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
        navigationLimit: {
            type: Object,
            required: false,
            default() {
                return {
                    pages: 10,
                    pageBySides: 2,
                };
            },
        },
    },
    data() {
        return {
            navigation: {
                pageTo: this.meta.current_page,
            },
        };
    },
    methods: {
        navigateToPage() {
            if (!this.checkPageTo) return;

            this.$inertia.visit(route().url(), {
                method: 'get',
                data: {
                    page: this.navigation.pageTo,
                },
            });
        },
    },
    computed: {
        listPageBySides() {
            const arr = [...Array(this.navigationLimit.pageBySides).keys()];
            const arrBefore = arr.map((val) => val + 1);
            const arrAfter = arr
                .reverse()
                .map((val) => this.meta.last_page - val);

            return [...arrBefore, ...arrAfter];
        },
        checkPageTo() {
            return (
                this.navigation.pageTo > 0 &&
                this.navigation.pageTo <= this.meta.last_page
            );
        },
    },
};
</script>
<i18n src="@locales/components/pagination.json"></i18n>
