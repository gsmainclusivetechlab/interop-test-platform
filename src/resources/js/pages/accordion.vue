<template>
    <div class="accordion">
        <div class="accordion-wrapper">
            <slot></slot>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'accordion',
    data: function() {
      return {
        isOpened: false,
      }
    },
  }
</script>
