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

<div class="container" id="content">
<a href="/clients/create_form" >Добавить нового клиента</a></br>
<a href="/vehicle/parked" >Машины находящиеся на стоянке</a>

    <h2>Все клиенты</h2>

    <table class="table">

        <tbody>
        @foreach($clients as $client)

            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->vehicle_id }}</td>
                <td>{{ $client->manufacturer }}</td>
                <td>{{ $client->plate }}</td>
                <td>
                    <a href="/clients/edit/{{$client->id}}" class="btn btn-primary a-btn-slide-text">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>
                </td>
                <td>
                    <form action="/vehicle/{{$client->vehicle_id}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button id="submit" name="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $clients->links() }}
</div>

@endsection







