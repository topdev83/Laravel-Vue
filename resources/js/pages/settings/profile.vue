<template>
  <card :title="$t('your_info')">
    <v-form @submit.prevent="update">
      <v-alert v-model="alert" dismissible color="#4caf50">{{$t('info_updated')}}</v-alert>
      <v-row style="justify-content: center">
        <v-col sm="7" md="7" lg="7" xl="7">
          <!-- Name -->
          <!-- <input v-model="form.companyname" :class="{ 'is-invalid': form.errors.has('companyname') }" class="form-control" type="text" name="companyname">
          <has-error :form="form" field="companyname" /> -->
          <v-row>
            <v-col sm="2" md="2" lg="2" xl="2" class="text-right">
              <label>Company Name</label>
            </v-col>
            <v-col sm="10" md="10" lg="10" xl="10">
              <v-text-field single-line outlined v-model="form.companyname" name="companyname"></v-text-field>
            </v-col>
          </v-row>
          <!-- Email -->
          <!-- <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
          <has-error :form="form" field="email" /> -->
          <v-row>
            <v-col sm="2" md="2" lg="2" xl="2" class="text-right">
              <label>{{$t('email')}}</label>
            </v-col>
            <v-col sm="10" md="10" lg="10" xl="10">
              <v-text-field single-line outlined v-model="form.email" name="email"></v-text-field>
              <!-- Submit Button -->
              <v-button :loading="form.busy" class="primary-btn" style="margin-top:20px">
                {{ $t('update') }}
              </v-button>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-form>
  </card>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      companyname: '',
      email: ''
    }),
    alert: false
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  created () {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/api/settings/profile')
      this.$store.dispatch('auth/updateUser', { user: data })
      this.alert = true
    }
  }
}
</script>
