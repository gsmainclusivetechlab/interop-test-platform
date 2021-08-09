<template>
    <div class="accordion__item" :class="{'accordion__item--active': isOpened}">
        <div class="accordion__item-wrapper">
            <div class="accordion__item-header" @click="openClose">
                <div class="title">
                    <slot name="header"></slot>
                </div>
                <svg class="arrow-icon" width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.42413 10.0384L8.19336 3.26916L14.9626 10.0384L16.1934 8.80762L9.42413 2.0384L8.19336 0.807629L6.96259 2.0384L0.193366 8.80762L1.42413 10.0384Z" fill="#DE002B"/>
                </svg>
            </div>
            <div class="accordion__item-body" :style="{'height': isOpened ? itemElementHeig : '0px'}">
                <div class="accordion__item-body-content">
                    <slot name="body"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'accordion-item',
    props: [
      'expanded'
    ],
    data() {
      return {
        isOpened: false,
        itemElement: null,
        itemElementHeig: 'auto',
        timeout: null,
      }
    },
    created() {
      this.isOpened = !!this.expanded;
    },
    mounted() {
      this.itemElement = this.$el.getElementsByClassName("accordion__item-body-content")[0];
      this.detectItemHeigth();
      window.addEventListener('resize', () => {
        if (this.timeout) {
          clearTimeout(this.timeout);
        }

        this.timeout = setTimeout(()=>{
          this.detectItemHeigth();
        }, 20);
      });
    },
    methods: {
      openClose() {
        this.detectItemHeigth();
        this.isOpened = !this.isOpened;
      },
      detectItemHeigth() {
        this.itemElementHeig = 'auto';
        this.itemElementHeig = `${this.itemElement.offsetHeight}px`;
      }
    }
  }
</script>
