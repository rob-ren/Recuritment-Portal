@extends('layouts.app')

@section('content')
    <div class="container">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="{{route('candidateList')}}">{{  __('Candidate List')}}</a>
        </nav>
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card">
                    <div class="card-header">{{ __('Update Candidate') }}</div>
                    <form method="get">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                    <div class="card-body">
                        @if (session('candidate_success'))
                            <div class="alert alert-success">
                                {{ session('candidate_success') }}
                            </div>
                        @endif
                        @if (session('error_msg'))
                            <div class="alert alert-danger">
                                {{ session('error_msg') }}
                            </div>
                        @endif
                        <form action="{{route('candidateUpdated',$candidate->id)}}" method="post"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('First Name *')}}</label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                           name="first_name" value="{{$candidate->first_name}}" required autofocus>
                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Last Name *')}}</label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                           name="last_name" value="{{$candidate->last_name}}" required autofocus>
                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Email')}}</label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" type="email" value="{{$candidate->email}}">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Phone')}}</label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                           name="phone" type="number" value="{{$candidate->phone}}">
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="role"
                                       class="col-sm-4 col-form-label text-md-right">{{ __('Role *')}}</label>
                                <div class="col-md-6">
                                    <select id="role" class="form-control" name="role_id">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{$role->id != $candidate->role_id ?:'selected'}}>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="recruiter"
                                       class="col-sm-4 col-form-label text-md-right">{{ __('Recruiter *')}}</label>
                                <div class="col-md-6">
                                    <select id="recruiter" class="form-control" name="recruiter_id">
                                        @foreach($recruiters as $recruiter)
                                            <option value="{{$recruiter->id}}" {{$recruiter->id != $candidate->recruiter_id ?:'selected'}}>{{$recruiter->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(Auth::user()->role =='admin')
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label text-md-right">{{ __('Wage')}}</label>
                                    <div class="col-md-6">
                                        <input class="form-control{{ $errors->has('wage') ? ' is-invalid' : '' }}"
                                               name="wage" value="{{$candidate->wage}}">
                                        @if ($errors->has('wage'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('wage') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('CV File')}}</label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('cv_file_path') ? ' is-invalid' : '' }}"
                                           name="cv_file_path" type="file">
                                    @if ($errors->has('cv_file_path'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cv_file_path') }}</strong>
                                        </span>
                                    @endif
                                    @if($candidate->cv_file_path)
                                        <a class="btn btn-link" target="_blank"
                                           href={{ route('candidateCVDownload',Crypt::encryptString($candidate->cv_file_path)) }} download>{{__('Download CV')}}</a>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('General Rating')}}</label>
                                <div class="col-md-6">
                                    <select id="rate" class="form-control" name="rate">
                                        <option value="">N/A</option>
                                        @for($i =1; $i <=10;$i++)
                                            <option value="{{$i}}" {{$i != $candidate->rate ?:'selected'}}>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('General Comments')}}</label>
                                <div class="col-md-6">
                                    <textarea class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                              name="comments">{{ $candidate->comments}}</textarea>
                                    @if ($errors->has('comments'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('comments') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Notice Period')}}</label>
                                <div class="col-md-6">
                                    <textarea
                                            class="form-control{{ $errors->has('notice_period') ? ' is-invalid' : '' }}"
                                            name="notice_period">{{ $candidate->notice_period}}</textarea>
                                    @if ($errors->has('notice_period'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('notice_period') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Visa Status')}}</label>
                                <div class="col-md-6">
                                    <textarea class="form-control{{ $errors->has('visa_status') ? ' is-invalid' : '' }}"
                                              name="visa_status">{{ $candidate->visa_status}}</textarea>
                                    @if ($errors->has('visa_status'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('visa_status') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Number of Years Working Experience')}}</label>
                                <div class="col-md-6">
                                    <textarea
                                            class="form-control{{ $errors->has('number_years_experience') ? ' is-invalid' : '' }}"
                                            name="number_years_experience">{{ $candidate->number_years_experience}}</textarea>
                                    @if ($errors->has('number_years_experience'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('number_years_experience') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Reason of Leaving')}}</label>
                                <div class="col-md-6">
                                    <textarea
                                            class="form-control{{ $errors->has('reason_of_leaving') ? ' is-invalid' : '' }}"
                                            name="reason_of_leaving">{{ $candidate->reason_of_leaving}}</textarea>
                                    @if ($errors->has('reason_of_leaving'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('reason_of_leaving') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Communication Skills')}}</label>
                                <div class="col-md-6">
                                    <textarea
                                            class="form-control{{ $errors->has('communication_skills') ? ' is-invalid' : '' }}"
                                            name="communication_skills">{{ $candidate->communication_skills}}</textarea>
                                    @if ($errors->has('communication_skills'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('communication_skills') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Completely Avoid') }}</label>
                                <div class="col-md-6 form-check-inline">
                                    <div class="form-check">
                                        <input class="form-check-input" name="completely_avoid" type="radio"
                                               id="is_completely_avoid"
                                               value="1" {{$candidate->completely_avoid?'checked':''}}>
                                        <label class="form-check-label" for="update_completely_avoid">{{ __('Yes') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" name="completely_avoid" type="radio" id="completely_avoid"
                                               value="0" {{$candidate->completely_avoid?'':'checked'}}>
                                        <label class="form-check-label" for="update_completely_avoid">{{ __('No') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-header">{{ __('Applied Positions') }}</div>
                    <div class="card-body">
                        <div class="anyClass">
                            <table id="positionList" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('Position Title')}}</th>
                                    <th>{{ __('Client')}}</th>
                                    <th>{{ __('Position Status')}}</th>
                                    <th>{{ __('Candidate Status')}}</th>
                                    <th>{{ __('Position Rate')}}</th>
                                    <th>{{ __('Create Time')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($positions as $position)
                                    <tr>
                                        <td>
                                            <a href="{{route('positionUpdate',$position->position_id) }}">{{ $position->title }}</a>
                                        </td>
                                        <td>{{ $position->client_name }}</td>
                                        <td>{{ $position->position_status?$position_status_array[$position->position_status]:null }}</td>
                                        <td>{{ $position->status?$position_candidate_status_array[$position->status]:null }}</td>
                                        <td>{{ $position->position_rate }}</td>
                                        <td>{{ date_format((new DateTime($position->created_at))->setTimezone(new DateTimeZone('Australia/Melbourne')),'Y-m-d')}}</td>
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
    <div class="modal fade" id="completelyAvoidModal" tabindex="-1" role="dialog"
         aria-labelledby="completelyAvoidTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completelyAvoidTitle">{{__('Completely Avoid Warning')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Do you want to completely avoid this candidates and never show him up for any positions?</p>
                </div>
                <div class="modal-footer">
                    <button id="completely_avoid_cancel" type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                    <button id="completely_avoid_confirm" type="submit" class="btn btn-danger btn-ok">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#is_completely_avoid').click(function () {
            $('#completelyAvoidModal').modal('show');
        });

        $('#completely_avoid_cancel').click(function () {
            $('#completelyAvoidModal').modal('hide');
            $('#is_completely_avoid').prop('checked', false);
            $('#completely_avoid').prop('checked', true);
        });

        $('#completely_avoid_confirm').click(function () {
            $('#completelyAvoidModal').modal('hide');
        });
    </script>
@endsection
