<template>
<v-app>
  <div class="row">
    <div class="col-8">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </div>
  </div>
  <div>
    <v-navigation-drawer 
      v-model="drawer"
      right
      width="500px"
      fixed
      temporary
    >
      <userForm v-on:childToParent="CloseDrawer()"/>
    </v-navigation-drawer>
  </div>
</v-app>
</template>

<script>
import userForm from './user-form'
import eventBus from '../../plugins/eventBus'
import UserList from './list'

export default {
  middleware: 'auth',
  components: {
    userForm,
    UserList,
  },
  data () {
    return {
      drawer: false,
    }
  },
  computed: {
  },
  created() {
  },
  async mounted() {
    eventBus.$on('create-user', async () => {
      this.drawer = !this.drawer
    })
    eventBus.$on('edit-user', async (id) => {
      this.drawer = !this.drawer
    })
  },
  methods: {
    CloseDrawer () {
      if(!this.drawer) {
        this.drawer = false
      }
      console.log("this.drawer=", this.drawer)
    },
  }
}
</script>

<style>
.mt-6{
  margin-top: 4rem;
}
</style>
