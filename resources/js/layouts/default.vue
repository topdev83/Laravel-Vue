<template>
<v-app>
  <navbar />
  <v-col sm="12" md="12" lg="12" xl="12">
    <child />
  </v-col>
</v-app>
</template>

<script>
import Navbar from '~/components/Navbar'

export default {
  name: 'MainLayout',

  components: {
    Navbar
  }
}
</script>
