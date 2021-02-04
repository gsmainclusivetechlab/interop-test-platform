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
                        meta.last_page <= navigation.limit ||
                        sideLimitPages.some((val) => val === n)
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
                            meta.last_page - (navigation.sideLimit + 2),
                            meta.last_page - navigation.sideLimit,
                        ].some((val) => val === n)
                    "
                    class="page-item"
                    :key="i"
                >
                    <span class="page-link">...</span>
                </li>
                <li
                    v-else-if="
                        meta.last_page - (navigation.sideLimit + 1) === n
                    "
                    class="page-item active pagination__navigation"
                    :key="i"
                >
                    <form
                        class="input-group"
                        @submit.prevent="
                            $inertia.visit(route().url(), {
                                method: 'get',
                                data: {
                                    page: navigation.page.current,
                                },
                            })
                        "
                    >
                        <input
                            v-model="navigation.page.current"
                            type="number"
                            class="form-control"
                        />
                        <button type="submit" class="btn btn-outline-primary">
                            Go
                        </button>
                    </form>
                    <span
                        v-if="
                            sideLimitPages.every(
                                (val) => val !== meta.current_page
                            )
                        "
                        class="badge badge-primary"
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
    },
    data() {
        return {
            navigation: {
                page: {
                    current: this.meta.current_page,
                },
                limit: 10,
                sideLimit: 2,
            },
        };
    },
    computed: {
        sideLimitPages() {
            const arr = [...Array(this.navigation.sideLimit).keys()];
            const arrStart = arr.map((val) => val + 1);
            const arrFinish = arr
                .reverse()
                .map((val) => this.meta.last_page - val);

            return [...arrStart, ...arrFinish];
        },
    },
};
</script>
<i18n src="@locales/components/pagination.json"></i18n>
