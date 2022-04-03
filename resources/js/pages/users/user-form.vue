<template>
  <v-row class="form-holder">
    <v-col v-if="form.id" sm="12" md="12" lg="12" xl="12">
      <FileUploadModal user_img_url="avatar_url"/>
      <!-- Change password dialog -->
      <v-row style="margin-top:15px">
        <v-col sm="5" md="5" lg="5" xl="5" data-app>
          <v-dialog
            v-model="changePasswordDlg"
            width="500"
            persistent
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn class="primary-btn" v-bind="attrs" v-on="on" @click="OpenChangePasswordDlg()">
                {{$t('reset_password')}}
              </v-btn>
            </template>
            <div class="changePasswordDlg">
              <h3>{{$t('reset_password')}}</h3><br/>
              <label for="inputPassword">{{$t('new_password')}}</label>
              <v-text-field 
                v-model="newPassword"
                name="newPassword"
                type="password"
                single-line
                outlined
              ></v-text-field>
              <label for="inputPassword">{{$t('confirm_password')}}</label>
              <v-text-field v-model="newPasswordConfirm"
                type="password"
                name="newPasswordConfirm"
                single-line
                outlined
              ></v-text-field>
              <div class="float-right">
                <v-btn class="primary-btn" @click="ChangePassword()">{{ $t('save') }}</v-btn>
              </div>
            </div>
          </v-dialog>
        </v-col>
        <v-col sm="7" md="7" lg="7" xl="7">
          <v-row>
            <v-col sm="8" md="8" lg="8" xl="8" class="float-right">
              <button class="btn btn-sm" @click="showModal">
                change avatar<FontAwesomeIcon icon="plus-circle" />
              </button>
              <br>
              <button class="btn btn-sm" @click="removeAvatar">
                remove avatar<FontAwesomeIcon icon="minus-circle" />
              </button>
            </v-col>
            <v-col>
              <img width="40" height="40" :src="avatar_url" class="profile-photo">
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-col>
    <v-col sm="12" md="12" lg="12" xl="12">
      <v-form>
        <div>
          <v-text-field label="Full name" v-model="form.name" name="name"></v-text-field>
        </div>
        <div>
          <v-text-field label="Email" v-model="form.email" name="email"></v-text-field>
        </div>
        <div>
          <v-text-field label="Username" v-model="form.username" name="username"></v-text-field>
        </div>
        <div>
          <v-select label="User level" v-model="user_level" :items="user_levels" ></v-select>
        </div>

        <v-row v-if="showOauthConnect">
          <v-col sm="12" md="12" lg="12" xl="12">
            <template v-if="isConnectedToGoogle">
              <a>
                <img src="/images/google-icon.png" algin="left">
                <span>Connected to Google account</span>
              </a>
            </template>
            <login-with-google
              v-else
              button-text="Link to Google account"
              :domain="tenantDomain"
              @onSucees="connectedGoogleAccount"></login-with-google>
          </v-col>
        </v-row>

        <div>
          <v-row>
            <v-col sm="6" md="6" lg="6" xl="6" v-if="form.id!=null" class="float-left">
              <v-btn :disabled="form.busy" class="primary-btn" @click="deleteUser()">
                {{ $t('delete') }}
              </v-btn>
            </v-col>
            <v-col style="text-align:right">
              <template v-if="form.id==null">
                <v-btn :disabled="form.busy" class="primary-btn" @click="save()">
                  {{ $t('save') }}
                </v-btn>
              </template>
              <template v-else>
                <v-btn :disabled="form.busy" class="primary-btn" @click="update()">
                  {{ $t('save') }}
                </v-btn>
              </template>
            </v-col>
          </v-row>
        </div>
        <v-row v-if="userData.id">
          <div class="col-auto">
            <small>Date created: {{dateCreated}}</small>
          </div>
          <div class="col-auto">
            <small>Date updated: {{dateUpdated}}</small>
          </div>
        </v-row>
      </v-form>
    </v-col>
  </v-row>
</template>

<script>
import Form from 'vform'
import eventBus from '../../plugins/eventBus'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import FileUploadModal from '../../components/users/FileUploadModal'
import * as axios from 'axios'
import moment from 'moment'
import LoginWithGoogle from '../../components/LoginWithGoogle'

