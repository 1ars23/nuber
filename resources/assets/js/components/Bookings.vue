<template>
<div class="container">
  <h2>Bookings</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Address</th>
        <th>CarType</th>
        <th>Booking Reference</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="models in model">

        <td>{{models.id}}</td>
        <td>{{models.email}}</td
        <td>{{models.address}}</td>
        <td>{{models.car_type}}</td>
        <td>{{models.booking_reference}}</td>
        <td>{{models.status}}</td>
      </tr>
      
    </tbody>
  </table>
</div>
</template>

<script>

import axios from 'axios'
    export default {
        data() {
            return {
                    model:{},
                    timer: ''
}
        },
        created:function(){
            this.fetchDataBookings()
            this.timer = setInterval(this.fetchDataBookings, 5000)
},
        methods:{
            fetchDataBookings(){
var vm = this
                axios.post('api/all_booking')
                    .then(function (response){
                            Vue.set(vm.$data, 'model',response.data)

})
}
}
    }
</script>
