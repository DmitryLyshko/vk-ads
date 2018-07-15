@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <p class="navbar-brand">
                    <img src="{{$profile->photo_200}}" width="30" height="30" class="d-inline-block align-top" alt="">
                    {{$profile->last_name}} {{$profile->first_name}}
                </p>

                <table class="table">
                    <thead>
                    <tr>
                        <th>Account id</th>
                        <th>Account name</th>
                        <th>Account type</th>
                        <th>Access role</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <th scope="row">{{$account->account_id}}</th>
                            <td><a href="{{$account->account_name}}/{{$account->account_id}}">{{$account->account_name}}</a></td>
                            <td>{{$account->account_type}}</td>
                            <td>{{$account->access_role}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
