<template>
    <layout>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>NGINX Error Log</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap w-15">Date and time</th>
                            <th class="text-nowrap w-auto">Level</th>
                            <th class="text-nowrap w-15">Request</th>
                            <th class="text-nowrap w-15">Message</th>
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
                                <div
                                    class="badge badge-outline text-xl"
                                    v-bind:class="msgColor(entry.level)"
                                >
                                    {{ entry.level.toUpperCase() }}
                                </div>
                            </td>
                            <td class="text-break">
                                {{ entry.request }}
                            </td>
                            <td>
                                {{ entry.message }}
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
        methods: {
            msgColor(level) {
                switch (level) {
                    case 'info':
                    case 'debug':
                        return 'text-info';
                    case 'warn':
                    case 'notice':
                    case 'alert':
                        return 'text-warning';
                    default:
                        return 'text-danger';
                }
            },
        },
    };
</script>
