<template>
  <v-card>
    <v-card-title v-if="title">
      {{ title }}
    </v-card-title>
    <v-divider></v-divider>
    <v-card-text>
      <slot />
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'Card',

  props: {
    title: { type: String, default: null }
  }
}
</script>
