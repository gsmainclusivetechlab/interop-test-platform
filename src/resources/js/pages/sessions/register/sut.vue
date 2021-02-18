<template>
    <layout :components="components" :session="session">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">SUT selection</h3>
                </div>
                <div class="card-body">
                    <div
                        v-for="(versionConflict, i) in versionConflicts"
                        v-html="versionConflict"
                        class="alert alert-danger"
                        role="alert"
                        :key="i"
                    ></div>
                    <div class="mb-3">
                        <label class="form-label">SUTs</label>
                        <v-select
                            v-model="form.components"
                            :options="componentsList"
                            label="name"
                            multiple
                            placeholder="Select SUT..."
                            :selectable="
                                (option) =>
                                    isSelectable(option, form.components)
                            "
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.components,
                            }"
                        />
                        <span
                            v-if="$page.props.errors.components"
                            class="invalid-feedback"
                        >
                            {{ $page.props.errors.components }}
                        </span>
                    </div>
                    <div class="list-group">
                        <template v-for="(component, i) in form.components">
                            <div
                                class="list-group-item"
                                :key="`component-${i}`"
                            >
                                <div
                                    v-if="
                                        component.versions &&
                                        component.versions.length
                                    "
                                    class="mb-3"
                                >
                                    <label class="form-label">{{
                                        `${component.name} version`
                                    }}</label>
                                    <v-select
                                        v-model="component.version"
                                        :options="component.versions"
                                        label="name"
                                        placeholder="Select version..."
                                        :selectable="
                                            (option) =>
                                                isSelectable(
                                                    option,
                                                    component.version
                                                )
                                        "
                                        class="form-control d-flex p-0"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${component.id}.version`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `components.${component.id}.version`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        {{
                                            $page.props.errors[
                                                `components.${component.id}.version`
                                            ]
                                        }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"
                                        >{{ component.name }} URL</label
                                    >
                                    <input
                                        v-model="component.base_url"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${component.id}.base_url`
                                                ],
                                        }"
                                        class="form-control"
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
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            :checked="
                                                `${component.use_encryption}` ===
                                                '1'
                                            "
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
                                <template v-if="component.use_encryption">
                                    <div v-if="hasGroupCertificates">
                                        <label class="form-label"
                                            >Certificate</label
                                        >
                                        <v-select
                                            v-model="
                                                component.certificate.serialized
                                            "
                                            :options="groupCertificatesList"
                                            label="name"
                                            placeholder="Select group certificate..."
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
                                                    $page.props.errors
                                                        .components,
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
                                                placeholder="Choose file..."
                                                :class="{
                                                    'is-invalid':
                                                        $page.props.errors[
                                                            `components.${component.id}.ca_crt`
                                                        ],
                                                }"
                                            />
                                            <span
                                                v-if="
                                                    $page.props.errors[
                                                        `components.${component.id}.ca_crt`
                                                    ]
                                                "
                                                class="invalid-feedback"
                                            >
                                                <strong>
                                                    {{
                                                        $page.props.errors[
                                                            `components.${component.id}.ca_crt`
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
                                                    component.certificate
                                                        .client_crt
                                                "
                                                placeholder="Choose file..."
                                                :class="{
                                                    'is-invalid':
                                                        $page.props.errors[
                                                            `components.${component.id}.client_crt`
                                                        ],
                                                }"
                                            />
                                            <span
                                                v-if="
                                                    $page.props.errors[
                                                        `components.${component.id}.client_crt`
                                                    ]
                                                "
                                                class="invalid-feedback"
                                            >
                                                <strong>
                                                    {{
                                                        $page.props.errors[
                                                            `components.${component.id}.client_crt`
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
                                                    component.certificate
                                                        .client_key
                                                "
                                                placeholder="Choose file..."
                                                :class="{
                                                    'is-invalid':
                                                        $page.props.errors[
                                                            `components.${component.id}.client_key`
                                                        ],
                                                }"
                                            />
                                            <span
                                                v-if="
                                                    $page.props.errors[
                                                        `components.${component.id}.client_key`
                                                    ]
                                                "
                                                class="invalid-feedback"
                                            >
                                                <strong>
                                                    {{
                                                        $page.props.errors[
                                                            `components.${component.id}.client_key`
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
                                                    component.certificate
                                                        .passphrase
                                                "
                                                :class="{
                                                    'is-invalid':
                                                        $page.props.errors[
                                                            `components.${component.id}.passphrase`
                                                        ],
                                                }"
                                                class="form-control"
                                            />
                                            <span
                                                v-if="
                                                    $page.props.errors[
                                                        `components.${component.id}.passphrase`
                                                    ]
                                                "
                                                class="invalid-feedback"
                                            >
                                                <strong>
                                                    {{
                                                        $page.props.errors[
                                                            `components.${component.id}.passphrase`
                                                        ]
                                                    }}
                                                </strong>
                                            </span>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="route('sessions.register.info')"
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <button
                    v-if="!Object.values(versionConflicts).length"
                    type="submit"
                    class="btn btn-primary ml-auto"
                >
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    <span>Next</span>
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import { serialize } from '@/utilities/object-to-formdata';
import Layout from '@/layouts/sessions/register';
import mixinVSelect from '@/components/v-select/mixin';
import mixinCrts from '@/pages/sessions/mixins/certificates';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: false,
        },
        components: {
            type: Object,
            required: true,
        },
        versions: {
            type: Object | Array,
            required: true,
        },
        hasGroupCertificates: {
            type: Boolean,
            required: true,
        },
    },
    mixins: [mixinVSelect, mixinCrts],
    data() {
        return {
            sending: false,
            isCompliance: this.session.type === 'compliance',
            groupCertificatesList: [],
            versionConflicts: collect(this.versions)
                .filter((v) => {
                    return typeof v === 'string';
                })
                .all(),
            componentsList: this.components.data?.map((el) => ({
                ...el,
                base_url: null,
                use_encryption: 0,
                certificate_id: null,
                certificate: {
                    ca_crt: null,
                    client_crt: null,
                    client_key: null,
                    passphrase: null,
                    serialized: null,
                },
                version: null,
            })),
            form: {
                components: [],
            },
        };
    },
    mounted() {
        this.loadGroupCertificateList().then((result) => {
            this.groupCertificatesList = result.data.data;

            this.form.components?.forEach(
                (el) =>
                    (el.certificate.serialized = this.groupCertificatesList?.find(
                        (crt) => `${crt.id}` === `${el.certificate_id}`
                    ))
            );
        });

        if (this.isCompliance && this.componentsList?.length === 1) {
            this.componentsList.forEach((el) => this.form.components.push(el));
        }

        if (this.componentsList?.length > 1) {
            this.componentsList
                .filter(
                    (el) => `${el.id}` === `${this.session?.sut?.[el.id]?.id}`
                )
                .forEach((el) => {
                    const sut = this.session.sut?.[el.id];

                    el.id = sut.id;
                    el.certificate_id = sut.certificate_id;
                    el.base_url = sut.base_url;
                    el.use_encryption = sut.use_encryption;
                    el.version = sut.version;

                    this.form.components.push(el);
                });
        }
    },
    methods: {
        submit() {
            const form = {
                components: Object.fromEntries(
                    this.form.components?.map((el) => [
                        el.id,
                        {
                            id: el.id,
                            base_url: el.base_url,
                            use_encryption: el.use_encryption,
                            certificate_id: el.certificate_id,
                            ca_crt: el.certificate.ca_crt,
                            client_crt: el.certificate.client_crt,
                            client_key: el.certificate.client_key,
                            passphrase: el.certificate.passphrase,
                            version: el.version,
                        },
                    ])
                ),
                components_ids: this.form.components?.map((el) => el.id),
            };

            this.sending = true;

            this.$inertia.post(
                route('sessions.register.sut.store'),
                serialize(form, {
                    indices: true,
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
