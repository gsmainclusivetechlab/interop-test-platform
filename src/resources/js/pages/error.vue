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
                        Oops...You just found an error page
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
    import Layout from '@/layouts/app';

    export default {
        metaInfo() {
            return {
                title: {
                    400: '400 Bad Request',
                    401: '401 Unauthorized',
                    403: '403 Forbidden',
                    404: '404 Page Not Found',
                    419: '419 Page Expired',
                    429: '429 Too Many Requests',
                    500: '500 Server Error',
                    503: '503 Service Unavailable',
                }[this.status],
            }
        },
        components: {
            Layout
        },
        props: {
            status: {
                type: Number,
                required: true
            },
        },
        computed: {
            message () {
                return {
                    400: 'We are sorry but your browser sent a request that this server could not understand.',
                    401: 'We are sorry but you are not authorized to access this page.',
                    403: 'We are sorry but you do not have permission to access this page',
                    404: 'We are sorry but the page you were looking for does not exist.',
                    419: 'We are sorry but your session has expired.',
                    429: 'We are sorry but too many requests.',
                    500: 'We are sorry but your request contains bad syntax and cannot be fulfilled.',
                    503: 'We are sorry but the service is temporarily unavailable.',
                }[this.status];
            }
        }
    };
</script>
