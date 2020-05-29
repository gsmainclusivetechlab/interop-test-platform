<template>
    <layout>
        <form class="card card-md" @submit.prevent="submit">
            <div class="card-body">
                <h2 class="mb-5 text-center">Confirm password</h2>
                <p class="text-muted">
                    Please confirm your password before continuing.
                </p>
                <div class="mb-3">
                    <label class="form-label">
                        Password
                        <inertia-link
                            :href="route('password.request')"
                            class="float-right small"
                        >
                            I forgot password
                        </inertia-link>
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
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block">
                        <span
                            v-if="sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        Confirm Password
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
        title: 'Confirm password',
    },
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            form: {
                password: null,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('password.confirm'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>
