<template>
  <div v-show="showImgUpload" style="border:1px solid black;padding: 15px 30px;">
    <v-row>
      <v-avatar>
        <img width="40" height="40" :src="img_url" class="profile-photo">
      </v-avatar>
      <v-file-input 
        :rules="rules"
        accept="image/png, image/jpeg, image/bmp"
        placeholder="Pick an avatar"
        prepend-icon="mdi-camera"
        style="margin-left:20px"
        @change="GetImage"
      ></v-file-input>
    </v-row>
    <div style="margin-top:15px">
      <v-btn class="primary-btn" @click="closeModal">Close</v-btn>
      <v-btn class="primary-btn float-right" @click="uploadPhoto">Save</v-btn>
    </div>
  </div>
</template>

<script>
import eventBus from '../../plugins/eventBus'
import * as axios from 'axios'

export default {
  name: 'FileUploadModal',
  props: {
    user_img_url: null
  },
  data () {
    return {
      showImgUpload: false,
      user_id: null,
      rules: [
        value => !value || value.size < 2000000 || 'Avatar size should be less than 2 MB!',
      ],
      img_url: null,
      img_data: null
    }
  },
  mounted () {
    console.log("user_img_url=", this.user_img_url)
    eventBus.$on('show-modal', ({ userId }) => {
      this.showImgUpload = true
      this.user_id = userId
    })
  },
  methods: {
    closeModal () {
      this.showImgUpload = false
    },
    GetImage(e) {
      if(e==undefined) {
        return
      }
      this.img_data = e
      console.log(e)
      let reader = new FileReader()
      reader.readAsDataURL(this.img_data)
      reader.onload = e => {
        this.img_url = e.target.result
      }
    },
    async uploadPhoto () {
      let formData = new FormData()
      formData.append('image', this.img_data)
      const {data} = await axios.post('/api/users/' + this.user_id + '/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      this.showImgUpload = false
      await this.$store.dispatch('users/fetchUsers')
      await this.$store.dispatch('auth/fetchUser')
      eventBus.$emit('change_user_avatar', this.img_url)
    }
  }
}
</script>

<style scoped>
.modal-dialog.file-upload {
  margin: 0;
  margin-bottom: 20px;
}
.modal-footer {
  padding: 0.5rem;
}
.input-file{
  padding-top: 3px;
}
</style>
