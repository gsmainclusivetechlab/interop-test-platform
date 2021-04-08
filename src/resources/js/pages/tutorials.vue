<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h2 class="page-title">
                        <b>{{ $t('tutorials.page.title') }}</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card" v-if="tutorials">
            <div class="row justify-content-around">
                <div class="w-100"><br /></div>
                <template v-for="(item, index) of tutorials">
                    <div class="col-md-3 col-sm-10" v-bind:key="index">
                        <div class="tutorial-cards pt-3">
                            <a
                                class="d-inline-block btn scenario-card"
                                :href="'#' + item.id"
                                v-b-toggle="'accordion-' + item.id"
                            >
                                <h4
                                    class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center border-bottom border-primary"
                                >
                                    <img :src="item.icon" class="icon" />
                                    {{ item.tile.title }}
                                </h4>
                                <div class="pl-2 font-weight-normal">
                                    {{ item.tile.comment }}
                                </div>
                            </a>
                        </div>
                    </div>
                    <div
                        v-bind:key="index"
                        v-if="(index + 1) % 3 === 0"
                        class="w-100"
                    >
                        <br />
                    </div>
                </template>
            </div>
            <div role="tablist" class="tutorial-accordion px-4">
                <template v-for="(item, index) of tutorials">
                    <article class="mb-3 card" v-bind:key="index">
                        <header
                            :id="item.id"
                            class="pl-0 card-header"
                            role="tab"
                        >
                            <button
                                v-b-toggle="'accordion-' + item.id"
                                class="btn shadow-none"
                                type="button"
                            >
                                <h3
                                    class="mb-0 scenario-accordion text-primary"
                                >
                                    {{ item.accordion.title }}
                                </h3>
                            </button>
                        </header>
                        <b-collapse
                            :id="'accordion-' + item.id"
                            accordion="tutorial-accordion"
                            role="tabpanel"
                        >
                            <div class="card-body">
                                <div v-html="item.accordion.htmlContent"></div>
                                <tutorial-demo
                                    v-if="
                                        item.accordion.tutorialData &&
                                        item.accordion.tutorialData.length > 0
                                    "
                                    :demo-data="item.accordion.tutorialData"
                                />
                            </div>
                        </b-collapse>
                    </article>
                </template>
            </div>
        </div>
        <div class="card" v-else>
            <div class="row justify-content-md-around">
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
import TutorialDemo from '@/components/tutorial-demo';

export default {
    metaInfo() {
        return {
            title: this.$t('tutorials.page.title'),
        };
    },
    components: {
        Layout,
        TutorialDemo,
    },
    data() {
        return {
            tutorials: null,
        };
    },
    mounted() {
        const locale = this.$i18n.locale;
        axios.get(`/assets/tutorial/${locale}.json`).then((res) => {
            this.tutorials = res.data;
            if (location.hash) {
            }
        });
    },
};
</script>
