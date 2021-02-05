@extends('layout')


@section('content')

<div class="container" id="content">

<a href="/clients/" >Просмотр всех клиентов</a></br>
     <label>Клиенты</label>
     <div class="form-group" style="width: 30%;">
        <select name="client" class="form-control" v-model="selected_client" @change="generateVehicles()" required>
            <!-- <option value="">Choose One</option> -->
            <option v-for="client in filters" :value="client['id']">@{{client['name']}}</option>
        </select>
    </div>

    <label>Автомобили</label>
    <div class="form-group" style="width: 30%;">
     <select id="vehicle" name="vehicle" class="form-control" v-model="selected_vehicle_id" @change="enableButtons()" disabled required>
       <!-- <option value="">Choose One</option> -->
       <option v-for="vehicle in clients_vehicles" :value="vehicle['vehicle_id']">@{{vehicle['manufacturer']}}</option>
     </select></br>

     <button id="btn1" type="button" class="btn btn-success" @click="addToParked()" disabled>Поставить</button>&nbsp
     <button id="btn2" type="button" class="btn btn-danger" @click="removeFromParked()" disabled>Снять </button>

    </div>


    <h2>Машины на стоянке</h2>
    <table class="table">
        <tbody>
        @foreach($parked_vehicles as $vehicle)
            <tr>
                <td>{{ $vehicle->vehicle_id }}</td>
                <td>{{ $vehicle->manufacturer }}</td>
                <td>{{ $vehicle->model }}</td>
                <td>{{ $vehicle->color }}</td>
                <td>{{ $vehicle->plate }}</td>
                <td>{{ $vehicle->parked }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection



@section('scripts')
<script>


    const contentapp = {
        data() {
            return {
                dataReady: false,
                parked_vehicles: @json($parked_vehicles),
                all_vehicles: @json($all_vehicles),
                clients_vehicles: [],
                filters: @json($clients),
                selectedValue: null,
                selected_client: null,
                selected_vehicle_id: null,
                selected_vehicle: null,
            }
        },
        methods: {
            generateVehicles(){
                this.clients_vehicles = []
                let j = 0
                for(let i=0;i<this.all_vehicles.length; i++){
                    if(this.selected_client == this.all_vehicles[i]['client_id']){
                        this.clients_vehicles[j] = this.all_vehicles[i]
                        j++
                    }
                }
                document.getElementById('vehicle').disabled = false

            },
            enableButtons(){
                this.selected_vehicle = this.all_vehicles.filter(vehicle => {
                    return vehicle.vehicle_id == this.selected_vehicle_id
                })
                if(this.selected_vehicle[0]['parked'] == 1){
                    document.getElementById('btn1').disabled = true
                    document.getElementById('btn2').disabled = false
                }else{
                    document.getElementById('btn2').disabled = true
                    document.getElementById('btn1').disabled = false
                }

            },
            async addToParked(){
                console.log(this.selected_vehicle_id)
                let url = 'update/parked/' + this.selected_vehicle_id
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                // const formData = new FormData();
                // formData.append('manufacturer', this.selected_vehicle[0]['manufacturer']);

                fetch(url, {
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json, text-plain, */*",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": token
                            },
                        method: 'put',
                        credentials: "same-origin",
                        body: JSON.stringify({
                            parked: '1'
                        })
                    })
                    .then((data) => {
                        window.location.href = 'parked';
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            },

            async removeFromParked() {
                let url = 'update/gone/' + this.selected_vehicle_id
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(url, {
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json, text-plain, */*",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": token
                            },
                        method: 'put',
                        credentials: "same-origin",
                        body: JSON.stringify({
                            parked: '0'
                        })
                    })
                    .then((data) => {
                        window.location.href = 'parked';
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },
        // async mounted(){
        //     const response = await fetch('')
        //     const vehicles_data = await response.json()
        //     this.dataReady = true
        //     this.vehicles = vehicles_data
        // }
    }
    Vue.createApp(contentapp).mount('#content')

    </script>
@endsection
