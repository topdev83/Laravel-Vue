<template>
  <div class="col" />
</template>

<script>
export default {
  name: 'Token',
  props: {
    token: {
      type: String
    }
  },
  mounted () {
    this.$store.dispatch('auth/saveToken', { token: this.token })
    this.$router.push({ name: 'home' })
  }
}
</script>

<style scoped>

</style>
