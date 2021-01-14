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
                        <v-select
                            v-model="currentSuts.selected"
                            :options="currentSuts.list"
                            label="name"
                            multiple
                            placeholder="Select SUT..."
                            :selectable="
                                (option) =>
                                    isSelectable(option, currentSuts.selected)
                            "
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.components,
                            }"
                            :disabled="selectedSut"
                            @input="setComponents"
                        />
                        <span
                            v-if="$page.props.errors.components"
                            class="invalid-feedback"
                        >
                            {{ $page.props.errors.components }}
                        </span>
                    </div>
                    <template
                        v-for="(sut, i) in suts.data"
                        v-if="form.components[sut.id]"
                    >
                        <div class="mb-3">
                            <label class="form-label">{{ sut.name }} URL</label>
                            <input
                                v-model="componentsData.base_url[sut.id]"
                                :class="{
                                    'is-invalid':
                                        $page.props.errors[
                                            `components.${sut.id}.base_url`
                                        ],
                                }"
                                class="form-control"
                            />
                            <span
                                v-if="
                                    $page.props.errors[
                                        `components.${sut.id}.base_url`
                                    ]
                                "
                                class="invalid-feedback"
                            >
                                {{
                                    $page.props.errors[
                                        `components.${sut.id}.base_url`
                                    ]
                                }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    v-model="
                                        componentsData.use_encryption[sut.id]
                                    "
                                />
                                <span class="form-check-label"
                                >Use Encryption</span
                                >
                            </label>
                            <span
                                v-if="
                                    $page.props.errors[
                                        `components.${sut.id}.use_encryption`
                                    ]
                                "
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{
                                        $page.props.errors[
                                            `components.${sut.id}.use_encryption`
                                        ]
                                    }}
                                </strong>
                            </span>
                        </div>
                        <div v-if="componentsData.use_encryption[sut.id]">
                            <div v-if="showGroupCertificates(sut)">
                                <label class="form-label">Certificate</label>
                                <v-select
                                    v-model="componentsData.certificate_id[sut.id]"
                                    :options="groupCertificatesList"
                                    label="name"
                                    placeholder="Select group certificate..."
                                    :selectable="
                                        (option) =>
                                            isSelectable(option, currentSuts.selected)
                                    "
                                    class="form-control d-flex p-0"
                                    :class="{
                                        'is-invalid': $page.props.errors.components,
                                    }"
                                />
                                <span
                                    v-if="
                                        $page.props.errors[
                                            `components.${sut.id}.certificate_id`
                                        ]
                                    "
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{
                                            $page.props.errors[
                                                `components.${sut.id}.certificate_id`
                                            ]
                                        }}
                                    </strong>
                                </span>
                            </div>
                            <div
                                class="hr-text"
                                v-if="
                                    showGroupCertificates(sut) &&
                                    !componentsData.certificate_id[sut.id]
                                "
                            >
                                or
                            </div>
                            <template
                                v-if="!componentsData.certificate_id[sut.id]"
                            >
                                <div class="mb-3">
                                    <label class="form-label"
                                    >CA certificate</label
                                    >
                                    <b-form-file
                                        v-model="componentsData.ca_crt[sut.id]"
                                        placeholder="Choose file..."
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${sut.id}.ca_crt`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `components.${sut.id}.ca_crt`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `components.${sut.id}.ca_crt`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"
                                    >Client certificate</label
                                    >
                                    <b-form-file
                                        v-model="
                                            componentsData.client_crt[sut.id]
                                        "
                                        placeholder="Choose file..."
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${sut.id}.client_crt`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `components.${sut.id}.client_crt`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `components.${sut.id}.client_crt`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Client key</label>
                                    <b-form-file
                                        v-model="
                                            componentsData.client_key[sut.id]
                                        "
                                        placeholder="Choose file..."
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${sut.id}.client_key`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `components.${sut.id}.client_key`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `components.${sut.id}.client_key`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"
                                    >Pass phrase</label
                                    >
                                    <input
                                        v-model="
                                            componentsData.passphrase[sut.id]
                                        "
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${sut.id}.passphrase`
                                                ],
                                        }"
                                        class="form-control"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `components.${sut.id}.passphrase`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `components.${sut.id}.passphrase`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    v-if="
                        $page.props.available_session_modes_count > 1 ||
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
    import { isSelectable } from '@/components/v-select';

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
            const sessionData = this.session?.sut || [];

            return {
                sending: false,
                isCompliance,
                selectedSut,
                groupCertificatesList: [],
                componentsData: {
                    base_url: collect(sessionData)
                        .mapWithKeys((s) => [s.id, s.base_url])
                        .all(),
                    use_encryption: collect(sessionData)
                        .mapWithKeys((s) => [s.id, s.use_encryption])
                        .all(),
                    certificate_id: [],
                    ca_crt: [],
                    client_crt: [],
                    client_key: [],
                    passphrase: [],
                },
                currentSuts: {
                    list: this.suts.data,
                    selected: this?.session?.sut
                        ? collect(this.suts.data)
                            .whereIn(
                                'id',
                                Object.keys(this.session.sut).map((key) =>
                                    Number(key)
                                )
                            )
                            .all()
                        : this.selectedSut
                            ? [collect(this.suts.data).first()]
                            : [],
                },
                form: {
                    components: this.session?.sut || [],
                },
            };
        },
        mounted() {
            this.loadGroupCertificateList();

            this.setForm(this.componentsData);
        },
        watch: {
            'currentSuts.selected': {
                immediate: true,
                handler: function (values) {
                    const c = this.componentsData;
                    this.form.components = [];

                    Object.values(values || []).forEach((value) => {
                        const id = value.id;

                        this.form.components[id] = {
                            id,
                            base_url: c.base_url[c.id],
                            use_encryption: c.use_encryption[c.id],
                            certificate_id: c.certificate_id[c.id]
                                ? c.certificate_id[c.id].id
                                : null,
                            ca_crt: c.ca_crt[c.id],
                            client_crt: c.client_crt[c.id],
                            client_key: c.client_key[c.id],
                            passphrase: c.passphrase[c.id],
                        };
                    });
                },
            },
            componentsData: {
                deep: true,
                handler: function (values) {
                    this.setForm(values);
                },
            },
        },
        methods: {
            isSelectable,
            submit() {
                this.sending = true;

                this.$inertia.post(
                    route('sessions.register.sut.store'),
                    this.jsonToFormData(this.form),
                    {
                        onFinish: () => {
                            this.sending = false;
                        },
                    }
                );
            },
            setForm(values) {
                this.form.components.forEach((c) => {
                    c.base_url = values.base_url[c.id];
                    c.use_encryption = values.use_encryption[c.id];
                    c.certificate_id = values.certificate_id[c.id]
                        ? values.certificate_id[c.id].id
                        : null;
                    c.ca_crt = values.ca_crt[c.id];
                    c.client_crt = values.client_crt[c.id];
                    c.client_key = values.client_key[c.id];
                    c.passphrase = values.passphrase[c.id];
                });
            },
            showGroupCertificates(sut) {
                return (
                    this.hasGroupCertificates &&
                    !this.componentsData.ca_crt[sut.id] &&
                    !this.componentsData.client_crt[sut.id] &&
                    !this.componentsData.client_key[sut.id]
                );
            },
            loadGroupCertificateList(query = '') {
                axios
                    .get(route('sessions.register.group-certificate-candidates'), {
                        params: { q: query },
                    })
                    .then((result) => {
                        this.groupCertificatesList = result.data.data;

                        const collection = collect(result.data.data);
                        const ids = collect(this.session?.sut || [])
                            .mapWithKeys((s) => [s.id, s.certificate_id])
                            .all();

                        Object.keys(ids).forEach((key) => {
                            this.componentsData.certificate_id[
                                key
                                ] = collection.where('id', parseInt(ids[key])).first();
                            this.form.components[key].certificate_id = ids[key];
                        });
                    });
            },
            buildFormData(formData, data, parentKey) {
                if (
                    data &&
                    typeof data === 'object' &&
                    !(data instanceof Date) &&
                    !(data instanceof File)
                ) {
                    Object.keys(data).forEach((key) => {
                        this.buildFormData(
                            formData,
                            data[key],
                            parentKey ? `${parentKey}[${key}]` : key
                        );
                    });
                } else {
                    let value = data == null ? '' : data;

                    if ('boolean' === typeof value) {
                        value = Number(value);
                    }

                    formData.append(parentKey, value);
                }
            },
            jsonToFormData(data) {
                const formData = new FormData();

                this.buildFormData(formData, data);

                return formData;
            },
            setComponents(items) {
                this.form.component_ids = items?.map((item) => item.id) ?? [];
            },
        },
    };
</script>
