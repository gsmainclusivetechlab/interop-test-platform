<template>
    <layout :components="components" :session="session">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">SUT selection</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">SUTs</label>
                        <v-select
                            v-model="currentSuts.selected"
                            :options="currentSuts.list"
                            label="name"
                            multiple
                            placeholder="Select SUT..."
                            :selectable="
                                (option) =>
                                    isSelectable(option, currentSuts.selected)
                            "
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.component_ids,
                            }"
                            :disabled="selectedSut"
                            @input="setComponents"
                        />
                        <span
                            v-if="$page.props.errors.component_ids"
                            class="invalid-feedback"
                        >
                            {{ $page.props.errors.component_ids }}
                        </span>
                    </div>
                    <template v-for="(sut, i) in suts.data">
                        <div
                            v-if="form.component_ids.includes(sut.id)"
                            class="mb-3"
                            :key="i"
                        >
                            <label class="form-label">{{ sut.name }} URL</label>
                            <input
                                v-model="form.base_urls[sut.id]"
                                :class="{
                                    'is-invalid':
                                        $page.props.errors[
                                            `base_urls.${sut.id}`
                                        ],
                                }"
                                class="form-control"
                            />
                            <span
                                v-if="$page.props.errors[`base_urls.${sut.id}`]"
                                class="invalid-feedback"
                            >
                                {{ $page.props.errors[`base_urls.${sut.id}`] }}
                            </span>
                        </div>
                    </template>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    v-if="
                        $page.props.available_session_modes_count > 1 ||
                        isCompliance ||
                        session.withQuestions
                    "
                    :href="
                        route(
                            isCompliance || session.withQuestions
                                ? 'sessions.register.questionnaire.summary'
                                : 'sessions.register.type'
                        )
                    "
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <button type="submit" class="btn btn-primary ml-auto">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Next
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/register';
import { isSelectable } from '@/components/v-select';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: false,
        },
        suts: {
            type: Object,
            required: true,
        },
        components: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            isCompliance: this.session.type === 'compliance',
            selectedSut: this.isCompliance && this.suts.data.length === 1,
            currentSuts: {
                list: this.suts.data,
                selected: this?.session?.sut?.component_ids
                    ? collect(this.suts.data)
                          .whereIn('id', this.session.sut.component_ids)
                          .all()
                    : this.selectedSut
                    ? [collect(this.suts.data).first()]
                    : [],
            },
            form: {
                base_urls: this?.session?.sut?.base_urls ?? [],
                component_ids: [],
            },
        };
    },
    methods: {
        isSelectable,
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('sessions.register.sut.store'),
                this.form,
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
        setComponents(items) {
            this.form.component_ids = items?.map((item) => item.id) ?? [];
        },
    },
};
</script>
