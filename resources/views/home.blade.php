@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{  __('Candidates List')}}
                        <div class="row float-right">
                            <a href="{{route('candidateCreate')}}"
                               class="btn btn-primary btn-sm">{{ __('Add Candidate') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('candidateQuery')}}" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label class="col-form-label">{{ __('Role')}}</label>
                                    <select id="role"
                                            class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}"
                                            name="role_id">
                                        <option value="all">All</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{$role->id != $criteria['role_id']?:'selected'}}>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label class="col-form-label">{{ __('Rate')}}</label>
                                    <select id="rate" class="form-control" name="rate">
                                        @for($i =1; $i <=10;$i++)
                                            <option value="{{$i}}" {{$i != $criteria['rate'] ?:'selected'}}>{{$i}}+
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label class="col-form-label">{{ __('Created Time')}}</label>
                                    <select id="created_at" class="form-control" name="created_at">
                                        <option value="all">All</option>
                                        <option value="greater_6month" {{ $criteria['created_at'] !='greater_6month'?:'selected'}}>
                                            > 6 Month
                                        </option>
                                        <option value="less_6month" {{$criteria['created_at'] !='less_6month'?:'selected'}}>
                                            < 6 Month
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2 mb-0">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Search') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="anyClass">
                            <table id="candidateList" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Role')}}</th>
                                    <th>{{ __('Client')}}</th>
                                    <th>{{ __('Interview Time')}}</th>
                                    <th>{{ __('Rate')}}</th>
                                    <th>{{ __('Create Time')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($candidates as $candidate)
                                    <tr>
                                        <td>{{ucfirst(sprintf('%s %s',$candidate->first_name, $candidate->last_name)) }}</td>
                                        <td>{{ $candidate->role_name }}</td>
                                        <td>{{ $candidate->client_name }}</td>
                                        <td>{{ Carbon\Carbon::parse($candidate->interview_time)->format('Y-m-d') }}</td>
                                        <td>{{ $candidate->rate }}</td>
                                        <td>{{ $candidate->created_at }}</td>
                                        <td><a href="{{ route('candidateUpdate',$candidate->id)}}"
                                               class="btn btn-primary btn-sm">{{ __('Edit Candidate')}} </a></td>
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
@endsection
