<template>
<v-app>
  <v-row class="user-list">
    <v-col sm="12" md="12" lg="12" xl="12">
      <v-row>
        <v-col sm="9" md="9" lg="9" xl="9">
          <h4>({{user_num}}) Users</h4>
        </v-col>
        <v-col sm="3" md="3" lg="3" xl="3" class="text-right">
          <v-btn @click="newUser" style="border-radius:20px;min-width:100px">New <FontAwesomeIcon icon="plus" style="margin-left:10px"></FontAwesomeIcon></v-btn>
        </v-col>
      </v-row>
      <v-card>
        <v-card-text class="card-body">
          <template v-for="(user,i) in users">
            <user-item :key="'user-item-'+i" :user="user" />
            <hr class="separator" v-if="i< users.length -1" :key="'user-hr-'+i"/>
          </template>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
  <v-row style="padding:10px">
    <v-col sm="12" md="12" lg="12" xl="12">
      <TerminalTable />
    </v-col>
  </v-row>
</v-app>
</template>

<script>
import UserItem from '../../components/users/UserItem'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import eventBus from '../../plugins/eventBus'
import TerminalTable from '../../components/TerminalTable'

export default {
  name: 'List',
  components: {
    UserItem,
    FontAwesomeIcon,
    TerminalTable
  },
  data () {
    return {
      drawer: false,
      user_num: null
    }
  },
  computed: {
    users () {
      this.user_num = this.$store.state.users.users.length
      return this.$store.state.users.users
    }
  },
  created () {
    this.$store.dispatch('users/fetchUsers')
    
  },
  methods: {
    newUser () {
      eventBus.$emit('create-user')
      console.log("new user method")
    }
  }
}
</script>

<style scoped>
.user-list{
  padding:10px;
}
.btn-rounded-border{
  padding: 3px 12px;
 }
.separator{
  margin: 5px 0;
}
</style>
