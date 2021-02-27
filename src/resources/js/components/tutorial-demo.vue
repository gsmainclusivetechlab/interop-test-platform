<template>
    <div class="demo-container mt-3 mx-auto">
        <div class="demo-inner-container mb-4">
            <div class="slide-img-holder" ref="img">
                <button
                    type="button"
                    ref="target"
                    class="target-circle"
                    :hidden="!controls.target"
                    @click.prevent="nextSlide"
                ></button>
                <img
                    class="slide-img"
                    loading="lazy"
                    decoding="async"
                    :src="slideSrc"
                    :alt="slideText"
                />
            </div>
            <div class="bg-dark text-light text-center font-weight-bold p-2">
                {{ slideText }}
            </div>
            <div class="demo-overlay" :hidden="!overlay"></div>
            <button
                type="button"
                class="btn btn-primary start-demo-btn"
                :hidden="!controls.start"
                @click.prevent="startDemo"
            >
                {{ $t('buttons.start-demo') }}
            </button>
        </div>
        <button
            type="button"
            class="btn btn-outline-primary"
            @click.prevent="resetDemo"
        >
            {{ $t('buttons.reset-demo') }}
        </button>
    </div>
</template>
<script>
export default {
    name: 'TutorialDemo',
    props: {
        demoData: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            currentStep: 0,
            slideText: '',
            slideSrc: '',
            controls: {
                target: true,
                start: true,
            },
            overlay: true,
        };
    },
    methods: {
        setSlideHeight() {
            const arrRatio = this.demoData[this.currentStep].slideRatio.split(
                '/'
            );
            const ratio = arrRatio[1] / arrRatio[0];

            this.$refs.img.style.setProperty('--img-height-ratio', ratio);
        },
        mooveTarget() {
            const targetPosision = this.demoData[this.currentStep]
                .targetPosision;

            this.$refs.target.style.setProperty(
                '--translate-x',
                targetPosision.x
            );
            this.$refs.target.style.setProperty(
                '--translate-y',
                targetPosision.y
            );
        },
        updateSlide() {
            this.mooveTarget();

            this.slideText = this.demoData[this.currentStep].slideText;
            this.slideSrc = this.demoData[this.currentStep].slideSrc;

            this.setSlideHeight();
        },
        nextSlide() {
            this.currentStep += 1;

            if (this.currentStep === this.demoData.length - 1) {
                this.controls.target = false;
            }

            this.updateSlide();
        },
        startDemo() {
            this.controls.start = false;
            this.overlay = false;
        },
        resetDemo() {
            this.currentStep = 0;
            this.slideSrc = this.demoData[this.currentStep].slideSrc;

            this.updateSlide();

            this.controls.target = true;
            this.controls.start = true;
            this.overlay = true;
        },
    },
    mounted() {
        this.updateSlide();
    },
};
</script>
<i18n src="@locales/components/tutorial-demo.json"></i18n>
