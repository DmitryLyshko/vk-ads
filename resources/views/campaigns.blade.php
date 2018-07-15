@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <table class="table">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Type</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($campaigns as $campaign)
                    <tr>
                        <th scope="row">{{$campaign->id}}</th>
                        <td><a href="{{url()->current()}}/{{$campaign->name}}/{{$campaign->id}}">{{$campaign->name}}</a></td>
                        <td>{{$campaign->status}}</td>
                        <td>{{$campaign->type}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="/accounts">К списку рекламных кабинетов</a>
        </div>
    </div>
</div>
@endsection
