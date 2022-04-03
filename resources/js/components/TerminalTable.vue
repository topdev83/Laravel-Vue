<template>
<div>
  <div style="margin-left: 0.5rem">
      <div class="col-9">
          <h4>Terminals</h4>
          <div style="margin-left:40px">
            <p>Pending...</p>
            <div style="display:flex">
                <div style="margin-right: 10px">
                    <div style="font-style:italic">mac address</div>
                    <input class="terminal-input" type="text" v-model="newMacAddress">
                </div>
                <div style="margin-right: 10px">
                    <div style="font-style:italic">device name</div>
                    <input class="terminal-input" type="text" v-model="newDeviceName">
                </div>
                <div style="margin-right: 10px">
                    <div style="font-style:italic">ip address</div>
                    <input class="terminal-input" type="text" v-model="newIpAddress">
                </div>
                <div>
                    <div style="color: white">fef</div>
                    <v-btn text icon @click="AddNewItem()">
                    <v-icon style="font-size: 30px">mdi-plus-circle-outline</v-icon>
                    </v-btn>
                </div>
            </div><br/>
            <p>Registered {{terminalItems.length}}</p>
        </div>
      </div>
  </div>

  <v-simple-table class="terminal-table">
    <template>
      <thead style="width: 100%">
        <tr>
          <th style="width:5%"></th>
          <th class="text-left" style="width: 15%; text-align:left;font-style:italic">
            mac address
          </th>
          <th class="text-left" style="width: 35%; text-align:left;font-style:italic">
            device name
          </th>
          <th class="text-left" style="width: 15%; text-align:left;font-style:italic">
            last ip address
          </th>
          <th class="text-left" style="width: 15%; text-align:left;font-style:italic">
            date registered
          </th>
          <th class="text-left" style="width: 5%; text-align:left;font-style:italic">
            assigned
          </th>
          <th style="width: 5%; text-align:left"></th>
          <th style="width: 5%; text-align:left;border-bottom:none"></th>
        </tr>
      </thead>
      <tbody style="width: 100%">
        <tr
          v-for="(item, index) in terminalItems"
          :key="index"
          class="terminal-tbody"
        >
          <td style="width:5%">#{{index + 1}}</td>
          <td style="width:15%">{{ item.macaddress }}</td>
          <td style="width:35%;position: relative">
            {{ item.devicename }}
            <span style="float: right">
              <div text icon color="lighten-2" style="position: absolute; right: 2px;top: 2px;cursor:pointer">
                <font-awesome-icon :icon="['fas','edit']" style="color: #81D4FA"/>
              </div>
            </span>
          </td>
          <td style="width:15%">{{ item.lastip }}</td>
          <td style="width:15%">{{ item.dateregister }}</td>
          <td style="width:5%">
            <div style="cursor:pointer">
              <v-icon style="font-size: 25px">mdi-lock</v-icon>
            </div>
          </td>
          <td style="width:5%">
            <div style="cursor:pointer">
                <v-icon style="font-size: 25px">mdi-lock</v-icon>
            </div>
          </td>
          <td style="width:5%;background:white">
            <div style="cursor:pointer" @click="DeleteItem(index)">
                <i class="fa fa-trash-o" style="font-size:25px;color:red"></i>
            </div>
          </td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</div>
</template>

<script>
import {library} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
 
export default {
  data () {
      return {
      terminalItems: [
          {
          macaddress: "AA:45:B0:22:A3",
          devicename: "Android 45RT6546",
          lastip: "192.168.1.22",
          dateregister: "22/03/2020",
          },
          {
          macaddress: "AA:45:B0:22:A3",
          devicename: "Android 45RT6546",
          lastip: "192.168.1.22",
          dateregister: "22/03/2020",
          },
          {
          macaddress: "AA:45:B0:22:A3",
          devicename: "Android 45RT6546",
          lastip: "192.168.1.22",
          dateregister: "22/03/2020",
          },
      ],
      newMacAddress: "",
      newDeviceName: "",
      newIpAddress: ""
      }
  },
  components: {
      FontAwesomeIcon,
      
  },
  methods: {
    AddNewItem() {
      var object_var = {}
      object_var.macaddress = this.newMacAddress;
      object_var.devicename = this.newDeviceName;
      object_var.lastip = this.newIpAddress;
      var currentDay = new Date();
      if(currentDay.getMonth() + 1 < 10) {
          object_var.dateregister = currentDay.getDate() + '/' + 0 + (currentDay.getMonth()+1).toString() + '/' + currentDay.getFullYear();
      }else {
          object_var.dateregister = currentDay.getDate() + '/' + (currentDay.getMonth()+1).toString() + '/' + currentDay.getFullYear();
      }
        this.terminalItems.push(object_var)
    },
    DeleteItem(index) {
      this.terminalItems.splice(index, 1);
    }
  }
}
</script>

<style scoped>
.terminal-table {
  width: 100%;
}
.terminal-table thead tr th {
  padding: 0px !important;
  border-bottom: none !important;
  height: 0px !important;
}
.terminal-tbody td{
  margin-left: 5px !important;
  border: 1px solid white;
  border-bottom: none !important;
}
.terminal-tbody {
  background: #ECEFF1;
}
.terminal-input {
  background:#ECEFF1;
  height: 35px;
  color:#C2185B;
  padding-left:5px;
}
</style>