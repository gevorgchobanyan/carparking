@extends('layout')


@section('content')


@if (Session::get('success'))
    <div class="alert alert-success">
        DB: {{Session::get('success')}}
    </div>
    @elseif (Session::get('fail'))
    <div class="alert alert-warning">
        DB: {{Session::get('fail')}}
    </div>
    @endif
    <!--  -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>Input validation: {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<div class="container" id="content">
<a href="/clients/" >Просмотр всех клиентов</a>
    <h1>Клиент</h1>
    <form :action="'/clients/update/' + client[0]['id']" method="post" class="form-group" style="width: 30%;" >
        @csrf
        @method('PUT')
        <label>name</label>
            <input class="form-control form-control-sm" type="text" name="name" v-model="client[0]['name']"><br>
        <label>telephone</label>
            <input class="form-control form-control-sm" type="text" name="telephone" v-model="client[0]['telephone']"><br>
        <label>address</label>
            <input class="form-control form-control-sm" type="text" name="address" v-model="client[0]['address']"><br>

        <!-- hidden input for quantity field -->
        <input name="quantity" type="hidden" :value="i">

        <input type="radio" id="male" name="gender" value=1 v-model="pickedGender" >
            <label for="male">Male</label><br>
        <input type="radio" id="female" name="gender" value=0 v-model="pickedGender">
            <label for="female">Female</label><br>

        <input type="submit" value="Сохранить" >
    </form>


    <h1>Автомобили</h1>

        <div v-for="(vehicle, index) in vehicles" :key="'A' + index">
            <form :action="'/vehicle/update/' + vehicles[index]['vehicle_id']" method="post" class="form-group" style="width: 30%;" >
                @csrf
                @method('PUT')
                Автомобиль @{{ index + 1 }}</br>

                <!-- -->
                <input name="client_id" type="hidden" v-model="client[0]['id']">

                <label>Manufacturer</label>
                    <input class="form-control form-control-sm" type="text"  name="manufacturer" v-model="vehicles[index]['manufacturer']"
                ><br>

                <label>Model</label>
                    <input class="form-control form-control-sm" type="text"  name="model" v-model="vehicles[index]['model']"
                ><br>

                <label>Color</label>
                    <input class="form-control form-control-sm" type="text"  name="color" v-model="vehicles[index]['color']"
                ><br>

                <label>Plate</label>
                    <input class="form-control form-control-sm" type="text"  name="plate" v-model="vehicles[index]['plate']"
                ><br>
                <label for="parked">Parked</label>&nbsp
                <input type="radio"  name="parked" value=1  v-model="parkedArr[index]" >
                <label for="parked">Yes</label>&nbsp
                <input type="radio"  name="parked" value=0 v-model="parkedArr[index]" >
                <label for="parked">No</label></br>

                <input type="submit" value="Сохранить" >
            </form>
        </div>


        <!-- <button type="button" class="add vehicle" @click="addVehicle()">+</button><br> -->

        <form action="/vehicle/add" method="post" class="form-group" style="width: 30%;" >
                @csrf
                <label>Добавить новый автомобиль</label></br>
                <!-- -->
                <input name="client_id" type="hidden" v-model="client[0]['id']">

                <label>Manufacturer</label>
                    <input class="form-control form-control-sm" type="text"  name="manufacturer"
                ><br>
                <label>Model</label>
                    <input class="form-control form-control-sm" type="text"  name="model"
                ><br>
                <label>Color</label>
                    <input class="form-control form-control-sm" type="text"  name="color"
                ><br>
                <label>Plate</label>
                    <input class="form-control form-control-sm" type="text"  name="plate"
                ><br>
                <label for="parked">Parked</label>&nbsp
                <input type="radio"  name="parked" value=1  >
                <label for="parked">Yes</label>&nbsp
                <input type="radio"  name="parked" value=0  >
                <label for="parked">No</label></br>

                <input type="submit" value="Сохранить" >
            </form>

</div>

@endsection





@section('scripts')
<script>

var client_ = @json($client);
var i_ = client_[0]['car_quantity'];
var pickedGender_ = client_[0]['gender'];
var vehicles_ = @json($vehicles);
var parkedArr_ = [];
for(let i=0;i<vehicles_.length;i++){
    parkedArr_ += vehicles_[i]['parked']
}

    const contentapp = {
        data() {
            return {
                pickedGender: pickedGender_,
                client: client_,
                vehicles: vehicles_,
                i: i_,
                parkedArr: parkedArr_
            }
        },
        methods: {
            addVehicle(){
                this.vehicles.push({
                    // manufacturer: "vehicle[" + this.i + "][manufacturer]",
                    // model: "vehicle[" + this.i + "][model]",
                    // color: "vehicle[" + this.i + "][color]",
                    // plate: "vehicle[" + this.i + "][plate]",
                    // parked: "vehicle[" + this.i + "][parked]",
                })
                this.i++
            },
            removeVehicle(e, index){
                this.vehicles.splice(index, 1)
                this.i--
            },
        },
    }
    Vue.createApp(contentapp).mount('#content')

    </script>
@endsection
