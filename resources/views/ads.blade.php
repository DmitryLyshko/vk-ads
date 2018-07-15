@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <nav>
                    <a href="{{$breadcrumbs['account']['uri']}}">
                       {{$breadcrumbs['account']['name']}}
                    </a>
                    <i> -> </i>
                    <a href="{{$breadcrumbs['campaign']['uri']}}">
                        {{$breadcrumbs['campaign']['name']}}
                    </a>
                </nav>

                <table class="table">
                    @foreach ($ads as $item)
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Значение</th>
                        <th>Дополнительно</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Название объявления</td>
                            <td>{{$item->id}}</td>
                            <td>
                                @if ($item->approved !== '3')
                                    <a href="{{url()->current()}}?block_ads={{$item->id}}">Добавить в архив</a>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>Название объявления</td>
                            <td>{{$item->name}}</td>
                        </tr>

                        <tr>
                            <td>Статус модерации объявления</td>
                            <td>{{$item->approved}}</td>
                        </tr>

                        <tr>
                            <td>Дата создания</td>
                            <td>{{date('Y-m-d H:i:s', $item->create_time)}}</td>
                        </tr>

                        <tr>
                            <td>Дата обновления</td>
                            <td>{{date('Y-m-d H:i:s', $item->update_time)}}</td>
                        </tr>

                        <tr>
                            <td>Идентификатор кампании</td>
                            <td>{{$item->campaign_id}}</td>
                        </tr>

                        <tr>
                            <td>Общий лимит</td>
                            <td>{{$item->all_limit}}</td>
                        </tr>

                        <tr>
                            <td>Лимит в день</td>
                            <td>{{$item->day_limit}}</td>
                        </tr>


                        <tr>
                            <td>Статус</td>
                            <td>{{$item->status}}</td>
                        </tr>

                        <tr>
                            <td>Общий лимит объявления в рублях.</td>
                            <td>{{$item->all_limit}}</td>
                        </tr>

                        <tr>
                            <td>Платформа</td>
                            <td>{{$item->ad_platform}}</td>
                        </tr>

                        <tr>
                            <td>Формат объявления</td>
                            <td>{{$item->ad_format}}</td>
                        </tr>

                        <tr>
                            <td>Тип оплаты</td>
                            <td>{{$item->cost_type}}</td>
                        </tr>

                        @if ($item->ad_format === 1)
                        <tr>
                            <td>
                            <form method="GET">
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Описание: </label>
                                    <textarea maxlength="100" class="form-control" id="exampleFormControlTextarea1" name="description[{{$item->id}}]" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Обновить</button>
                            </form></td>
                        </tr>
                        @endif
                    </tbody>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
@endsection
