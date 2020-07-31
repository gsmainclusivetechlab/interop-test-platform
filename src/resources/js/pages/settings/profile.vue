<template>
    <layout>
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h3 class="card-title">
                    Profile
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>Email</b>
                        </label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                readonly
                                class="form-control"
                                :value="$page.auth.user.email"
                            />
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>First name</b>
                        </label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                v-model="form.first_name"
                                class="form-control"
                                :class="{
                                    'is-invalid': $page.errors.first_name,
                                }"
                                placeholder="e.g., John"
                                name="first_name"
                            />
                            <span
                                v-if="$page.errors.first_name"
                                class="invalid-feedback"
                            >
                                {{ $page.errors.first_name }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>Last name</b>
                        </label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                v-model="form.last_name"
                                class="form-control"
                                :class="{
                                    'is-invalid': $page.errors.last_name,
                                }"
                                placeholder="e.g., Doe"
                                name="last_name"
                            />
                            <span
                                v-if="$page.errors.last_name"
                                class="invalid-feedback"
                            >
                                {{ $page.errors.last_name }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row align-items-center">
                        <label class="col-sm-3">
                            <b>Company</b>
                        </label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                v-model="form.company"
                                class="form-control"
                                :class="{ 'is-invalid': $page.errors.company }"
                                placeholder="e.g., GSMA"
                                name="company"
                            />
                            <span
                                v-if="$page.errors.company"
                                class="invalid-feedback"
                            >
                                {{ $page.errors.company }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary btn-space">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Update profile
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
                first_name: this.$page.auth.user.first_name,
                last_name: this.$page.auth.user.last_name,
                company: this.$page.auth.user.company,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('settings.profile.update'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>
