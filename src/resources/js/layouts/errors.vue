<template>
    <layout>
        <div class="flex-fill d-flex align-items-center justify-content-center">
            <div class="container-tight py-6">
                <div class="empty">
                    <div class="empty-icon">
                        <div class="display-4">
                            {{ code }}
                        </div>
                    </div>
                    <p class="empty-title h3">
                        Oops… You just found an error page
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
                            Take me home
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/default';

export default {
    components: {
        Layout
    },
    props: {
        code: {
            type: Number,
            required: true
        },
        message: {
            type: String,
            required: true
        }
    }
};
</script>
