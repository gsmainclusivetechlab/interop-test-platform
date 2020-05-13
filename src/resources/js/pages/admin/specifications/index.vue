<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">
                        Administration
                    </div>
                    <h2 class="page-title">
                        <b>Specifications</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form class="input-icon" @submit.prevent="search">
                    <input
                        v-model="form.q"
                        type="text"
                        class="form-control"
                        placeholder="Search..."
                    />
                    <span class="input-icon-addon">
                        <icon name="search" />
                    </span>
                </form>
                <div class="card-options">
                    <inertia-link
                        :href="route('admin.specifications.import')"
                        v-if="$page.auth.user.can.specifications.create"
                        class="btn btn-primary"
                    >
                        <icon name="upload" />
                        Import Specification
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-vcenter table-hover card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Version</th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="specification in specifications.data">
                            <td class="text-break">
                                {{ specification.name }}
                            </td>
                            <td class="text-break">
                                {{ specification.version }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="specification.can.delete"
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li v-if="specification.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.specifications.destroy',
                                                    specification.id
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="
                                                `Are you sure you want to delete ${specification.name}?`
                                            "
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!specifications.data.length">
                            <td class="text-center" colspan="3">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :meta="specifications.meta" :links="specifications.links" class="card-footer" />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/main';

    export default {
        metaInfo: {
            title: 'Specifications'
        },
        components: {
            Layout,
        },
        props: {
            specifications: {
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
                }
            };
        },
        methods: {
            search() {
                this.$inertia.replace(route('admin.specifications.index'), {
                    data: this.form
                });
            }
        }
    };
</script>
