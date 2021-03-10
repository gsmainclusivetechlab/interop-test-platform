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
                <form @submit.prevent="search">
                    <input-search v-model="form.q" />
                </form>
                <div class="card-options">
                    <inertia-link
                        :href="route('admin.api-specs.import')"
                        v-if="$page.props.auth.user.can.api_specs.create"
                        class="btn btn-primary"
                    >
                        <icon name="file-import" />
                        {{ $t('buttons.import') }}
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap">
                                {{ $t('table.header.name') }}
                            </th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(apiSpec, i) in apiSpecs.data"
                            :key="`spec-${i}`"
                        >
                            <td class="text-break">
                                {{ apiSpec.name }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="apiSpec.can.delete"
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template #button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li>
                                        <inertia-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.api-specs.edit',
                                                    apiSpec.id
                                                )
                                            "
                                        >
                                            {{ $t('table.menu.import.title') }}
                                        </inertia-link>
                                    </li>
                                    <li v-if="apiSpec.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.api-specs.destroy',
                                                    apiSpec.id
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
                                                    'table.menu.delete.modal.text',
                                                    { name: apiSpec.name }
                                                )
                                            "
                                        >
                                            {{ $t('table.menu.delete.title') }}
                                        </confirm-link>
                                    </li>
                                    <li v-if="apiSpec.file_path">
                                        <a
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.api-specs.download',
                                                    apiSpec.id
                                                )
                                            "
                                        >
                                            {{
                                                $t('table.menu.download.title')
                                            }}
                                        </a>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!apiSpecs.data.length">
                            <td class="text-center" colspan="2">
                                {{ $t('table.no-results') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="apiSpecs.meta"
                :links="apiSpecs.links"
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
        apiSpecs: {
            type: Object,
            required: true,
        },
        filter: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            form: {
                q: this.filter.q,
            },
        };
    },
    methods: {
        search() {
            this.$inertia.replace(route('admin.api-specs.index'), {
                data: this.form,
            });
        },
    },
};
</script>
<i18n src="@locales/pages/admin/api-spec/index.json"></i18n>
