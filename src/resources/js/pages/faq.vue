<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h2 class="page-title">
                        <b>{{ $t('faq.page.title') }}</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="col-sm-4">
                    <input-search v-model="filterInput" @input="handleSearch" />
                </div>
            </div>
            <div v-if="Object.keys(filteredFaqData).length">
                <div v-for="(items, key) in filteredFaqData" :key="key">
                    <div role="tablist" class="faq-accordion px-4">
                        <div class="card-header h3">
                            {{ key !== 'undefined' ? key : '' }}
                        </div>
                        <template v-for="(item, index) of items">
                            <article
                                class="mb-3 card"
                                v-bind:key="index"
                                :key="index"
                            >
                                <header
                                    :id="key + index"
                                    class="pl-0 card-header"
                                    role="tab"
                                    type="button"
                                    v-b-toggle="'accordion-' + key + index"
                                >
                                    <button class="btn shadow-none">
                                        <h3 class="mb-0 text-primary">
                                            {{ item.title }}
                                        </h3>
                                    </button>
                                </header>
                                <b-collapse
                                    :id="'accordion-' + key + index"
                                    accordion="faq-accordion"
                                    role="tabpanel"
                                >
                                    <div class="card-body">
                                        <div v-html="item.text"></div>
                                    </div>
                                </b-collapse>
                            </article>
                        </template>
                    </div>
                </div>
            </div>
            <div class="row justify-content-md-around" v-else>
                <div class="w-100"><br /></div>
                <div class="col-3 tutorial-cards">
                    <h4
                        class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center"
                    >
                        {{ $t('tutorials.page.noTutorials') }}
                    </h4>
                </div>
                <div class="w-100"><br /></div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo() {
        return {
            title: this.$t('faq.page.title'),
        };
    },
    components: {
        Layout,
    },
    props: {
        faqData: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            faqDataSource: null,
            filteredFaqData: {},
            filterInput: '',
        };
    },

    created() {
        this.faqDataSource = this.faqData;
        this.applyFilter('');
    },

    methods: {
        applyFilter(searchebleValue) {
            this.filteredFaqData = {};

            this.faqDataSource.forEach((item) => {
                item.items.forEach((el) => {
                    if (
                        el.title
                            .toLowerCase()
                            .includes(searchebleValue.toLowerCase()) ||
                        el.text
                            .toLowerCase()
                            .includes(searchebleValue.toLowerCase())
                    ) {
                        if (!this.filteredFaqData[item.title]) {
                            this.filteredFaqData[item.title] = [];
                        }

                        this.filteredFaqData[item.title].push(el);
                    }
                });
            });
        },
        handleSearch() {
            this.applyFilter(this.filterInput);
        },
    },
};
</script>
