<template>
<div>
  <v-row style="margin-left: 0.5rem">
    <v-col sm="9" md="9" lg="9" xl="9">
      <h4>Stations</h4>
    </v-col>
    <v-col sm="3" md="3" lg="3" xl="3">
      <v-btn class="new-button">
        New <FontAwesomeIcon icon="plus" style="margin-left:10px"></FontAwesomeIcon>
      </v-btn>
    </v-col>
  </v-row>
  <div>
    <v-simple-table class="station-table">
      <template>
        <thead style="width: 100%">
          <tr>
            <th></th>
            <th></th>
            <th>terminals</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody style="width: 100%;background-color:#ECEFF1">
          <tr v-for="(item, index) in station_data" :key="index">
            <td v-bind:style="{background: record_color[index]}" style="max-width:10px;padding:0px"></td>
            <td style="width:70%">{{item.name}}</td>
            <td style="width:10%;text-align:center">{{item.terminals}}</td>
            <td style="width:10%">{{item.avartar}}</td>
            <td style="width: 9%;text-align:center;cursor:pointer"><font-awesome-icon :icon="['fas','edit']" style="color: #81D4FA;font-size:20px"/></td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
  </div>
</div>
</template>

<script>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
 
export default {
  data () {
    return {
      station_data: [
        { name: "#CNC Bending machine", terminals: 1, avatar: ""},
        { name: "#Welding machine", terminals: 3, avatar: ""},
        { name: "#Assembly station", terminals: 2, avatar: ""},
        { name: "#Laser cutting", terminals: 1, avatar: ""},
        { name: "#Chair Assembly", terminals: 4, avatar: ""},
        { name: "#CNC wood cutting", terminals: 0, avatar: ""},
      ],
      record_color: ['#AD4983', '#55B474', '#2095F2', '#DA1918', '#FF6431', '#6E63F4']  
    }
  },
  components: {
      FontAwesomeIcon,
  },
  methods: {
      
  } 
}
</script>

<style>
.new-button {
  width: 100px;
  border-radius: 10px;
  border: 1px solid black;
  height: 30px;
}
.station-table {
  width: 100%;
  margin-top: 20px;
}
.station-table tbody td {
  border: 1px solid white;
  padding: 10px;
  border-bottom: 1px solid white !important;
}
.station-table tbody tr:hover {
  background: #EEEEEE;
}
.station-table thead tr th {
  padding: 0px !important;
  border-bottom: none !important;
  height: 0px !important;
}
</style>