<template>
    <div class="flex-fill d-flex align-items-center justify-content-center">
        <div class="container-tight py-6">
            <div class="empty">
                <div class="empty-icon">
                    <div class="display-4">
                        {{ code }}
                    </div>
                </div>
                <p class="empty-title h3">Oops… You just found an error page</p>
                <p class="empty-subtitle text-muted">
                    {{ message }}
                </p>
                <div class="empty-action">
                    <inertia-link :href="route('home')" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"></path>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <line x1="5" y1="12" x2="11" y2="18"></line>
                            <line x1="5" y1="12" x2="11" y2="6"></line>
                        </svg>
                        Take me home
                    </inertia-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            code: Number,
            message: String,
        },
    }
</script>
