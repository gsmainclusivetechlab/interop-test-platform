<template>
    <layout>
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h2 class="card-title">{{ $t('page.title') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div
                            class="alert alert-warning"
                            role="alert"
                            v-if="implicitSut.has_sessions"
                        >
                            {{ $t('message_has_session') }}
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.slug')
                        }}</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.slug,
                            }"
                            v-model="form.slug"
                            :readonly="implicitSut.has_sessions"
                        />
                        <span
                            v-if="$page.props.errors.slug"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.slug }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.version')
                        }}</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.version,
                            }"
                            v-model="form.version"
                            :readonly="implicitSut.has_sessions"
                        />
                        <span
                            v-if="$page.props.errors.version"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.version }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{ $t('inputs.url') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.url,
                            }"
                            v-model="form.url"
                        />
                        <span
                            v-if="$page.props.errors.url"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.url }}
                            </strong>
                        </span>
                    </div>
                    <div class="d-flex mb-3">
                        <label class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                v-model="form.use_encryption"
                            />
                            <span class="form-check-label">Use Encryption</span>
                        </label>
                        <span
                            v-if="$page.props.errors.use_encryption"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.use_encryption }}
                            </strong>
                        </span>
                    </div>
                    <div class="row" v-if="form.use_encryption">
                        <div class="col-6 mb-3">
                            <label class="form-label">{{
                                $t('inputs.ca_crt')
                            }}</label>
                            <b-form-file
                                v-model="form.ca_crt"
                                :placeholder="
                                    implicitSut.certificate
                                        ? 'CA certificate'
                                        : 'Choose file...'
                                "
                                :class="{
                                    'is-invalid': $page.props.errors.ca_crt,
                                }"
                            />
                            <span
                                v-if="$page.props.errors.ca_crt"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.props.errors.ca_crt }}
                                </strong>
                            </span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">{{
                                $t('inputs.client_crt')
                            }}</label>
                            <b-form-file
                                v-model="form.client_crt"
                                :placeholder="
                                    implicitSut.certificate
                                        ? 'Client certificate'
                                        : 'Choose file...'
                                "
                                :class="{
                                    'is-invalid': $page.props.errors.client_crt,
                                }"
                            />
                            <span
                                v-if="$page.props.errors.client_crt"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.props.errors.client_crt }}
                                </strong>
                            </span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">{{
                                $t('inputs.client_key')
                            }}</label>
                            <b-form-file
                                v-model="form.client_key"
                                :placeholder="
                                    implicitSut.certificate
                                        ? 'Client key'
                                        : 'Choose file...'
                                "
                                :class="{
                                    'is-invalid': $page.props.errors.client_key,
                                }"
                            />
                            <span
                                v-if="$page.props.errors.client_key"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.props.errors.client_key }}
                                </strong>
                            </span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">{{
                                $t('inputs.passphrase')
                            }}</label>
                            <input
                                name="slug"
                                type="text"
                                class="form-control"
                                :class="{
                                    'is-invalid': $page.props.errors.passphrase,
                                }"
                                v-model="form.passphrase"
                            />
                            <span
                                v-if="$page.props.errors.passphrase"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.props.errors.passphrase }}
                                </strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <inertia-link
                    :href="route('admin.implicit-suts.index')"
                    class="btn btn-link"
                >
                    {{ $t('buttons.cancel') }}
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    {{ $t('buttons.create') }}
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import { serialize } from '@/utilities/object-to-formdata';
import Layout from '@/layouts/main';

export default {
    components: {
        Layout,
    },
    props: {
        implicitSut: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            form: {
                _method: 'PUT',
                slug: this.implicitSut.slug,
                version: this.implicitSut.version,
                url: this.implicitSut.url,
                use_encryption: this.implicitSut.use_encryption,
                ca_crt: null,
                client_crt: null,
                client_key: null,
                passphrase: this.implicitSut.certificate?.passphrase,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('admin.implicit-suts.update', this.implicitSut.id),
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
    },
};
</script>
<i18n src="@locales/pages/admin/implicit-suts/edit.json"></i18n>
