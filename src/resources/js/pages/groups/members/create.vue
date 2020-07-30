<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ `Invite new member to ${group.name}` }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    User
                                </label>
                                <selectize
                                    v-model="form.user_id"
                                    class="form-select"
                                    placeholder="Select member..."
                                    :class="{
                                        'is-invalid': $page.errors.user_id,
                                    }"
                                    :load="loadUsersItems"
                                    :render="renderUsersItems"
                                    :preload="true"
                                    valueField="id"
                                    labelField="name"
                                    :searchField="['name', 'email']"
                                 />
                                <span
                                    v-if="$page.errors.user_id"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.user_id }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-check">
                                    <input
                                        type="checkbox"
                                        v-model="form.admin"
                                        class="form-check-input"
                                    />
                                    <span class="form-check-label">Make administrator</span>
                                </label>
                                <span
                                    v-if="$page.errors.admin"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.admin }}
                                    </strong>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('groups.show', group.id)"
                                class="btn btn-link"
                            >
                                Cancel
                            </inertia-link>
                            <button type="submit" class="btn btn-primary">
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                Invite
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import Selectize from '@/components/selectize';

export default {
    metaInfo() {
        return {
            title: `Invite new member to ${this.group.name}`,
        };
    },
    components: {
        Layout,
        Selectize,
    },
    props: {
        group: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            form: {
                user_id: null,
                admin: false,
            },
            renderUsersItems: {
                option: function(item) {
                    return '<div class="option">' +
                        '<div>' + item.name + '</div>' +
                        '<div class="text-muted small">' + item.email + '</div>' +
                        '</div>';
                }
            }
        };
    },
    mounted() {

    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('groups.members.store', this.group), this.form)
                .then(() => (this.sending = false));
        },
        loadUsersItems(query, callback) {
            axios.get(route('groups.members.candidates', this.group), {params: {q: query}}).then((result) => {
                callback(result.data.data);
            });
        },
    },
};
</script>
