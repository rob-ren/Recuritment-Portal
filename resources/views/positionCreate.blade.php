@extends('layouts.app')
<style>
    .hidden {
        display: none !important;
    }
</style>
@section('content')
    <div class="container">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{route('positionList')}}">{{  __('Position List')}}</a>
        </nav>
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card">
                    <div class="card-header">{{ __('Add Postion') }}</div>
                    <form method="get">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                    <div class="card-body">
                        @if (session('error_msg'))
                            <div class="alert alert-danger">
                                {{ session('error_msg') }}
                            </div>
                        @endif
                        <form action="{{route('positionCreated')}}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Title *')}}</label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           name="title" required autofocus>
                                    @if ($errors->has('title'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="client"
                                       class="col-sm-4 col-form-label text-md-right">{{ __('Client *')}}</label>
                                <div class="col-md-6">
                                    <select id="client"
                                            class="form-control{{ $errors->has('client_id') ? ' is-invalid' : '' }}"
                                            name="client_id" required>
                                        <option value="0">--</option>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}">{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('client_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('client_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Description')}}</label>
                                <div class="col-md-6">
                                    <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                              name="description"></textarea>
                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="recruiter"
                                       class="col-sm-4 col-form-label text-md-right">{{ __('Status *')}}</label>
                                <div class="col-md-6">
                                    <select id="status"
                                            class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}"
                                            name="status" required>
                                        <option value="open">Open</option>
                                        <option value="filled">Filled</option>
                                        <option value="closed">Closed</option>
                                        <option value="not_filled">Not Filled</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('status') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div id="closed_date_section" class="form-group row hidden">
                                <label for="closed_date"
                                       class="col-sm-4 col-form-label text-md-right">{{ __('Closed Date')}}</label>
                                <div class="col-md-6">
                                    <input id="closed_date"
                                           class="form-control{{ $errors->has('closed_date') ? ' is-invalid' : '' }}"
                                           name="closed_date" value={{now()}} type="datetime">
                                    @if ($errors->has('closed_date'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('closed_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Add') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#closed_date').datepicker({
            format: 'yyyy-mm-dd',
            sideBySide: true
        });

        $('#status').change(function () {
            var status = $('#status').val();
            var closed_date_section = $('#closed_date_section');
            if (status === 'closed' && closed_date_section.hasClass('hidden')) {
                $('#closed_date_section').removeClass('hidden');
            }
            if (status != 'closed' && !closed_date_section.hasClass('hidden')) {
                $('#closed_date_section').addClass('hidden');
            }
        });
    </script>
@endsection