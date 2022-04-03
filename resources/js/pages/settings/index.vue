<template>
  <v-row>
    <v-col sm="3" md="3" lg="3" xl="3">
      <!-- <card :title="$t('settings')">
        <ul class="nav flex-column nav-pills">
          <li v-for="tab in tabs" :key="tab.route" class="nav-item">
            <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
              <fa :icon="tab.icon" fixed-width />
              {{ tab.name }}
            </router-link>
          </li>
        </ul>
      </card> -->
      <v-card height="auto" width="100%" class="mx-auto">
        <v-navigation-drawer permanent width="100%">
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title class="title">
                {{$t('settings')}}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
          <v-list dense nav>
            <v-list-item-group color="#2196F3" v-model="tab">
              <div v-for="tab in tabs" :key="tab.route">
                <router-link :to="{ name: tab.route }">
                  <v-list-item>
                    <fa :icon="tab.icon" fixed-width />{{ tab.name }}
                  </v-list-item>
                </router-link>
              </div>
            </v-list-item-group>
          </v-list>
        </v-navigation-drawer>
      </v-card>
    </v-col>

    <v-col sm="9" md="9" lg="9" xl="9">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </v-col>
  </v-row>
</template>

<script>
export default {
  middleware: 'auth',

  data: ()=>({
    tab: 0
  }),

  computed: {
    tabs () {
      return [
        {
          icon: 'user',
          name: this.$t('profile'),
          route: 'settings.profile'
        },
        {
          icon: 'lock',
          name: this.$t('password'),
          route: 'settings.password'
        }
      ]
    }
  }
}
</script>

<style>

</style>
