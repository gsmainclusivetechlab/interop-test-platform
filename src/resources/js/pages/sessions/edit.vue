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
                                <label class="form-label"> Name </label>
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
                                <label class="form-label"> Description </label>
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
                                <label class="form-label"> SUTs </label>
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
                                v-for="(component, i) in form.components"
                                :key="`component-${i}`"
                            >
                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ component.name }} URL
                                    </label>
                                    <input
                                        type="text"
                                        v-model="component.base_url"
                                        class="form-control"
                                        :readonly="component.implicit_sut_id"
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
                                <div
                                    class="d-flex mb-3"
                                    v-if="!component.implicit_sut_id"
                                >
                                    <label class="form-check form-switch">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            :checked="component.use_encryption"
                                            @change="
                                                (e) =>
                                                    changeEncryption(
                                                        component,
                                                        e.target.checked
                                                    )
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
                                        component.use_encryption &&
                                        !component.implicit_sut_id
                                    "
                                >
                                    <div
                                        class="mb-3"
                                        v-if="hasGroupCertificates"
                                    >
                                        <label class="form-label"
                                            >Certificate</label
                                        >
                                        <v-select
                                            v-model="
                                                component.certificate.serialized
                                            "
                                            placeholder="Select certificate..."
                                            label="name"
                                            :options="groupCertificatesList"
                                            :selectable="
                                                (option) =>
                                                    isSelectable(
                                                        option,
                                                        component.certificate
                                                            .serialized
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
                                            hasGroupCertificates &&
                                            !component.certificate.serialized
                                        "
                                    >
                                        or
                                    </div>
                                    <template
                                        v-if="!component.certificate.serialized"
                                    >
                                        <div class="mb-3">
                                            <label class="form-label"
                                                >CA certificate</label
                                            >
                                            <b-form-file
                                                v-model="
                                                    component.certificate.ca_crt
                                                "
                                                :placeholder="
                                                    component.hasNonGroupCertificate
                                                        ? 'CA certificate is uploaded'
                                                        : 'Choose file...'
                                                "
                                                :browse-text="
                                                    component.hasNonGroupCertificate
                                                        ? 'Change file'
                                                        : 'Browse'
                                                "
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
                                                >Client certificate (Optional)</label
                                            >
                                            <b-form-file
                                                v-model="
                                                    component.certificate
                                                        .client_crt
                                                "
                                                :placeholder="
                                                    component.hasNonGroupCertificate
                                                        ? 'Client certificate is uploaded'
                                                        : 'Choose file...'
                                                "
                                                :browse-text="
                                                    component.hasNonGroupCertificate
                                                        ? 'Change file'
                                                        : 'Browse'
                                                "
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
                                    </template>
                                </div>
                            </div>
                            <div
                                class="mb-3"
                                v-if="
                                    $page.props.auth.user.groups &&
                                    $page.props.auth.user.groups.filter(
                                        (g) =>
                                            g.default_session_id === session.id
                                    ).length
                                "
                            >
                                <label class="form-label">
                                    Default session for groups
                                </label>
                                <v-select
                                    :value="
                                        $page.props.auth.user.groups.filter(
                                            (g) =>
                                                g.default_session_id ===
                                                session.id
                                        )
                                    "
                                    label="name"
                                    multiple
                                    class="form-control d-flex p-0"
                                    disabled
                                />
                            </div>
                            <div v-if="hasGroupEnvironments" class="mb-3">
                                <label class="form-label"
                                    >Groups environments</label
                                >
                                <v-select
                                    v-model="groupsEnvs"
                                    multiple
                                    :options="groupsEnvsList"
                                    label="name"
                                    placeholder="Select environments"
                                    :selectable="
                                        (option) =>
                                            isSelectable(option, groupsEnvs)
                                    "
                                    class="form-control d-flex p-0 mb-1"
                                />
                                <span class="text-muted small"
                                    >Chose groups environments and merge with
                                    current</span
                                >
                                <div class="text-right">
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        @click="
                                            mergeGroupsEnvs(
                                                groupsEnvs,
                                                form.environments,
                                                form.fileEnvironments
                                            )
                                        "
                                    >
                                        Merge
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"
                                    >Test cases environments</label
                                >
                                <span class="d-block text-muted small"
                                    >You can update environments from included
                                    test cases</span
                                >
                                <div class="text-right">
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        @click="
                                            loadTestCasesEnvs(
                                                form.test_cases
                                            ).then((data) => {
                                                mergeTestCasesEnvs(
                                                    data.data,
                                                    form.environments,
                                                    form.fileEnvironments
                                                );
                                            })
                                        "
                                    >
                                        Merge
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Environments </label>
                                <environments v-model="form.environments" />
                                <div
                                    class="text-danger small mt-2"
                                    v-if="$page.props.errors.environments"
                                >
                                    <strong>{{
                                        $page.props.errors.environments
                                    }}</strong>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    File environments
                                </label>
                                <environments
                                    v-model="form.fileEnvironments"
                                    envs-type="file"
                                />
                                <div
                                    class="text-danger small mt-2"
                                    v-if="$page.props.errors.fileEnvironments"
                                >
                                    <strong>{{
                                        $page.props.errors.fileEnvironments
                                    }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label"> Test Cases </label>
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
import mixinEnvs from '@/pages/sessions/mixins/environments';
import mixinCrts from '@/pages/sessions/mixins/certificates';

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
    mixins: [mixinVSelect, mixinEnvs, mixinCrts],
    data() {
        return {
            sending: false,
            isCompliance: this.session.type === 'compliance',
            groupsEnvs: this.session.groupEnvironment?.data ?? [],
            groupsEnvsList: [],
            groupCertificatesList: [],
            form: {
                name: this.session.name,
                description: this.session.description,
                environments: Object.entries(
                    this.session.environments ?? {}
                )?.map(([key, value]) => ({ key: key, value: value })),
                fileEnvironments: this.session.fileEnvironments?.map((el) => ({
                    key: el.name,
                    value: el.id,
                    file_name: el.file_name,
                })),
                components: this.components.data?.map((el) => ({
                    ...el,
                    certificate: {
                        serialized: null,
                        ca_crt: null,
                        client_crt: null,
                        group_id: el.certificate?.group_id,
                    },
                    hasNonGroupCertificate:
                        el.certificate_id && !el.certificate?.certificable_id,
                })),
                test_cases: this.session.testCases.data?.map((el) => el.id),
            },
        };
    },
    mounted() {
        this.loadGroupsEnvsList().then((result) => {
            this.groupsEnvsList = result.data.data;
        });
        this.loadGroupCertificateList(this.session.id).then((result) => {
            this.groupCertificatesList = result.data.data;

            this.form.components.forEach(
                (el) =>
                    (el.certificate.serialized = this.groupCertificatesList?.find(
                        (crt) => crt.id === el.certificate_id
                    ))
            );
        });
    },
    methods: {
        submit() {
            const form = {
                _method: 'PUT',
                name: this.form.name,
                description: this.form.description,
                environments: Object.fromEntries(
                    this.form.environments.map((el) => [el.key, el.value])
                ),
                fileEnvironments: Object.fromEntries(
                    this.form.fileEnvironments.map((el) => [el.key, el.value])
                ),
                components: Object.fromEntries(
                    this.form.components?.map((el) => [
                        el.id,
                        {
                            id: el.id,
                            base_url: el.base_url,
                            use_encryption: el.use_encryption,
                            certificate_id:
                                el.certificate.serialized?.id ??
                                (el.hasNonGroupCertificate
                                    ? el.certificate_id
                                    : null),
                        },
                    ])
                ),
                certificates: Object.fromEntries(
                    this.form.components?.map((el) => {
                        const crt = el.certificate.serialized?.id ?? {
                            ca_crt: el.certificate.ca_crt,
                            client_crt: el.certificate.client_crt,
                        };

                        return [el.id, crt];
                    })
                ),
                test_cases: this.form.test_cases,
            };

            this.sending = true;
            this.$inertia.post(
                route('sessions.update', this.session.id),
                serialize(form, {
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
    },
};
</script>
