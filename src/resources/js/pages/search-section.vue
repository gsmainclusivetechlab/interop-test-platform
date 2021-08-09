<template>
    <div class="search-section section--p-gap has-bg">
        <div class="container container--thin">
            <div class="section-intro">
                <h1 class="hero-section__title text-center">{{ title }}</h1>
                <div class="hero-section__description text-center">{{ description }}</div>
            </div>
            <div class="search-input-holder">
                <input
                    type="text"
                    class="input input--search"
                    v-model="filterInput"
                    @input="handleSearch"
                    placeholder="Search"
                />
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'search-section',
    props: [
      'title',
      'description',
    ],
    data() {
      return {
        filterInput: '',
      }
    },
    methods: {
      handleSearch() {
        this.$emit('on-search', this.filterInput);
      }
    }
  }
</script>
