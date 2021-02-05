@extends('layout')

@section('content')

<div class="container" id="content">
<a href="/clients/" >Просмотр всех клиентов</a>
@{{i}}
    <h1>New Client</h1>

    <!--  -->
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

    <div class="progress" style="width: 40%;">
      <div class="progress-bar"
           role="progressbar"
           :style="progressWidth"
           aria-valuenow="25"
           aria-valuemin="0"
           aria-valuemax="100"
           >
      </div>
    </div>

    <form action="/clients/add" method="post" class="form-group" style="width: 30%;" >
        @csrf
        <div v-for="(item, index) in clientInfo" :key="index">
            <div>
                <label>@{{ item.name }}</label>
                    <span class="fa"
                            v-if="controls[index].activated"
                            :class="controls[index].error ? 'fa-exclamation-circle text-danger' :
                                                            'fa-check-circle text-success'"
                            >
                    </span>
                    <br>
                    <input class="form-control form-control-sm"
                            type="text"
                            :id="item.name"
                            :name="item.name"
                            :value="item.value"
                            @input="onInput(index, $event.target.value)"
                    >
                <br>
            </div>
        </div>

        <!-- hidden input for quantity field -->
        <input name="quantity" type="hidden" :value="i">

        <input type="radio" id="male" name="gender" value=1 v-model="pickedGender" >
            <label for="male">Male</label><br>
        <input type="radio" id="female" name="gender" value=0 v-model="pickedGender">
            <label for="female">Female</label><br>

        <!--ADDING VEHICLE  -->
        <label for="vehicle">Vehicles</label>&nbsp;
        <button type="button" class="add vehicle" @click="addVehicle()">+</button><br>

        <div v-for="(vehicle, index) in vehicles" :key="'A' + index">
            <label for="vehicle">
            Vehicle @{{ index + 1 }}
            <i style="color:red" class="fa fa-window-close"  @click="removeVehicle($event, index)" aria-hidden="true"></i>
            </label><br>
            <label>Manufacturer</label>
                <input class="form-control form-control-sm" type="text"  :name="vehicles[index]['manufacturer']"
            ><br>
            <label>Model</label>
                <input class="form-control form-control-sm" type="text"  :name="vehicles[index]['model']"
            ><br>
            <label>Color</label>
                <input class="form-control form-control-sm" type="text"  :name="vehicles[index]['color']"
            ><br>
            <label>Plate</label>
                <input class="form-control form-control-sm" type="text"  :name="vehicles[index]['plate']"
            ><br>

            <label for="parked">Parked</label>&nbsp
            <input type="radio"  :name="vehicles[index]['parked']" value=1  >
            <label for="parked">Yes</label>&nbsp
            <input type="radio"  :name="vehicles[index]['parked']" value=0 >
            <label for="parked">No</label>

        </div>
        <input type="submit" value="Create a new Client" >
    </form>

</div>

@endsection




@section('scripts')
<script>
        const contentapp = {
            data() {
                return {
                    pickedGender: "",
                    // parked: "",
                    //
                    i: 0,
                    vehicles: [],
                    controls: [],
                    showResult: false,
                    clientInfo: [
                        {
                            name: 'name',
                            value: '',
                            pattern: /^[a-zA-Z]+$/
                        },
                        {
                            name: 'telephone',
                            value: '',
                            pattern: /^[1-9]\d*$/
                        },
                        {
                            name: 'address',
                            value: '',
                            pattern: /^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/
                        },
                    ],
                }
            },
            methods: {
                addVehicle(){
                    this.vehicles.push({
                        manufacturer: "vehicle[" + this.i + "][manufacturer]",
                        model: "vehicle[" + this.i + "][model]",
                        color: "vehicle[" + this.i + "][color]",
                        plate: "vehicle[" + this.i + "][plate]",
                        parked: "vehicle[" + this.i + "][parked]",
                    })
                    this.i++
                },
                removeVehicle(e, index){
                    this.vehicles.splice(index, 1)
                    this.i--
                },
                onInput(index, value){
                    let data = this.clientInfo[index]
                    let control = this.controls[index]
                    data.value = value
                    control.error = !data.pattern.test(value)
                    control.activated = true
                },
            },
            computed: {
                done(){
                    let done = 0
                    for(let i=0; i<this.controls.length;i++){
                        if(!this.controls[i].error){
                        done++
                        }
                    }
                    return done
                },
                progressWidth(){
                    return {
                        width: (this.done / this.controls.length * 100) + '%'
                    }
                }
            },
            beforeMount(){
                for(let i=0; i<this.clientInfo.length;i++){
                this.controls.push({
                    error: true,
                    activated: false
                })
                }
            },
            mounted(){
                console.log('mounted')
            }
        }
        Vue.createApp(contentapp).mount('#content')

    </script>
@endsection
