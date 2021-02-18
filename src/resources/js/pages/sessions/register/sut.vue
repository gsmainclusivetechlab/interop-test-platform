<template>
    <layout :components="components" :session="session">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">SUT selection</h3>
                </div>
                <div class="card-body">
                    <div
                        class="alert alert-danger"
                        role="alert"
                        v-for="versionConflict in versionConflicts"
                        v-html="versionConflict"
                    ></div>
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
                    <div class="list-group">
                        <template v-for="(sut, i) in components.data">
                            <div
                                v-if="component_ids.includes(sut.id)"
                                class="list-group-item"
                                :key="i"
                            >
                                <div class="mb-3" v-if="getVersions(sut)">
                                    <label class="form-label"
                                        >{{ sut.name }} version</label
                                    >
                                    <v-select
                                        v-model="componentsData.version[sut.id]"
                                        :options="getVersions(sut)"
                                        label="name"
                                        placeholder="Select version..."
                                        :selectable="
                                            (option) =>
                                                isSelectable(
                                                    option,
                                                    componentsData.version[
                                                        sut.id
                                                    ]
                                                )
                                        "
                                        class="form-control d-flex p-0"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${sut.id}.version`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `components.${sut.id}.version`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        {{
                                            $page.props.errors[
                                                `components.${sut.id}.version`
                                            ]
                                        }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"
                                        >{{ sut.name }} URL</label
                                    >
                                    <input
                                        v-model="
                                            componentsData.base_url[sut.id]
                                        "
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `components.${sut.id}.base_url`
                                                ],
                                        }"
                                        :readonly="implicitSutUrl(sut)"
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
                                <div class="mb-3" v-if="!implicitSutUrl(sut)">
                                    <label class="form-check form-switch">
                                        <input
                                            v-model="
                                                componentsData.use_encryption[
                                                    sut.id
                                                ]
                                            "
                                            class="form-check-input"
                                            type="checkbox"
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
                                <div
                                    v-if="
                                        componentsData.use_encryption[sut.id] &&
                                        !implicitSutUrl(sut)
                                    "
                                >
                                    <div v-if="showGroupCertificates(sut)">
                                        <label class="form-label"
                                            >Certificate</label
                                        >
                                        <v-select
                                            v-model="
                                                componentsData.certificate_id[
                                                    sut.id
                                                ]
                                            "
                                            :options="groupCertificatesList"
                                            label="name"
                                            placeholder="Select group certificate..."
                                            :selectable="
                                                (option) =>
                                                    isSelectable(
                                                        option,
                                                        componentsData
                                                            .certificate_id[
                                                            sut.id
                                                        ]
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
                                            !componentsData.certificate_id[
                                                sut.id
                                            ]
                                        "
                                    >
                                        or
                                    </div>
                                    <template
                                        v-if="
                                            !componentsData.certificate_id[
                                                sut.id
                                            ]
                                        "
                                    >
                                        <div class="mb-3">
                                            <label class="form-label"
                                                >CA certificate</label
                                            >
                                            <b-form-file
                                                v-model="
                                                    componentsData.ca_crt[
                                                        sut.id
                                                    ]
                                                "
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
                                                    componentsData.client_crt[
                                                        sut.id
                                                    ]
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
                                            <label class="form-label"
                                                >Client key</label
                                            >
                                            <b-form-file
                                                v-model="
                                                    componentsData.client_key[
                                                        sut.id
                                                    ]
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
                                                    componentsData.passphrase[
                                                        sut.id
                                                    ]
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
                    type="submit"
                    class="btn btn-primary ml-auto"
                    v-if="!Object.values(versionConflicts).length"
                >
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
import { serialize } from '@/utilities/object-to-formdata';
import Layout from '@/layouts/sessions/register';
import mixinVSelect from '@/components/v-select/mixin';

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
    mixins: [mixinVSelect],
    data() {
        const isCompliance = this.session.type === 'compliance';
        const selectedSut = isCompliance && this.components.data.length === 1;
        const sessionData = this.session?.sut ?? [];

        return {
            sending: false,
            isCompliance,
            selectedSut,
            groupCertificatesList: [],
            component_ids: [],
            versionConflicts: collect(this.versions)
                .filter((v) => {
                    return typeof v === 'string';
                })
                .all(),
            componentsData: {
                base_url: collect(sessionData)
                    .mapWithKeys((s) => [s.id, s.base_url])
                    .all(),
                version: collect(sessionData)
                    .mapWithKeys((s) => [s.id, s.version])
                    .all(),
                use_encryption: collect(sessionData)
                    .mapWithKeys((s) => [
                        s.id,
                        s.use_encryption === '0' ? false : s.use_encryption,
                    ])
                    .all(),
                implicit_sut_id: collect(sessionData)
                    .mapWithKeys((s) => [s.id, s.implicit_sut_id])
                    .all(),
                certificate_id: [],
                ca_crt: [],
                client_crt: [],
                client_key: [],
                passphrase: [],
            },
            currentSuts: {
                list: this.components.data,
                selected: this?.session?.sut
                    ? collect(this.components.data)
                          .whereIn(
                              'id',
                              Object.keys(this.session.sut).map((key) =>
                                  Number(key)
                              )
                          )
                          .all()
                    : this.selectedSut
                    ? [collect(this.components.data).first()]
                    : [],
            },
        };
    },
    mounted() {
        this.loadGroupCertificateList();
    },
    methods: {
        submit() {
            this.sending = true;

            this.$inertia.post(
                route('sessions.register.sut.store'),
                serialize(this.getForm(), {
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
        getForm() {
            let components = {},
                c = this.componentsData;

            Object.values(this.component_ids).forEach((id) => {
                components[id] = {
                    id,
                    base_url: c.base_url[id],
                    version: c.version[id],
                    use_encryption: c.use_encryption[id],
                    certificate_id: c.certificate_id[id]
                        ? c.certificate_id[id].id
                        : null,
                    ca_crt: c.ca_crt[id],
                    client_crt: c.client_crt[id],
                    client_key: c.client_key[id],
                    passphrase: c.passphrase[id],
                    implicit_sut_id: c.implicit_sut_id[id],
                };
            });

            return { components };
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
                    });
                });
        },
        setComponents(items) {
            this.component_ids = items?.map((item) => item.id) ?? [];
        },
        getVersions(sut) {
            if (typeof this.versions[sut.id] === 'object') {
                return this.versions[sut.id];
            } else if (this.implicitSuts[sut.slug]) {
                const versions = collect(this.implicitSuts[sut.slug]).pluck(
                    'version'
                );

                if (versions.count() === 1) {
                    this.componentsData.version[sut.id] = versions.first();
                }

                return versions.all();
            }
            return false;
        },
        implicitSutUrl(sut) {
            const implicitSut = collect(this.implicitSuts[sut.slug])
                .filter((implicitSut) => {
                    const regex = new RegExp(implicitSut.version, 'g');

                    return this.componentsData.version[sut.id]?.match(regex);
                })
                .first();

            this.componentsData.base_url[sut.id] = implicitSut?.url;
            this.componentsData.implicit_sut_id[sut.id] = implicitSut?.id;

            return implicitSut?.url;
        },
    },
};
</script>
