<template>
    <layout>
        <div class="flex-fill d-flex align-items-center justify-content-center">
            <div class="container-tight py-6">
                <div class="empty">
                    <div class="empty-icon">
                        <div class="display-4">
                            {{ status }}
                        </div>
                    </div>
                    <p class="empty-title h3">
                        {{ $t('error.page.title') }}
                    </p>
                    <p class="empty-subtitle text-muted">
                        {{ message }}
                    </p>
                    <div class="empty-action">
                        <inertia-link
                            :href="route('home')"
                            class="btn btn-primary"
                        >
                            <icon name="arrow-left" />
                            {{ $t('error.buttons.home') }}
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/app';

export default {
    metaInfo() {
        return {
            title: {
                400: this.$t('error.errors.e-400.title'),
                401: this.$t('error.errors.e-401.title'),
                403: this.$t('error.errors.e-403.title'),
                404: this.$t('error.errors.e-404.title'),
                405: this.$t('error.errors.e-405.title'),
                419: this.$t('error.errors.e-419.title'),
                429: this.$t('error.errors.e-429.title'),
                500: this.$t('error.errors.e-500.title'),
                503: this.$t('error.errors.e-503.title'),
            }[this.status],
        };
    },
    components: {
        Layout,
    },
    props: {
        status: {
            type: Number,
            required: true,
        },
    },
    computed: {
        message() {
            return {
                400: this.$t('error.errors.e-400.comment'),
                401: this.$t('error.errors.e-401.comment'),
                403: this.$t('error.errors.e-403.comment'),
                404: this.$t('error.errors.e-404.comment'),
                405: this.$t('error.errors.e-405.comment'),
                419: this.$t('error.errors.e-419.comment'),
                429: this.$t('error.errors.e-429.comment'),
                500: this.$t('error.errors.e-500.comment'),
                503: this.$t('error.errors.e-503.comment'),
            }[this.status];
        },
    },
};
</script>
