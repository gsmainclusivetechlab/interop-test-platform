<template>
    <layout>
        <form class="card card-md" @submit.prevent="submit">
            <div class="card-body">
                <div class="card-title">Verify Your Email Address</div>
                <p class="text-muted">
                    Before proceeding, please check your email for a
                    verification link.
                </p>
                <p class="text-muted">
                    If have problems to receive the verification link, please
                    reach out our support:
                    <a href="mailto:inclusivetechlab@gsma.com"
                        >inclusivetechlab@gsma.com</a
                    >
                </p>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block">
                        <span
                            v-if="sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        Click here to request another
                    </button>
                </div>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/auth';

export default {
    metaInfo: {
        title: 'Verify Your Email Address',
    },
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('verification.resend'),
                {},
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
    },
};
</script>
