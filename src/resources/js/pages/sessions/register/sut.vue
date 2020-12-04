<template>
    <layout :components="components" :session="session">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">SUT selection</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">SUTs</label>
                        <selectize
                            v-model="selectedComponents"
                            :class="{ 'is-invalid': $page.errors.component_ids }"
                            :options="suts.data"
                            keyBy="id"
                            :keys="['name']"
                            label="name"
                            :createItem="false"
                            :multiple="true"
                            class="form-select"
                            placeholder="Select SUT..."
                        />
                        <span
                            v-if="$page.errors.component_ids"
                            class="invalid-feedback"
                        >
                            {{ $page.errors.component_ids }}
                        </span>
                    </div>
                    <div class="mb-3" v-for="sut in suts.data" v-if="form.component_ids.includes(sut.id)">
                        <label class="form-label">{{ sut.name }} URL</label>
                        <input
                            v-model="form.base_urls[sut.id]"
                            :class="{ 'is-invalid': $page.errors[`base_urls.${sut.id}`] }"
                            class="form-control"
                        />
                        <span
                            v-if="$page.errors[`base_urls.${sut.id}`]"
                            class="invalid-feedback"
                        >
                            {{ $page.errors[`base_urls.${sut.id}`] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="
                        route(
                            isCompliance
                                ? 'sessions.register.questionnaire.summary'
                                : 'sessions.register.type'
                        )
                    "
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
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
            isCompliance: this.session.type === 'compliance',
            selectedComponents:
                this.session && this.session.sut && this.session.sut.component_ids
                    ? collect(this.suts.data)
                          .whereIn('id', this.session.sut.component_ids)
                          .all()
                    : [],
            form: {
                base_urls:
                    this.session && this.session.sut && this.session.sut.base_urls
                        ? this.session.sut.base_urls
                        : [],
                component_ids: [],
            },
        };
    },
    watch: {
        selectedComponents: {
            immediate: true,
            handler: function (values) {
                this.form.component_ids = [];

                Object.values(values ? values : []).forEach(
                    (value) => {
                        this.form.component_ids.push(value.id);
                    }
                );
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
