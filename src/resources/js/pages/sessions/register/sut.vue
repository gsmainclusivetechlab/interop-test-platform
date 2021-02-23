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
                                        :clearable="
                                            component.versions &&
                                            component.versions.length > 1
                                        "
                                        class="form-control d-flex p-0"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${component.id}.version`
                                                ],
                                        }"
                                        @input="implicitSutUrl(component)"
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
                                        :readonly="component.implicit_sut_id"
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
                                <div
                                    class="mb-3"
                                    v-if="!component.implicit_sut_id"
                                >
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
                                <template
                                    v-if="
                                        `${component.use_encryption}` === '1' &&
                                        !implicitSutUrl(component)
                                    "
                                >
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
        implicitSuts: {
            type: Object | Array,
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
            componentsList: [],
            form: {
                components: [],
            },
        };
    },
    mounted() {
        this.componentsList = this.components.data?.map((c) => {
            const versions =
                typeof this.versions[c.id] !== 'string' &&
                this.versions[c.id]?.length > 0
                    ? this.versions[c.id]
                    : this.implicitSuts[c.slug]
                    ? this.implicitSuts[c.slug]?.map((iSut) => iSut.version)
                    : null;

            const version = versions?.length === 1 ? versions?.[0] : null;
            const iSut = this.implicitSuts[c.slug]?.find((iSut) =>
                this.checkSutParity(version, iSut.version)
            );

            return {
                name: c.name,
                id: c.id,
                slug: c.slug,
                connections: c.connections,
                base_url: iSut?.url ?? null,
                use_encryption: 0,
                certificate_id: null,
                certificate: {
                    ca_crt: null,
                    client_crt: null,
                    client_key: null,
                    passphrase: null,
                    serialized: null,
                },
                versions: versions,
                version: version,
                implicit_sut_id: iSut?.id ?? null,
            };
        });

        if (this.isCompliance && this.componentsList?.length === 1) {
            this.componentsList.forEach((c) => this.form.components.push(c));
        }

        if (this.componentsList?.length > 1) {
            this.componentsList
                .filter((c) => `${c.id}` === `${this.session?.sut?.[c.id]?.id}`)
                .forEach((c) => {
                    const sut = this.session.sut?.[c.id];

                    c.id = sut.id;
                    c.certificate_id = sut.certificate_id;
                    c.base_url = sut.base_url;
                    c.use_encryption = sut.use_encryption;
                    c.version = sut.version;
                    c.implicit_sut_id = sut.implicit_sut_id;

                    this.form.components.push(c);
                });
        }

        this.loadGroupCertificateList().then((result) => {
            this.groupCertificatesList = result.data.data;

            this.form.components?.forEach(
                (c) =>
                    (c.certificate.serialized = this.groupCertificatesList?.find(
                        (crt) => `${crt.id}` === `${c.certificate_id}`
                    ))
            );
        });
    },
    methods: {
        submit() {
            const form = {
                components: Object.fromEntries(
                    this.form.components?.map((c) => [
                        c.id,
                        {
                            id: c.id,
                            base_url: c.base_url,
                            use_encryption: c.use_encryption,
                            certificate_id: c.certificate_id,
                            ca_crt: c.certificate.ca_crt,
                            client_crt: c.certificate.client_crt,
                            client_key: c.certificate.client_key,
                            passphrase: c.certificate.passphrase,
                            version: c.version,
                            implicit_sut_id: c.implicit_sut_id,
                        },
                    ])
                ),
                components_ids: this.form.components?.map((c) => c.id),
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
        checkSutParity(sutV, iSutV) {
            const regexp = new RegExp(iSutV, 'g');

            return sutV?.match(regexp)?.length > 0 ?? false;
        },
        implicitSutUrl(sut) {
            const implicitSut = this.implicitSuts?.[sut.slug]?.find((iSut) =>
                this.checkSutParity(sut.version, iSut.version)
            );

            sut.implicit_sut_id = implicitSut?.id ?? null;

            if (implicitSut) {
                sut.base_url = implicitSut.url;
            }
        },
    },
};
</script>
