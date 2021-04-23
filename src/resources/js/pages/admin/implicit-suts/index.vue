<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">
                        {{ $t('special-locales.administration') }}
                    </div>
                    <h2 class="page-title">
                        <b>{{ $t('page.title') }}</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-options">
                    <a
                        :href="route('sessions.certificates.download-csr')"
                        class="btn btn-primary mr-3"
                    >
                        Download CSR
                    </a>
                    <button
                        v-b-modal="`modal-download`"
                        class="btn btn-outline-primary mr-3"
                    >
                        Download certificates
                    </button>
                    <inertia-link
                        :href="route('admin.implicit-suts.create')"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        {{ $t('buttons.create') }}
                    </inertia-link>
                    <b-modal
                        :id="`modal-download`"
                        size="lg"
                        centered
                        hide-footer
                        title="Upload CSR of your SUT"
                        @hidden="resetModal"
                    >
                        <form @submit.prevent="submit">
                            <div class="card-body">
                                <div class="mb-1">
                                    <h4>
                                        It will be used by ITP to generate public certificate.
                                    </h4>
                                </div>
                                <div class="mb-3">
                                    <b-form-file
                                        v-model="form.file"
                                        :placeholder="'Choose file ...'"
                                        :browse-text="'Browse'"
                                        :class="{
                                                    'is-invalid': $page.props.errors.file,
                                                }"
                                    />
                                    <div
                                        v-if="$page.props.errors.file"
                                        class="invalid-feedback"
                                    >
                                        <p class="mb-1">
                                            <strong>
                                                Error with file - {{form.fileSrc}}
                                            </strong>
                                        </p>
                                        <p
                                            v-if="$page.props.errors.file"
                                            class="mb-1"
                                        >
                                            <strong>
                                                {{ $page.props.errors.file }}
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="!form.file"
                                >
                                    Submit CSR
                                </button>
                                <a
                                    @click="disableBtn"
                                    :class="{disabled: btnDisabled}"
                                    :href="
                                                route('sessions.certificates.download')
                                            "
                                    class="btn btn-primary"
                                >
                                    Download certificates
                                </a>
                            </div>
                        </form>
                    </b-modal>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap">
                                {{ $t('table.header.slug') }}
                            </th>
                            <th class="text-nowrap">
                                {{ $t('table.header.version') }}
                            </th>
                            <th class="text-nowrap">
                                {{ $t('table.header.url') }}
                            </th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(implicitSut, i) in implicitSuts.data"
                            :key="`implicit-sut-${i}`"
                        >
                            <td class="text-break">
                                {{ implicitSut.slug }}
                            </td>
                            <td>
                                {{ implicitSut.version }}
                            </td>
                            <td>
                                {{ implicitSut.url }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li>
                                        <inertia-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.implicit-suts.edit',
                                                    implicitSut.id
                                                )
                                            "
                                        >
                                            {{ $t('table.menu.edit') }}
                                        </inertia-link>
                                    </li>
                                    <li>
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.implicit-suts.destroy',
                                                    implicitSut.id
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="
                                                $t(
                                                    'table.menu.delete.modal.title'
                                                )
                                            "
                                            :confirm-text="
                                                $t(
                                                    'table.menu.delete.modal.title',
                                                    { name: implicitSut.slug }
                                                )
                                            "
                                        >
                                            {{ $t('table.menu.delete.title') }}
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!implicitSuts.data.length">
                            <td class="text-center" colspan="3">
                                {{ $t('table.no-results') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="implicitSuts.meta"
                :links="implicitSuts.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo() {
        return { title: this.$t('page.title') };
    },
    components: {
        Layout,
    },
    props: {
        implicitSuts: {
            type: Object,
            required: true,
        },
        data: {
            type: Object,
            required: false,
        },
    },
    data() {
        return {
        btnDisabled: true,
        form: {
            file: null,
            fileSrc: null,
        }
        };
    },
    methods: {
        submit() {
            const data = new FormData();
            this.$page.props.errors.file = null;

            data.append('file', this.form.file);
            this.$inertia.post(
                route('sessions.certificates.upload-csr'),
                data,
                {
                    onFinish: () => {
                        this.form.fileSrc = `${this.form.file.name}`;
                        this.form.file = null;
                        if (!this.$page.props.errors.file) {
                            this.btnDisabled = false;
                        }
                    },
                }
            );
        },
        resetModal() {
            this.form.file = null;
            this.form.fileSrc = null;
            this.$page.props.errors.file = null;
            this.btnDisabled = true;
        },
        disableBtn() {
            this.btnDisabled = true;
        },
    },
};
</script>
<i18n src="@locales/pages/admin/implicit-suts/index.json"></i18n>
