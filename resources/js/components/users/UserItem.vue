<template>
  <v-col sm="12" md="12" lg="12" xl="12" style="padding-top:0px; padding-bottom:0px">
    <v-row v-if="!deleted">
      <v-col sm="1" md="1" lg="1" xl="1" style="display: flex;align-items: center;">
        <v-checkbox v-model="checkbox"></v-checkbox>
      </v-col>
      <v-col sm="1" md="1" lg="1" xl="1" style="display: flex;align-items: center;">
        <img width="40" height="40" :src="user.photo_url" style="border-radius: 50%">
      </v-col>
      <v-col sm="2" md="2" lg="2" xl="2" v-text="user.name" style="display: flex; align-items:center"/>
      <v-col sm="3" md="3" lg="3" xl="3" v-text="user.email" style="display: flex; align-items:center"/>
      <v-col sm="2" md="2" lg="2" xl="2" v-text="user.username" style="display: flex; align-items:center"/>
      <v-col sm="2" md="2" lg="2" xl="2" v-text="createdAt" style="display: flex; align-items:center"/>
      <v-col sm="1" md="1" lg="1" xl="1" style="text-align: right;display: flex; align-items:center">
        <a href="#" @click="edit"><font-awesome-icon :icon="['fas','edit']" style="color: #81D4FA;font-size: 20px"/></a>
      </v-col>
    </v-row>
  </v-col>
</template>
<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import eventBus from '../../plugins/eventBus'
import moment from 'moment'

export default {
  name: 'UserItem',
  components: {
    FontAwesomeIcon
  },
  props: {
    user: {
      required: true,
      type: Object
    }
  },
  data () {
    return {
      deleted: false,
      checkbox: false
    }
  },
  computed: {
    createdAt () {
      return moment(this.user.created_at).format('HH:mm DD/MM/YYYY')
    }
  },
  methods: {
    edit () {
      eventBus.$emit('edit-user', { id: this.user.id })
    },
    async deleteUser () {
      let confirmed = await confirm('Are you sure want to delete user ' + this.user.email)
      if (confirmed) {
        await this.$store.dispatch('users/deleteUser', { id: this.user.id })
        this.deleted = true
      }
    }
  }
}
</script>

<style scoped>
.checkbox{
  width: 25px;
}
</style>
