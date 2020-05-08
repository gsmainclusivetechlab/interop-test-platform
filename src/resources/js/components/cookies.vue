<template>
    <div v-if="!accepted" class="col-sm-6 col-md-5 col-lg-4 col-xl-3 mb-2 mr-2 ml-2 ml-sm-auto fixed-bottom">
        <div class="card m-0">
            <div class="card-body">
                <p>
                    We use cookies to analyse our traffic and enhance the user
                    experience for the platform. By continuing to use our site,
                    you accept our use of cookies.
                    <a
                        href="https://www.gsma.com/aboutus/legal/cookie-policy"
                        class="test-decoration-underline text-nowrap"
                        target="_blank"
                    >
                        Learn more
                    </a>
                </p>
                <button @click.prevent="accept" class="btn btn-primary w-100">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Got It!
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                sending: false,
                accepted: this.$page.app.cookies_accepted,
            };
        },
        methods: {
            accept() {
                this.sending = true;
                axios
                    .post(route('legal.cookies.accept'))
                    .then(() => this.accepted = true);
            },
        },
    };
</script>
