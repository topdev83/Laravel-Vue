<template>
  <card :title="$t('your_password')">
    <v-form>
      <v-alert v-model="alert" dismissible color="#4caf50">{{$t('password_updated')}}</v-alert>
      <v-row style="justify-content: center">
        <v-col sm="7" md="7" lg="7" xl="7">
          <!-- New password -->
          <v-row>
            <v-col sm="3" md="3" lg="3" xl="3" class="text-right">
              <label>{{$t('new_password')}}</label>
            </v-col>
            <v-col sm="9" md="9" lg="9" xl="9">
              <v-text-field
                v-model="form.password"
                type="password"
                name="password"
                single-line
                outlined
              ></v-text-field>
            </v-col>
          </v-row>
          <!-- Password Confirmation -->
          <v-row>
            <v-col sm="3" md="3" lg="3" xl="3" class="text-right">
              <label>{{$t('confirm_password')}}</label>
            </v-col>
            <v-col sm="9" md="9" lg="9" xl="9">
              <v-text-field
                v-model="form.password_confirmation"
                type="password"
                name="password_confirmation"
                single-line
                outlined
              ></v-text-field>
              <!-- Submit Button -->
              <v-btn :loading="form.busy" class="primary-btn" style="margin-top:20px" @click="update">
                {{ $t('update') }}
              </v-btn>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-form>
  </card>
</template>

<script>
import Form from 'vform'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: ''
    }),
    alert: false
  }),

  methods: {
    async update () {
      await this.form.patch('/api/settings/password')
      this.alert = true
      this.form.reset()
    }
  }
}
</script>
