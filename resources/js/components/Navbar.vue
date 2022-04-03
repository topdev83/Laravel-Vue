<template>
  <v-app-bar v-if="user" height="42px" style="background-color:#E91E63;border-radius:20px; margin: 4px; font-weight: 400;padding-left:10px">
    <router-link :to="{ name: user ? 'home' : 'welcome' }">
      <img src="/plany_fav_2.png" width="30px">
    </router-link>
    <v-btn style="margin-left: 15px;height: 30px;border: 1px solid white;width: 100px;border-radius: 10px;font-size:16px">New <fa icon="plus" fixed-width /></v-btn>
    <router-link :to="{name:'users.index'}" class="top-bar-icon">
      <fa icon="list-ul" class="top-bar"/>
      <div>Tasks</div>
    </router-link>
    <router-link :to="{name:'users.index'}" class="top-bar-icon">
      <fa icon="columns" class="top-bar"/>
      <div>Kanban</div>
    </router-link>
    <router-link :to="{name:'users.index'}" class="top-bar-icon">
      <fa icon="archive" class="top-bar"/>
      <div>Archive</div>
    </router-link>
    <router-link :to="{name:'users.index'}" class="top-bar-icon">
      <fa icon="archive" class="top-bar" />
      <div>Users</div>
    </router-link>
    <v-spacer></v-spacer>
    <router-link :to="{name:'users.index'}" class="top-bar-icon">
      <fa icon="cubes" class="top-bar"/>
      <div>Products</div>
    </router-link>
    <router-link :to="{name:'users.index'}" class="top-bar-icon">
      <fa icon="user" class="top-bar" />
      <div>Contacts</div>
    </router-link>
    <router-link :to="{name:'workstations.index'}" class="top-bar-icon">
      <fa icon="hammer" class="top-bar" />
      <div>Stations</div>
    </router-link>
    <!-- Authenticated -->
    <v-menu v-if="user" offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-btn text v-bind="attrs" v-on="on" style="color:white;font-size:16px">
          {{ loginDisplayName }}
          <img class="profile-photo" :src="user.photo_url" width="30px"/>{{ user.companyname }}
        </v-btn>
      </template>
      <v-list>
        <v-list-item>
          <router-link :to="{ name: 'settings.profile' }" class="dropdown-item">
            <fa icon="cog" fixed-width />
            {{ $t('settings') }}
          </router-link>
        </v-list-item>
        <v-list-item>
          <router-link :to="{ name: 'users.index'}" class="dropdown-item" >
            <fa icon="user" fixed-width /> Profile Settings
          </router-link>
        </v-list-item>
        <v-list-item>
          <router-link :to="{ name: 'users.index'}" class="dropdown-item" >
            <fa icon="users" fixed-width /> Users
          </router-link>
          <div class="dropdown-divider" />
        </v-list-item>
        <v-list-item>
          <router-link :to="{ name: 'settings.profile' }" class="dropdown-item">
            <fa icon="cog" fixed-width /> {{ $t('settings') }}
          </router-link>
        </v-list-item>
        <v-list-item>
          <router-link :to="{ name: 'settings.profile' }" class="dropdown-item"><fa icon="file" fixed-width /> Library</router-link>
        </v-list-item>
        <v-list-item>
          <router-link :to="{ name: 'settings.profile' }" class="dropdown-item"><fa icon="tag" fixed-width /> Tags</router-link>
          <div class="dropdown-divider" />
        </v-list-item>
        <v-list-item>
          <v-list-item-title>
            <router-link :to="{ name: 'users.index'}" class="dropdown-item" >
              <fa icon="credit-card" fixed-width /> Billing
              <div style=" font-style:italic;">Expiration 23/12/2025</div>
            </router-link>
          </v-list-item-title>
          <div class="dropdown-divider" />
        </v-list-item>
        <v-list-item>
          <router-link :to="{ name: 'users.index'}" class="dropdown-item" ><fa icon="question-circle" fixed-width /> Knowledge base</router-link>
        </v-list-item>
        <v-list-item>
          <a href="#" class="dropdown-item " @click.prevent="logout">
            <fa icon="sign-out-alt" fixed-width />
            {{ $t('logout') }}
          </a>
        </v-list-item>
      </v-list>
    </v-menu>
  </v-app-bar>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  components: {
  },

  data: () => ({
    appName: window.config.appName,
  }),

  computed: {
    isTenant () {
      return window.config.isTenant
    },
    loginDisplayName () {
      return this.user.username
    },
    ...mapGetters({
      user: 'auth/user'
    })
  },

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style scoped>

.top-bar-icon {
  position: relative;
  margin-left: 30px;
}
.top-bar-icon div {
  position: absolute;
  margin-top: 10px;
  font-size: 12px;
  color: black;
}
.top-bar {
  font-size: 1.8rem;
  color:white;
  cursor: pointer;
}
.dropdown-item {
  color: black;
}
</style>
