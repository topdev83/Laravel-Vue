<template>
  <v-row justify="center">
    <v-col sm="8" md="8" lg="8" xl="8">
      <div id="first">
        <div class="myform">
          <div>
            <v-row class="text-left" style="display: flex;justify-content: center;">
              <div class="text-center" style="font-size: 60px;margin-right: 10px;font-weight:bold">Welcome to</div>
              <img src="logo.png" algin="left" style="padding: 0px 10px;max-width: 250px;margin-left: 10px;">
            </v-row>
            <v-row class="text-left">
              <div style="font-size: 30px;font-weight:bold">Create your plany account</div><br/>
            </v-row>
          </div>
          <div v-if="mustVerifyEmail" :title="$t('register')">
            <v-alert v-model="alert" dismissible color="#4caf50">{{$t('verify_email_address')}}</v-alert>
          </div>
          <div v-else :title="$t('register')">
            <v-form>
              <div style="margin-top:10px">
                <label for="exampleInputEmail1">Company name</label>
                <v-text-field single-line outlined v-model="form.company_name" name="companyname"></v-text-field>
                <div class="text-right">
                  <input
                    v-if="domain.edit" v-model="form.domain" v-focus
                    class="main tx-tfm-lower"
                    type="text"
                    name="company_name"
                    :class="{ 'is-invalid': form.errors.has('domain') }"
                    @blur="domain.edit = false; $emit('update')"
                    @keyup.enter="domain.edit=false; $emit('update')"
                  >
                  <div v-else>
                    <a class="main tx-tfm-lower" :href="`https://${form.domain ? form.domain : form.company_name}.plany.io`">{{ form.domain ? form.domain :form.company_name }}.plany.io</a>
                    <fa class="main" :icon="['fas', 'edit']" @click="domain.edit = true;" />
                  </div>
                  <has-error :form="form" field="company_name" />
                </div>
                <div v-show="domainError" class="text-danger">
                  <small>{{domainErrorMessage}}</small>
                </div>
              </div>
              <div style="margin-top:10px">
                <label for="exampleInputEmail1">Email address</label>
                <!-- <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="text" name="email">
                <has-error :form="form" field="email" /> -->
                <v-text-field single-line outlined v-model="form.email" name="email"></v-text-field>
              </div>
              <div style="margin-top:10px">
                <label for="exampleInputEmail1">Set a password</label>
                <!-- <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password">
                <has-error :form="form" field="password" /> -->
                <v-text-field
                  v-model="form.password"
                  type="password"
                  name="password"
                  single-line
                  outlined
                ></v-text-field>
              </div>
              <div style="margin-top:30px">
                <div class="text-center">
                  <!-- <v-button :loading="form.busy" class="btn btn-block mainbtn">
                    {{ $t('signup') }}
                  </v-button> -->
                  <v-btn :loading="form.busy" class="mainbtn" block @click="register">{{$t('signup')}}</v-btn>
                </div>
              </div>
            </v-form>
            <div style="margin-top:20px">
              <p class="text-center">
                By sign up your account you agree with plany's <a href="#">Privacy Policy</a> and <a href="#">Terms of use</a>.
              </p>
            </div>
            <div>
              <div class="login-or">
                <hr class="hr-or">
                <span class="span-or">or</span>
              </div>
            </div>
            <login-with-google :domain="domainValidated" />
            <div>
              <p class="text-left">
                Already have an account? <a id="signup" href="/login">Sign in</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import Form from 'vform'
import LoginWithGithub from '~/components/LoginWithGithub'
import LoginWithGoogle from '~/components/LoginWithGoogle'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faEdit } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import * as axios from "axios";

library.add(faEdit)

export default {
  middleware: 'guest',

  components: {
    LoginWithGithub,
    LoginWithGoogle
  },

  metaInfo () {
    return { title: this.$t('register') }
  },
  directives: {
    focus: {
      inserted (el) {
        el.focus()
      }
    }
  },

  data: () => ({
    form: new Form({
      company_name: '',
      domain: '',
      email: '',
      password: ''
    }),
    domain: { edit: false },
    mustVerifyEmail: false,
    domainError: false,
    domainErrorMessage: null,
    alert: true
  }),

  computed: {
    domainValidated () {
      return this.domainError ? null : this.form.domain
    }
  },

  watch: {
    'form.domain': {
      handler (newVal, oldVal) {
        if (newVal.length > 1) {
          this.domainError = false
          this.domainErrorMessage = null
        }
      }
    },
    'domain.edit': {
      handler (editing, wasEditing) {
        if (wasEditing && !editing) {
          this.verifyDomain()
        }
      }
    }
  },

  methods: {
    async register () {
      // Register the user.
      console.log("register button clicked");
      const { data } = await this.form.post('/api/register')

      // Must verify email fist.
      if (data.status) {
        this.mustVerifyEmail = true
        this.alert = true
      } else {
        // Log in the user.
        const { data: { token } } = await this.form.post('//' + data.base_url + '/api/login')

        window.location.href = '//' + data.base_url + '/token/' + token
      }
    },
    async verifyDomain () {
      let { data } = await axios.post('/api/domain/verify', { domain: this.form.domain })
      if (data.available) {
        this.domainError = false
        this.domainErrorMessage = null
      } else {
        this.domainError = true
        this.domainErrorMessage = 'Domain is already taken.'
      }
    }
  }
}
</script>
