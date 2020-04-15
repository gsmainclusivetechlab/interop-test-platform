<template>
    <layout>
        <form class="card card-md" @submit.prevent="submit">
            <div class="card-body">
                <h2 class="mb-5 text-center">Login to your account</h2>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input
                        v-model="form.email"
                        :class="{ 'is-invalid': $page.errors.email }"
                        type="email"
                        class="form-control"
                        placeholder="e.g., john.doe@email.com"
                    />
                    <span v-if="$page.errors.email" class="invalid-feedback">
                        {{ $page.errors.email }}
                    </span>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        Password
                        <span class="form-label-description">
                            <inertia-link :href="route('password.request')"
                                >Forgot password?</inertia-link
                            >
                        </span>
                    </label>
                    <input
                        v-model="form.password"
                        :class="{ 'is-invalid': $page.errors.password }"
                        type="password"
                        class="form-control"
                        placeholder="e.g., **********"
                    />
                    <span v-if="$page.errors.password" class="invalid-feedback">
                        {{ $page.errors.password }}
                    </span>
                </div>
                <div class="mb-3">
                    <label class="form-check">
                        <input
                            type="checkbox"
                            v-model="form.remember"
                            class="form-check-input"
                        />
                        <span class="form-check-label">Remember me</span>
                    </label>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block">
                        <span v-if="sending" class="spinner-border spinner-border-sm mr-2"></span>
                        Login
                    </button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted">
            Don't have account yet?
            <inertia-link :href="route('register')">Register</inertia-link>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/auth.vue';

export default {
    metaInfo: {
        title: 'Login to your account'
    },
    components: {
        Layout
    },
    data() {
        return {
            sending: false,
            form: {
                email: null,
                password: null,
                remember: null
            }
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('login'), this.form)
                .then(() => (this.sending = false));
        }
    }
};
</script>
