<template>
  <v-row justify="center">
    <v-col sm="8" md="8" lg="8" xl="8">
      <div class="myform">
        <div>
          <v-row justify="center">
            <div style="font-size: 60px">Welcome to</div>
          </v-row>
          <v-row justify="center">
            <div style="font-size: 30px">{{ domainName }}</div>
          </v-row>
        </div>
        <v-form>
          <div>
            <label for="email">Email address</label>
            <v-text-field single-line outlined v-model="form.email" name="email"></v-text-field>
          </div>
          <div>
            <label for="password">Password</label>
            <v-text-field
              v-model="form.password"
              type="password"
              name="password"
              single-line
              outlined
            ></v-text-field>
          </div>
          <!-- Remember Me -->
          <div>
            <v-checkbox v-model="remember" name="remember" :label="`${$t('remember_me')}`"></v-checkbox>
          </div>
          <v-btn :loading="form.busy" class="mainbtn" block @click="login">Login</v-btn>
        </v-form>
        <v-col sm="12" md="12" lg="12" xl="12">
          <div class="login-or">
              <hr class="hr-or">
              <span class="span-or">or</span>
          </div>
        </v-col>
        <div>
          <a @click="signInWithGoogle">
            <img src="https://img.icons8.com/color/16/000000/google-logo.png">
            Sign in with Google
          </a>
        </div>
        <div>
          <p class="text-left">Don't an have account? <a href="/register" id="signup">Sign up</a></p>
        </div>
      </div>
    </v-col>
  </v-row>
</template>

<style>
</style>

<script>
import Form from 'vform'
import LoginWithGithub from '~/components/LoginWithGithub'

export default {
  middleware: 'guest',

  components: {
  },

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: '',
    }),
    remember: false,
    showpass: false,
    rules: {
      required: value => !!value || 'Required.',
      min: v => v.length >= 8 || 'Min 8 characters',
      emailMatch: () => ('The email and password you entered don\'t match'),
    },
  }),

  computed: {
    domainName () {
      return window.config.tenantDomain + '.' + window.config.centralDomain;
    }
  },

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({ name: 'home' })
    },
    signInWithGoogle () {
      // TODO: fetch this URL dynamically
      window.location.href = '//register.' + window.config.centralDomain + '/login/oauth/google/' + window.config.tenantDomain
    }
  }
}
</script>
