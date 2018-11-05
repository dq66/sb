<template>
<div>
    <v-breadcrumbs divider="/">
      <v-breadcrumbs-item
        v-for="item in items"
        :key="item.text"
        :disabled="item.disabled"
      >
        {{ item.text }}
      </v-breadcrumbs-item><br>
    </v-breadcrumbs>
  </div>
</template>

<script>
export default {
  data: () => ({
    items: [
      {
        text: "控制台",
        disabled: false
      },
      {
        text: "概要",
        disabled: false
      }
    ]
  })
};
</script>

<style>
</style>
