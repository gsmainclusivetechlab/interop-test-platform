<template>
    <layout>
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h3 class="card-title">
                    Change password
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>Current password</b>
                        </label>
                        <div class="col-sm-9">
                            <input
                                type="password"
                                v-model="form.current_password"
                                class="form-control"
                                :class="{
                                    'is-invalid': $page.errors.current_password,
                                }"
                                placeholder="e.g., **********"
                                name="current_password"
                            />
                            <span
                                v-if="$page.errors.current_password"
                                class="invalid-feedback"
                            >
                                {{ $page.errors.current_password }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>New password</b>
                        </label>
                        <div class="col-sm-9">
                            <input
                                type="password"
                                v-model="form.password"
                                class="form-control"
                                :class="{ 'is-invalid': $page.errors.password }"
                                placeholder="e.g., **********"
                                name="password"
                            />
                            <span
                                v-if="$page.errors.password"
                                class="invalid-feedback"
                            >
                                {{ $page.errors.password }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>Confirm new password</b>
                        </label>
                        <div class="col-sm-9">
                            <input
                                type="password"
                                v-model="form.password_confirmation"
                                class="form-control"
                                :class="{
                                    'is-invalid':
                                        $page.errors.password_confirmation,
                                }"
                                placeholder="e.g., **********"
                                name="password_confirmation"
                            />
                            <span
                                v-if="$page.errors.password_confirmation"
                                class="invalid-feedback"
                            >
                                {{ $page.errors.password_confirmation }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <inertia-link
                    :href="route('password.request')"
                    class="btn btn-link btn-space"
                >
                    I forgot my password
                </inertia-link>
                <button type="submit" class="btn btn-primary btn-space">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Update password
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/settings';

export default {
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            form: {
                current_password: null,
                password: null,
                password_confirmation: null,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('settings.password.update'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>
