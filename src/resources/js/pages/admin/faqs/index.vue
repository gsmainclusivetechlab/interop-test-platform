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
                    <inertia-link
                        :href="route('admin.faqs.import')"
                        class="btn btn-primary ml-2"
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
                                {{ $t('table.header.description') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.updated-at') }}
                            </th>
                            <th class="text-nowrap text-center w-1">
                                {{ $t('table.header.active') }}
                            </th>
                            <th class="text-nowrap text-center w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(faq, i) in faqs.data" :key="i">
                            <td class="text-break">
                                {{faq.description}}
                            </td>
                            <td class="text-center text-break">
                                {{faq.updated_at}}
                            </td>
                            <td class="text-center text-break">
                                <label class="form-check form-switch">
                                    <input
                                        v-if="faq.can.toggleActive"
                                        class="form-check-input"
                                        type="checkbox"
                                        :checked="faq.active"
                                        @change.prevent="
                                            $inertia.put(
                                                route(
                                                    'admin.faqs.toggle-active',
                                                    faq.id
                                                )
                                            )
                                        "
                                    />
                                    <input
                                        v-else
                                        class="form-check-input"
                                        type="checkbox"
                                        disabled
                                        :checked="faq.active"
                                    />
                                </label>
                            </td>
                            <td class="text-center">
                                <b-dropdown
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
                                        <a
                                            :href="
                                                route(
                                                    'admin.faqs.export',
                                                    faq.id
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            {{ $t('table.menu.export') }}
                                        </a>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!faqs.data.length">
                            <td class="text-center" colspan="8">
                                {{ $t('table.content.no-results') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="faqs.meta"
                :links="faqs.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo() {
        return {
            title: this.$t('page.title'),
        };
    },
    components: {
        Layout,
    },
    props: {
        faqs: {
            type: Object,
            required: true,
        },
    },
};
</script>
<i18n src="@locales/pages/admin/faqs/index.json"></i18n>
