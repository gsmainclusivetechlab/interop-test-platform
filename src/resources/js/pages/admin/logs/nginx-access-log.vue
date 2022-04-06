<template>
    <layout>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>NGINX Access Log</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap w-15">Date and time</th>
                            <th class="text-nowrap w-auto">IP Address</th>
                            <th class="text-nowrap w-15">Request</th>
                            <th class="text-nowrap w-15">Response Code</th>
                            <th class="text-nowrap w-15">Bytes Transerred</th>
                            <th class="text-nowrap w-15">User Agent</th>
                            <th class="text-nowrap w-15">Url</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(entry, i) in logItems.data"
                            :key="`message-${i}`"
                        >
                            <td>
                                {{ entry.date }}
                            </td>
                            <td>
                                {{ entry.ip_address }}
                            </td>
                            <td>
                                {{ entry.request }}
                            </td>
                            <td>
                                {{ entry.response_code }}
                            </td>
                            <td>
                                {{ entry.bytes }}
                            </td>
                            <td>
                                {{ entry.user_agent }}
                            </td>
                            <td>
                                {{ entry.url }}
                            </td>
                        </tr>
                        <tr v-if="!Object.keys(logItems.data).length">
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

    export default {
        components: {
            Layout,
        },
        props: {
            logItems: {
                type: Object,
                required: true,
            },
        },
    };
</script>