export default {
  name: 'UserForm',
  middleware: 'auth',

  components: {
    FontAwesomeIcon,
    FileUploadModal,
    LoginWithGoogle
  },

  metaInfo () {
    return { title: this.$t('register') }
  },

  data: () => ({
    form: {},
    errors: {},
    domain: { edit: false },
    mustVerifyEmail: false,
    showFileModal: false,
    userData: {},
    changePasswordDlg: false,
    currentPassword: '',
    newPassword: '',
    newPasswordConfirm: '',
    showNewPassword: false,
    showConfirmPassword: false,
    rules: {
      required: value => !!value || 'Required.',
      min: v => v.length >= 8 || 'Min 8 characters',
      emailMatch: () => ('The email and password you entered don\'t match'),
    },
    user_levels: ['Admin', 'User'],
    user_level: 'Admin',
    avatar_url: null
  }),


  computed: {
    userLevels () {
      return [
        { id: 1, title: 'Admin' },
        { id: 2, title: 'User' }
      ]
    },
    dateCreated () {
      if (this.userData.id) {
        return moment(this.userData.created_at).format('DD/MM/YYYY')
      }
      return ''
    },
    dateUpdated () {
      if (this.userData.id) {
        return moment(this.userData.updated_at).format('DD/MM/YYYY')
      }
      return ''
    },
    photoUrl () {
      let photoUrl
      this.$store.state.users.users.map((user) => {
        if (user.id === this.form.id) {
          photoUrl = user.photo_url
        }
      })
      return photoUrl
    },
    showOauthConnect () {
      return this.form.id === this.$store.state.auth.user.id
    },
    isConnectedToGoogle () {
      return this.$store.state.auth.oauthConnections.includes('google')
    },
    tenantDomain () {
      return window.config.tenantDomain
    }
  },

  created () {
    this.resetForm()
  },

  async mounted () {
    this.avatar_url = this.photoUrl
    eventBus.$on('edit-user', async ({ id }) => {
      this.userData = await this.$store.dispatch('users/fetchUser', id)
      // eslint-disable-next-line camelcase
      let { name, email, username, user_level, photo_url } = this.userData
      let user = { id, name, email, username, user_level, photo_url }
      this.form = Object.assign(this.form, user)
    })
    
    eventBus.$on('create-user', async () => {
      this.resetForm()
    })

    eventBus.$on('errors-user', ({ errors }) => {
      console.log(errors)
      for (let key in errors) {
        if (errors.hasOwnProperty(key)) {
          this.errors[key] = errors[key].join(',')
        }
      }
    })

    eventBus.$on('change_user_avatar', (img_url) => {
      this.avatar_url = img_url
    })
  },

  methods: {
    save () {
      // console.log(this.form)
      for (let i = 1; i < this.userLevels.length+1; i++) {
        if(this.user_level === this.userLevels[i-1].title) {
          this.form.user_level = i
          break
        }
      }
      this.$emit('childToParent')
      this.user_level = this.userLevels[0].title
      this.$store.dispatch('users/storeUser', this.form)
    },
    update () {
      this.$store.dispatch('users/updateUser', this.form)
    },
    async deleteUser () {
      let response = await confirm('Are you sure want to delete ' + this.form.email + ' ?')
      if (response) {
        await this.$store.dispatch('users/deleteUser', { id: this.form.id })
        this.resetForm()
        await this.$store.dispatch('users/fetchUsers')
        eventBus.$emit('create-user')
      }
    },
    resetForm () {
      this.form = new Form({
        id: null,
        name: '',
        email: '',
        user_level: 1
      })
      this.errors = []
    },
    showModal () {
      eventBus.$emit('show-modal', { userId: this.form.id })
    },
    async removeAvatar () {
      let response = await confirm('are you sure want to remove this avatar?')
      if (response) {
        await axios.delete('/api/users/' + this.form.id + '/upload')
        await this.$store.dispatch('users/fetchUsers')
      }
    },
    connectedGoogleAccount () {
      this.$store.dispatch('auth/updateUser')
    },
    OpenChangePasswordDlg() {
      this.userData = this.$store.dispatch('users/fetchUser', this.form.id)
    },
    ChangePassword() {
      if(this.newPassword === this.newPasswordConfirm) {
        this.changePasswordDlg = false
        this.newPassword = ''
        this.newPasswordConfirm = ''
        this.currentPassword = ''
      }
    },
  }

}
</script>

<style scoped>
.form-holder{
  margin: 0 10px;
  padding-top: 50px;
  padding-bottom: 10px;
  padding-left: 10px;
  padding-right: 10px;
}
.btn-action{
  padding: 6px 30px;
}
.btn-google-connect {
  padding: 0;
  text-align: left;
  border: 1px solid #4285f4;
  background: #4285f4;
  color:#fff;
}
.btn-google-connect > img{
  width: 50px;
}
.btn-google-connect > span{
  padding-left: 20px;
}
</style>
<style>
.v-dialog {
  background: white;
  padding-top: 20px;
  padding-bottom: 15px;
  padding-left: 25px;
  padding-right: 25px;
}
</style>
