<template>
<v-app>
  <card :title="$t('home')" style="margin-top:20px">
    {{ $t('you_are_logged_in') }}
  </card>
</v-app>
</template>

<script>
export default {
  data: () => ({
    items: ['Foo', 'Bar', 'Fizz', 'Buzz'],
  }),
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>
