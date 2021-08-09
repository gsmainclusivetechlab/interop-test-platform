<template>
    <layout>
        <div class="faq">
            <search-section
                @on-search="applyFilter"
                :title="title"
                :description="description"
            />

            <div class="search-results-section">
                <div class="container container--narrow">
                    <template v-if="Object.keys(filteredFaqData).length">
                        <div class="faq-results__item" v-for="(item, key) in filteredFaqData" :key="`category-${key}`">
                            <h2 class="h2 faq-results__item-title">{{ key !== 'undefined' ? key : '' }}</h2>
                            <accordion>
                                <accordion-item v-for="(categoryItem, index) in item" :key="`category-item-${index}`">
                                    <template #header>
                                        {{ categoryItem.title }}
                                    </template>

                                    <template #body>
                                        <div v-html="categoryItem.text"></div>
                                    </template>
                                </accordion-item>
                            </accordion>
                        </div>
                    </template>
                    <div class="card" v-else>
                        <div class="row justify-content-md-around">
                            <div class="w-100"><br /></div>
                            <div class="col-3 tutorial-cards">
                                <h4
                                    class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center"
                                >
                                    {{ $t('tutorials.page.noTutorials') }}No results found.
                                </h4>
                            </div>
                            <div class="w-100"><br /></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import accordion from '@/pages/accordion';
import accordionItem from '@/pages/accordion-item';
import searchSection from '@/pages/search-section';

export default {
    name: 'faq-page',
    components: {
        accordion,
        accordionItem,
        searchSection,
        Layout,
    },
    props: {
        faqData: {
            type: Array,
            required: true,
        },
        title: {
            type: string,
            required: false,
        },
        description: {
            type: string,
            required: false,
        },
    },

    data() {
        return {
            faqDataSource: null,
            filteredFaqData: {},
        }
    },

    created() {
        this.faqDataSource = this.faqData;
        this.applyFilter('');
    },

    methods: {
        applyFilter(searchebleValue) {
            this.filteredFaqData = {};

            this.faqDataSource.forEach(item => {
                item.items.forEach(el => {
                    if (
                        el.title.toLowerCase().includes(searchebleValue.toLowerCase()) ||
                        el.text.toLowerCase().includes(searchebleValue.toLowerCase())
                    ) {
                        if (!this.filteredFaqData[item.title]) {
                            this.filteredFaqData[item.title] = [];
                        }

                        this.filteredFaqData[item.title].push(el);
                    }
                });
            });
            console.log(this.filteredFaqData)
        }
    }
}
</script>
