<template>
    <layout>
        <form class="card card-md" @submit.prevent="submit">
            <div class="card-body">
                <h2 class="mb-5 text-center">Forgot password</h2>
                <p class="text-muted">
                    Enter your email address and your password will be reset and
                    emailed to you.
                </p>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input
                        v-model="form.email"
                        :class="{ 'is-invalid': $page.props.errors.email }"
                        type="email"
                        class="form-control"
                        placeholder="e.g., john.doe@email.com"
                        name="email"
                    />
                    <span
                        v-if="$page.props.errors.email"
                        class="invalid-feedback"
                    >
                        {{ $page.props.errors.email }}
                    </span>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block">
                        <span
                            v-if="sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        Send me password reset link
                    </button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted" v-if="$page.props.auth.guest">
            Forget it,
            <inertia-link :href="route('login')">send me back</inertia-link>
            to the sign in screen.
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/auth';

export default {
    metaInfo: {
        title: 'Forgot password',
    },
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            form: {
                email: null,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(route('password.email'), this.form, {
                onFinish: () => {
                    this.sending = false;
                },
            });
        },
    },
};
</script>
