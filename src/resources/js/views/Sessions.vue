<template>
    <div>
        <h1 class="page-title mb-5">
            <b>Sessions</b>
        </h1>

        <b-card no-body>
            <template v-slot:header>
                <form action="#" method="GET" class="input-icon">
                    <input
                        type="text"
                        name="q"
                        value=""
                        placeholder="Search..."
                        class="form-control"
                    />
                    <span class="input-icon-addon"
                        ><i class="fe fe-search"></i
                    ></span>
                </form>
            </template>

            <b-table
                :fields="fields"
                :items="items"
                responsive
                striped
                hover
                head-variant="light"
                emptyText="No Results"
                show-empty
                class="card-table mb-0"
            ></b-table>
        </b-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            fields: [
                {
                    key: 'name',
                    class: 'text-nowrap w-25',
                },
                {
                    key: 'useCases',
                    class: 'text-nowrap w-auto',
                },
                {
                    key: 'testCases',
                    class: 'text-nowrap w-auto',
                },
                {
                    key: 'status',
                    class: 'text-nowrap w-25',
                },
                {
                    key: 'lastRun',
                    class: 'text-nowrap w-auto',
                },
                {
                    key: 'action',
                    class: 'text-nowrap w-1',
                    label: '',
                },
            ],
            items: [
                {
                    name: 'Name',
                    useCases: 'Use Cases',
                    testCases: 'Test Cases',
                    status: 'Status',
                    lastRun: 'Last Run',
                },
            ],
        };
    },
};
</script>
