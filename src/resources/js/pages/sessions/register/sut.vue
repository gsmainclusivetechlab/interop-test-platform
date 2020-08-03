<template>
    <layout :components="components">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">SUT selection</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">SUT</label>
                        <selectize
                            v-model="component"
                            :class="{ 'is-invalid': $page.errors.component_id }"
                            :options="suts.data"
                            keyBy="id"
                            :keys="['name']"
                            label="name"
                            :createItem="false"
                            class="form-select"
                            placeholder="Select SUT..."
                        />
                        <span
                            v-if="$page.errors.component_id"
                            class="invalid-feedback"
                        >
                            {{ $page.errors.component_id }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input
                            v-model="form.base_url"
                            :class="{ 'is-invalid': $page.errors.base_url }"
                            class="form-control"
                            name="base_url"
                        />
                        <span
                            v-if="$page.errors.base_url"
                            class="invalid-feedback"
                        >
                            {{ $page.errors.base_url }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary ml-auto">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Next
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/register';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: false,
        },
        suts: {
            type: Object,
            required: true,
        },
        components: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            component: this.session && this.session.sut
                ? collect(this.suts.data).where('id', this.session.sut.component_id).first()
                : collect(this.suts.data).first(),
            form: {
                base_url:
                    this.session && this.session.sut
                        ? this.session.sut.base_url
                        : null,
                component_id: null,
            },
        };
    },
    watch: {
        component: {
            immediate: true,
            handler: function (value) {
                this.form.component_id = value ? value.id : null;
            },
        },
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('sessions.register.sut.store'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>
