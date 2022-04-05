<template>
    <layout>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>Audit Log</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap w-20">User</th>
                            <th class="text-nowrap w-auto">Action</th>
                            <th class="text-nowrap w-15">Subject</th>
                            <th class="text-nowrap w-15">Meta</th>
                            <th class="text-nowrap w-15">Action Performed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(message, i) in logItems.data"
                            :key="`message-${i}`"
                        >
                            <td>
                                {{ message.user.name }}
                            </td>
                            <td>
                                {{ message.action }}
                            </td>
                            <td class="text-break">
                                <inertia-link
                                    v-if="message.type"
                                    :href="route(message.url, message.subject)"
                                >
                                    #{{ message.subject }}
                                </inertia-link>
                            </td>
                            <td>
                                <button
                                    class="btn btn-secondary"
                                    v-if="message.meta"
                                    v-b-modal="`modal-request-${message.id}`"
                                >
                                    Request
                                </button>
                                <b-modal
                                    :id="`modal-request-${message.id}`"
                                    size="lg"
                                    centered
                                    hide-footer
                                    title="Request"
                                >
                                    <div
                                        class="d-flex"
                                        v-if="collect(message.meta).count()"
                                    >
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Meta</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            <div class="mb-0 p-0">
                                                <vue-json-pretty
                                                    :deep="2"
                                                    :data="message.meta"
                                                    class="p-2"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </b-modal>
                            </td>
                            <td>
                                {{ message.created_at }}
                            </td>
                        </tr>

                        <tr v-if="!logItems.data.length">
                            <td class="text-center" colspan="5">No Results</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <pagination
                :meta="logItems.meta"
                :links="logItems.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/logs';
    import VueJsonPretty from 'vue-json-pretty';
    import 'vue-json-pretty/lib/styles.css';

    export default {
        components: {
            Layout,
            VueJsonPretty,
        },
        props: {
            logItems: {
                type: Object,
                required: true,
            },
        },
    };
</script>
