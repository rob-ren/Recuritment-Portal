@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{  __('Position List')}}
                        <div class="row float-right">
                            <a href="{{route('positionCreate')}}"
                               class="btn btn-primary btn-sm">{{ __('Add Position') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('positionQuery')}}" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-sm-5">
                                    <label class="col-form-label">{{ __('Client')}}</label>
                                    <select id="client" class="form-control" name="client_id">
                                        <option value="all">All</option>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}" {{$client->id != $criteria['client_id']?:'selected'}}>{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-5">
                                    <label class="col-form-label">{{ __('Status')}}</label>
                                    <select id="status" class="form-control" name="status">
                                        <option value="all">All</option>
                                        @foreach($position_status_array as $key=>$position_status)
                                            <option value="{{$key}}" {{$criteria['status'] != $key ?:'selected'}}>{{$position_status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-2" style="margin-top: 37px;">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Search') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="anyClass">
                            <table id="positionList" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('Title')}}</th>
                                    <th>{{ __('Client')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th>{{ __('Number of Applicants') }}</th>
                                    <th>{{ __('Create Time')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($positions as $position)
                                    <tr>
                                        <td>{{ $position->title  }}</td>
                                        <td>{{ $position->client_name }}</td>
                                        <td>{{ $position->status?$position_status_array[$position->status]:null }}</td>
                                        <td>{{ $position->applied_count }}</td>
                                        <td>{{ date_format((new DateTime($position->created_at))->setTimezone(new DateTimeZone('Australia/Melbourne')),'Y-m-d H:i:s') }}</td>
                                        <td><a href="{{ route('positionUpdate',$position->id)}}"
                                               class="btn btn-primary btn-sm">{{ __('Edit Position')}} </a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#positionList').DataTable({
            "pageLength": 25
        });
        $('.dataTables_length').addClass('bs-select');
    </script>
@endsection
