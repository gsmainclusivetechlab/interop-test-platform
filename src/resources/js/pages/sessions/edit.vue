<template>
    <layout :session="session">
        <div class="col-10 m-auto mt-3">
            <form class="card" @submit.prevent="submit">
                <div class="card-header">
                    <h3 class="card-title">Update session info</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label>
                                    <b>Name</b>
                                </label>
                                <input
                                    type="text"
                                    v-model="form.name"
                                    class="form-control"
                                    :readonly="isCompliance"
                                    :class="{
                                        'is-invalid': $page.props.errors.name,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.name"
                                    class="invalid-feedback"
                                >
                                    {{ $page.props.errors.name }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <label>
                                    <b>Description</b>
                                </label>
                                <textarea
                                    class="form-control"
                                    rows="5"
                                    v-model="form.description"
                                    :readonly="isCompliance"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.description,
                                    }"
                                ></textarea>
                                <span
                                    v-if="$page.props.errors.description"
                                    class="invalid-feedback"
                                >
                                    {{ $page.props.errors.description }}
                                </span>
                            </div>
                            <div class="mb-3" v-if="components.data">
                                <label>
                                    <b>SUTs</b>
                                </label>
                                <v-select
                                    :value="components.data"
                                    label="name"
                                    multiple
                                    class="form-control d-flex p-0"
                                    disabled
                                />
                            </div>
                            <div
                                class="mb-3"
                                v-for="(component, i) in components.data"
                                :key="`component-${i}`"
                            >
                                <div class="mb-3">
                                    <label>
                                        <b>{{ component.name }} URL</b>
                                    </label>
                                    <input
                                        type="text"
                                        v-model="
                                            form.components[component.id]
                                                .base_url
                                        "
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${component.id}.base_url`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `components.${component.id}.base_url`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        {{
                                            $page.props.errors[
                                                `components.${component.id}.base_url`
                                            ]
                                        }}
                                    </span>
                                </div>
                                <div class="d-flex mb-3">
                                    <label class="form-check form-switch">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            v-model="
                                                form.components[component.id]
                                                    .use_encryption
                                            "
                                        />
                                        <span class="form-check-label"
                                            >Use Encryption</span
                                        >
                                    </label>
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `components.${component.id}.use_encryption`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `components.${component.id}.use_encryption`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <div
                                    v-if="
                                        form.components[component.id]
                                            .use_encryption
                                    "
                                >
                                    <div
                                        class="mb-3"
                                        v-if="showGroupCertificates(component)"
                                    >
                                        <label class="form-label"
                                            >Certificate</label
                                        >
                                        <v-select
                                            v-model="
                                                selectedCertificates[
                                                    component.id
                                                ]
                                            "
                                            placeholder="Select group certificate..."
                                            label="name"
                                            :options="groupCertificatesList"
                                            :selectable="
                                                (option) =>
                                                    isSelectable(
                                                        option,
                                                        selectedCertificates[
                                                            component.id
                                                        ]
                                                    )
                                            "
                                            class="form-control d-flex p-0"
                                            :class="{
                                                'is-invalid':
                                                    $page.props.errors[
                                                        `components.${component.id}.certificate_id`
                                                    ],
                                            }"
                                        />
                                        <span
                                            v-if="
                                                $page.props.errors[
                                                    `components.${component.id}.certificate_id`
                                                ]
                                            "
                                            class="invalid-feedback"
                                        >
                                            <strong>
                                                {{
                                                    $page.props.errors[
                                                        `components.${component.id}.certificate_id`
                                                    ]
                                                }}
                                            </strong>
                                        </span>
                                    </div>
                                    <div
                                        class="hr-text"
                                        v-if="
                                            showGroupCertificates(component) &&
                                            !selectedCertificates[component.id]
                                        "
                                    >
                                        or
                                    </div>
                                    <template
                                        v-if="
                                            !selectedCertificates[component.id]
                                        "
                                    >
                                        <div class="mb-3">
                                            <label class="form-label"
                                                >CA certificate</label
                                            >
                                            <b-form-file
                                                v-model="
                                                    form.certificates[
                                                        component.id
                                                    ].ca_crt
                                                "
                                                placeholder="Choose file..."
                                                :class="{
                                                    'is-invalid':
                                                        $page.props.errors[
                                                            `certificates.${component.id}.ca_crt`
                                                        ],
                                                }"
                                            />
                                            <span
                                                v-if="
                                                    $page.props.errors[
                                                        `certificates.${component.id}.ca_crt`
                                                    ]
                                                "
                                                class="invalid-feedback"
                                            >
                                                <strong>
                                                    {{
                                                        $page.props.errors[
                                                            `certificates.${component.id}.ca_crt`
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
                                                    form.certificates[
                                                        component.id
                                                    ].client_crt
                                                "
                                                placeholder="Choose file..."
                                                :class="{
                                                    'is-invalid':
                                                        $page.props.errors[
                                                            `certificates.${component.id}.client_crt`
                                                        ],
                                                }"
                                            />
                                            <span
                                                v-if="
                                                    $page.props.errors[
                                                        `certificates.${component.id}.client_crt`
                                                    ]
                                                "
                                                class="invalid-feedback"
                                            >
                                                <strong>
                                                    {{
                                                        $page.props.errors[
                                                            `certificates.${component.id}.client_crt`
                                                        ]
                                                    }}
                                                </strong>
                                            </span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label"
                                                >Client key</label
                                            >
                                            <b-form-file
                                                v-model="
                                                    form.certificates[
                                                        component.id
                                                    ].client_key
                                                "
                                                placeholder="Choose file..."
                                                :class="{
                                                    'is-invalid':
                                                        $page.props.errors[
                                                            `certificates.${component.id}.client_key`
                                                        ],
                                                }"
                                            />
                                            <span
                                                v-if="
                                                    $page.props.errors[
                                                        `certificates.${component.id}.client_key`
                                                    ]
                                                "
                                                class="invalid-feedback"
                                            >
                                                <strong>
                                                    {{
                                                        $page.props.errors[
                                                            `certificates.${component.id}.client_key`
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
                                                    form.certificates[
                                                        component.id
                                                    ].passphrase
                                                "
                                                :class="{
                                                    'is-invalid':
                                                        $page.props.errors[
                                                            `certificates.${component.id}.passphrase`
                                                        ],
                                                }"
                                                class="form-control"
                                            />
                                            <span
                                                v-if="
                                                    $page.props.errors[
                                                        `certificates.${component.id}.passphrase`
                                                    ]
                                                "
                                                class="invalid-feedback"
                                            >
                                                <strong>
                                                    {{
                                                        $page.props.errors[
                                                            `certificates.${component.id}.passphrase`
                                                        ]
                                                    }}
                                                </strong>
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div
                                class="mb-3"
                                v-if="
                                    $page.props.auth.user.groups &&
                                    $page.props.auth.user.groups.length > 0
                                "
                            >
                                <label>
                                    <b>Session default for groups</b>
                                </label>
                                <v-select
                                    :value="$page.props.auth.user.groups"
                                    label="name"
                                    multiple
                                    class="form-control d-flex p-0"
                                    disabled
                                />
                            </div>
                            <div class="mb-3">
                                <label>
                                    <b>Environments</b>
                                </label>
                                <v-select
                                    v-if="hasGroupEnvironments"
                                    v-model="groupEnvironment"
                                    :options="groupEnvironmentsList"
                                    :selectable="
                                        (option) =>
                                            isSelectable(
                                                option,
                                                groupEnvironment
                                            )
                                    "
                                    label="name"
                                    class="form-control d-flex p-0 mb-3"
                                />
                                <environments
                                    v-model="form.environments"
                                    ref="environments"
                                />
                                <div
                                    class="text-danger small mt-2"
                                    v-if="$page.props.errors.environments"
                                >
                                    <strong>{{
                                        $page.props.errors.environments
                                    }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label>
                                    <b>Test Cases</b>
                                </label>
                                <test-case-checkboxes
                                    style="max-height: 485px"
                                    :session="session"
                                    :useCases="useCases"
                                    :isCompliance="isCompliance"
                                    v-model="form.test_cases"
                                />
                                <div
                                    class="text-danger small mt-3"
                                    v-if="$page.props.errors.test_cases"
                                >
                                    <strong>{{
                                        $page.props.errors.test_cases
                                    }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <inertia-link
                        :href="route('sessions.show', session.id)"
                        class="btn btn-link"
                    >
                        Cancel
                    </inertia-link>
                    <button type="submit" class="btn btn-primary btn-space">
                        <span
                            v-if="sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </layout>
</template>

<script>
import { serialize } from '@/utilities/object-to-formdata';
import Layout from '@/layouts/sessions/app';
import Environments from '@/components/environments';
import TestCaseCheckboxes from '@/components/sessions/test-case-checkboxes';
import mixinVSelect from '@/components/v-select/mixin';

export default {
    components: {
        Layout,
        Environments,
        TestCaseCheckboxes,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        useCases: {
            type: Object,
            required: true,
        },
        components: {
            type: Object,
            required: true,
        },
        hasGroupEnvironments: {
            type: Boolean,
            required: true,
        },
        hasGroupCertificates: {
            type: Boolean,
            required: true,
        },
    },
    mixins: [mixinVSelect],
    data() {
        return {
            sending: false,
            isCompliance: this.session.type === 'compliance',
            groupEnvironment: this.session.groupEnvironment
                ? this.session.groupEnvironment.data
                : null,
            groupEnvironmentsList: [],
            groupCertificatesList: [],
            selectedCertificates: [],
            form: {
                _method: 'PUT',
                name: this.session.name,
                description: this.session.description,
                group_environment_id: this.session.groupEnvironment
                    ? this.session.groupEnvironment.data.id
                    : null,
                environments: this.session.environments,
                components: collect(this.components.data)
                    .map((component) => {
                        return collect(component)
                            .only([
                                'id',
                                'base_url',
                                'use_encryption',
                                'certificate_id',
                            ])
                            .all();
                    })
                    .keyBy('id')
                    .all(),
                certificates: collect(this.components.data)
                    .keyBy('id')
                    .map(() => {
                        return {};
                    })
                    .all(),
                test_cases: collect(this.session.testCases.data)
                    .pluck('id')
                    .all(),
            },
        };
    },
    watch: {
        groupEnvironment: {
            immediate: false,
            handler(value) {
                this.form.group_environment_id = value ? value.id : null;
                if (value !== null) {
                    this.form.environments = value.variables;
                    this.$refs.environments.syncEnvironments(
                        this.form.environments
                    );
                }
            },
        },
        selectedCertificates: {
            immediate: false,
            handler(values) {
                Object.keys(values).forEach(
                    (key) =>
                        (this.form.components[key].certificate_id =
                            values[key]?.id)
                );
            },
        },
    },
    mounted() {
        this.loadGroupEnvironmentList();
        this.loadGroupCertificateList();
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('sessions.update', this.session.id),
                serialize(this.form, {
                    indices: true,
                    booleansAsIntegers: true,
                }),
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
        showGroupCertificates(component) {
            return (
                this.hasGroupCertificates &&
                !this.form.certificates[component.id].ca_crt &&
                !this.form.certificates[component.id].client_crt &&
                !this.form.certificates[component.id].client_key
            );
        },
        loadGroupEnvironmentList(query = '') {
            axios
                .get(route('sessions.register.group-environment-candidates'), {
                    params: { q: query },
                })
                .then((result) => {
                    this.groupEnvironmentsList = result.data.data;
                });
        },
        searchGroupEnvironments(query, callback) {
            this.loadGroupEnvironmentList(query);
            callback();
        },
        loadGroupCertificateList(query = '') {
            axios
                .get(route('sessions.register.group-certificate-candidates'), {
                    params: {
                        q: query,
                        session: this.session.id,
                    },
                })
                .then((result) => {
                    this.groupCertificatesList = result.data.data;

                    const collection = collect(result.data.data);
                    const ids = collect(this.components.data)
                        .mapWithKeys((s) => [s.id, s.certificate_id])
                        .all();

                    Object.keys(ids).forEach((key) => {
                        this.selectedCertificates[key] = collection
                            .where('id', parseInt(ids[key]))
                            .first();
                    });
                });
        },
    },
};
</script>
