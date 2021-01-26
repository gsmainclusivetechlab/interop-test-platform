<template>
    <layout>
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h3 class="card-title">Profile</h3>
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
                                :value="$page.props.auth.user.email"
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
                                    'is-invalid': $page.props.errors.first_name,
                                }"
                                placeholder="e.g., John"
                                name="first_name"
                            />
                            <span
                                v-if="$page.props.errors.first_name"
                                class="invalid-feedback"
                            >
                                {{ $page.props.errors.first_name }}
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
                                    'is-invalid': $page.props.errors.last_name,
                                }"
                                placeholder="e.g., Doe"
                                name="last_name"
                            />
                            <span
                                v-if="$page.props.errors.last_name"
                                class="invalid-feedback"
                            >
                                {{ $page.props.errors.last_name }}
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
                                :class="{
                                    'is-invalid': $page.props.errors.company,
                                }"
                                placeholder="e.g., GSMA"
                                name="company"
                            />
                            <span
                                v-if="$page.props.errors.company"
                                class="invalid-feedback"
                            >
                                {{ $page.props.errors.company }}
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
                first_name: this.$page.props.auth.user.first_name,
                last_name: this.$page.props.auth.user.last_name,
                company: this.$page.props.auth.user.company,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(route('settings.profile.update'), this.form, {
                onFinish: () => {
                    this.sending = false;
                },
            });
        },
    },
};
</script>
