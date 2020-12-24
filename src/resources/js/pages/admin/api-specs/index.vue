<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <b>Api Specs</b>
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
                        <icon name="upload" />
                        Import Api Spec
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="apiSpec in apiSpecs.data">
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
                                    <template v-slot:button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
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
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="`Are you sure you want to delete ${apiSpec.name}?`"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!apiSpecs.data.length">
                            <td class="text-center" colspan="2">No Results</td>
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
    metaInfo: {
        title: 'Specifications',
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
