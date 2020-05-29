<template>
    <layout>
        <form class="card card-md" @submit.prevent="submit">
            <div class="card-body">
                <h2 class="mb-5 text-center">Reset password</h2>
                <p class="text-muted">
                    You can set a new password below. Please take the time to
                    choose a secure and difficult to guess password.
                </p>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input
                        v-model="form.email"
                        :class="{ 'is-invalid': $page.errors.email }"
                        type="email"
                        readonly="readonly"
                        class="form-control"
                        placeholder="e.g., john.doe@email.com"
                    />
                    <span v-if="$page.errors.email" class="invalid-feedback">
                        {{ $page.errors.email }}
                    </span>
                </div>
                <div class="mb-3">
                    <label class="form-label">New password</label>
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
                    <label class="form-label">Confirm password</label>
                    <input
                        v-model="form.password_confirmation"
                        :class="{
                            'is-invalid': $page.errors.password_confirmation,
                        }"
                        type="password"
                        class="form-control"
                        placeholder="e.g., **********"
                    />
                    <span
                        v-if="$page.errors.password_confirmation"
                        class="invalid-feedback"
                    >
                        {{ $page.errors.password_confirmation }}
                    </span>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block">
                        <span
                            v-if="sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        Reset password
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
        title: 'Reset password',
    },
    components: {
        Layout,
    },
    props: {
        token: {
            type: String,
            required: true,
        },
        email: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            form: {
                token: this.token,
                email: this.email,
                password: null,
                password_confirmation: null,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('password.update'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>
