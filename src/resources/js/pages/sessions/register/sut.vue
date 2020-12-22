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
                            :class="{
                                'is-invalid': $page.errors.components,
                            }"
                            :options="suts.data"
                            keyBy="id"
                            :keys="['name']"
                            label="name"
                            :createItem="false"
                            :multiple="true"
                            class="form-select"
                            placeholder="Select SUT..."
                            :disabled="selectedSut"
                        />
                        <span
                            v-if="$page.errors.components"
                            class="invalid-feedback"
                        >
                            {{ $page.errors.components }}
                        </span>
                    </div>
                    <template v-for="(sut, i) in suts.data" v-if="form.components[sut.id]">
                        <div class="mb-3">
                            <label class="form-label">{{ sut.name }} URL</label>
                            <input
                                v-model="componentsData.base_url[sut.id]"
                                :class="{
                                    'is-invalid':
                                        $page.errors[`components.${sut.id}.base_url`],
                                }"
                                class="form-control"
                            />
                            <span
                                v-if="$page.errors[`components.${sut.id}.base_url`]"
                                class="invalid-feedback"
                            >
                                {{ $page.errors[`components.${sut.id}.base_url`] }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    v-model="componentsData.use_encryption[sut.id]"
                                />
                                <span class="form-check-label">Use Encryption</span>
                            </label>
                            <span
                                v-if="$page.errors[`components.${sut.id}.use_encryption`]"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.errors[`components.${sut.id}.use_encryption`] }}
                                </strong>
                            </span>
                        </div>
                        <div class="mb-3" v-if="componentsData.use_encryption[sut.id]">
                            <label class="form-label">Certificate</label>
                            <selectize
                                v-model="componentsData.certificate_id[sut.id]"
                                class="form-select mb-3"
                                placeholder="Select group certificate..."
                                label="name"
                                :keys="['name']"
                                :options="groupCertificatesList"
                                :createItem="false"
                                :searchFn="searchGroupCertificates"
                                :class="{
                                    'is-invalid': $page.errors[`components.${sut.id}.certificate_id`],
                                }"
                                v-if="hasGroupCertificates && !componentsData.certificate[sut.id]"
                            />
                            <div class="hr-text" v-if="hasGroupCertificates && !componentsData.certificate[sut.id] && !componentsData.certificate_id[sut.id]">or</div>
                            <b-form-file
                                v-model="componentsData.certificate[sut.id]"
                                v-if="!componentsData.certificate_id[sut.id]"
                                placeholder="Choose file..."
                                :class="{
                                    'is-invalid': $page.errors[`components.${sut.id}.certificate`],
                                }"
                            />
                            <span
                                v-if="$page.errors[`components.${sut.id}.certificate`] || $page.errors[`components.${sut.id}.certificate_id`]"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.errors[`components.${sut.id}.certificate`]
                                        ? $page.errors[`components.${sut.id}.certificate`]
                                        : $page.errors[`components.${sut.id}.certificate_id`] }}
                                </strong>
                            </span>
                        </div>
                    </template>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    v-if="
                        $page.app.available_session_modes_count > 1 ||
                        isCompliance ||
                        session.withQuestions
                    "
                    :href="
                        route(
                            isCompliance || session.withQuestions
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
        hasGroupCertificates: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        const isCompliance = this.session.type === 'compliance';
        const selectedSut = isCompliance && this.suts.data.length === 1;
        const sessionData = this.session && this.session.sut ? this.session.sut : [];

        return {
            sending: false,
            isCompliance,
            selectedSut,
            groupCertificatesList: [],
            componentsData: {
                base_url: collect(sessionData).mapWithKeys((s) => [s.id, s.base_url]).all(),
                use_encryption: collect(sessionData).mapWithKeys((s) => [s.id, s.use_encryption]).all(),
                certificate_id: collect(sessionData).mapWithKeys((s) => [s.id, s.certificate_id]).all(),
                certificate: [],
            },
            selectedComponents:
                this.session && this.session.sut
                    ? collect(this.suts.data)
                          .whereIn('id', Object.keys(this.session.sut).map((key) => Number(key)))
                          .all()
                    : selectedSut
                    ? [collect(this.suts.data).first()]
                    : [],
            form: {
                components: this.session &&
                    this.session.sut
                        ? this.session.sut.components
                        : [],
            },
        };
    },
    mounted() {
        this.loadGroupCertificateList();
    },
    watch: {
        selectedComponents: {
            immediate: true,
            handler: function (values) {
                const c = this.componentsData;
                this.form.components = [];

                Object.values(values ? values : []).forEach((value) => {
                    const id = value.id;

                    this.form.components[id] = {
                        id,
                        base_url: c.base_url[c.id],
                        use_encryption: c.use_encryption[c.id],
                        certificate_id: c.certificate_id[c.id] ? c.certificate_id[c.id].id : null,
                        certificate: c.certificate[c.id]
                    };
                });
            },
        },
        componentsData: {
            deep: true,
            handler: function (values) {
                this.form.components.forEach((c) => {
                    c.base_url = values.base_url[c.id]
                    c.use_encryption = values.use_encryption[c.id]
                    c.certificate_id = values.certificate_id[c.id] ? values.certificate_id[c.id].id : null
                    c.certificate = values.certificate[c.id]
                });
            }
        }
    },
    methods: {
        submit() {
            this.sending = true;

            this.$inertia
                .post(route('sessions.register.sut.store'), this.jsonToFormData(this.form))
                .then(() => (this.sending = false));
        },
        loadGroupCertificateList(query = '') {
            axios
                .get(route('sessions.register.group-certificate-candidates'), {
                    params: { q: query },
                })
                .then((result) => {
                    this.groupCertificatesList = result.data.data;

                    const collection = collect(result.data.data);
                    const ids = this.componentsData.certificate_id;

                    Object.keys(ids).forEach((key) => {
                        this.componentsData.certificate_id[key] = collection
                            .where('id', ids[key])
                            .first()
                    })
                });
        },
        searchGroupCertificates(query, callback) {
            this.loadGroupCertificateList(query);
            callback();
        },
        buildFormData(formData, data, parentKey) {
            if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
                Object.keys(data).forEach(key => {
                    this.buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
                });
            } else {
                let value = data == null ? '' : data;

                if ('boolean' === typeof value) {
                    value = Number(value)
                }

                formData.append(parentKey, value);
            }
        },
        jsonToFormData(data) {
            const formData = new FormData();

            this.buildFormData(formData, data);

            return formData;
        }
    },
};
</script>
