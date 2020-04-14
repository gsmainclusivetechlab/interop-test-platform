<template>
    <layout>
        <form class="card card-md" @submit.prevent="submit">
            <div class="card-body">
                <h2 class="mb-5 text-center">Create new account</h2>
                <div class="mb-3">
                    <label class="form-label">First name</label>
                    <input
                        v-model="form.first_name"
                        :class="{ 'is-invalid': $page.errors.first_name }"
                        type="text"
                        class="form-control"
                        placeholder="e.g., John"
                    />
                    <span
                        v-if="$page.errors.first_name"
                        class="invalid-feedback"
                    >
                        {{ $page.errors.first_name }}
                    </span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Last name</label>
                    <input
                        v-model="form.last_name"
                        :class="{ 'is-invalid': $page.errors.last_name }"
                        type="text"
                        class="form-control"
                        placeholder="e.g., Doe"
                    />
                    <span
                        v-if="$page.errors.last_name"
                        class="invalid-feedback"
                    >
                        {{ $page.errors.last_name }}
                    </span>
                </div>
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
                    <label class="form-label">Company</label>
                    <input
                        v-model="form.company"
                        :class="{ 'is-invalid': $page.errors.company }"
                        type="text"
                        class="form-control"
                        placeholder="e.g., GSMA"
                    />
                    <span v-if="$page.errors.company" class="invalid-feedback">
                        {{ $page.errors.company }}
                    </span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
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
                            'is-invalid': $page.errors.password_confirmation
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
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input
                        v-model="form.code"
                        :class="{ 'is-invalid': $page.errors.code }"
                        type="text"
                        class="form-control"
                        placeholder="e.g., CODE"
                    />
                    <span v-if="$page.errors.code" class="invalid-feedback">
                        {{ $page.errors.code }}
                    </span>
                </div>
                <div class="mb-3">
                    <label
                        :class="{ 'is-invalid': $page.errors.terms }"
                        class="form-check"
                    >
                        <input
                            type="checkbox"
                            v-model="form.terms"
                            class="form-check-input"
                        />
                        <span class="form-check-label">
                            Agree the
                            <a
                                href="https://www.gsma.com/aboutus/legal"
                                target="_blank"
                                >terms and policy</a
                            >.
                        </span>
                    </label>
                    <span v-if="$page.errors.terms" class="invalid-feedback">
                        {{ $page.errors.terms }}
                    </span>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block">
                        Register
                    </button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted">
            Already have account?
            <inertia-link :href="route('login')">Login</inertia-link>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/auth.vue';

export default {
    metaInfo: {
        title: 'Create new account'
    },
    components: {
        Layout
    },
    data() {
        return {
            sending: false,
            form: {
                first_name: null,
                last_name: null,
                email: null,
                company: null,
                password: null,
                password_confirmation: null,
                code: null,
                terms: null
            }
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('register'), this.form)
                .then(() => (this.sending = false));
        }
    }
};
</script>
