<template>
  <div class="col">
    <login-with-google v-show="false" v-if="provider==='google'" :domain="domain" :redirect="true"/>
  </div>
</template>

<script>
import LoginWithGoogle from '../../components/LoginWithGoogle'
import eventBus from '../../plugins/eventBus'

export default {
  middleware: 'guest',
  name: 'Oauth',
  components: {
    LoginWithGoogle
  },
  props: {
    domain: {
      type: String,
      required: true
    },
    provider: {
      type: String,
      required: true
    }
  },
  mounted () {
    eventBus.$emit('loginWithGoogle')
  }
}
</script>

<style scoped>

</style>
