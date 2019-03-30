@extends('layouts.app')
<link href="{{ asset('css/typeaheadjs.css') }}" rel="stylesheet">
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
                    <div class="card-header">{{ __('Update Position') }}</div>
                    <form method="get">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                    <div class="card-body">
                        @if (session('position_success'))
                            <div class="alert alert-success">
                                {{ session('position_success') }}
                            </div>
                        @endif
                        @if (session('error_msg'))
                            <div class="alert alert-danger">
                                {{ session('error_msg') }}
                            </div>
                        @endif
                        <form action="{{route('positionUpdated',$position->id)}}" method="post"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Title *')}}</label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           name="title" value="{{$position->title}}" required autofocus>
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
                                    <select id="client" class="form-control" name="client_id">
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}" {{$client->id != $position->client_id ?:'selected'}}>{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Description')}}</label>
                                <div class="col-md-6">
                                    <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                              name="description">{{ $position->description}}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="recruiter"
                                       class="col-sm-4 col-form-label text-md-right">{{ __('Status')}}</label>
                                <div class="col-md-6">
                                    <select id="status"
                                            class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}"
                                            name="status">
                                        @foreach($position_status_array as $key=>$position_status)
                                            <option value="{{$key}}" {{$position->status != $key ?:'selected'}}>{{$position_status}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('status') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div id="closed_date_section" class="form-group row">
                                <label for="closed_date"
                                       class="col-sm-4 col-form-label text-md-right">{{ __('Closed Date')}}</label>
                                <div class="col-md-6">
                                    <input id="closed_date"
                                           class="form-control{{ $errors->has('closed_date') ? ' is-invalid' : '' }}"
                                           name="closed_date" type="datetime"
                                           value='{{ !is_null($position->closed_date)? Carbon\Carbon::parse($position->closed_date)->format('Y-m-d'): now()->format('Y-m-d')}}'>
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
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card">
                    <div class="card-header">{{ __('Associate Candidate') }}
                        <div class="row float-right">
                            <a class="btn btn-primary btn-sm" style="margin-right:5px;"
                               href="{{route('candidateCreateWithPosition',$position->id)}}">{{ __('Add & Associate New Candidate') }}</a>
                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#addCandidate">{{ __('Associate Existing Candidate') }}</button>
                        </div>
                    </div>
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
                                <strong>{{ session('error_msg') }}</strong>
                            </div>
                        @endif
                        @if ($errors->has('candidate_id')||$errors->has('candidate_name'))
                            <div class="alert alert-danger">
                                <strong>{{ $errors->first('candidate_id') }}</strong>
                            </div>
                        @endif
                        <div class="anyClass">
                            <table id="candidateList" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('Candidate Name')}}</th>
                                    <th>{{ __('Interview Time')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th>{{ __('Position Rating')}}</th>
                                    <th>{{ __('Create Time')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($positions_candidates as $candidate)
                                    <tr class="{{$candidate->status == "client_accepted"?'table-success':''}} {{$candidate->status=="apscore_rejected"?'table-danger':''}}">
                                        <td>{{ucfirst(sprintf('%s %s',$candidate->first_name, $candidate->last_name)) }}</td>
                                        <td>{{ $candidate->interview_time?Carbon\Carbon::parse($candidate->interview_time)->format('Y-m-d'):null }}</td>
                                        <td>{{ $candidate->status? $position_candidate_status_array[$candidate->status]:null }}</td>
                                        <td>{{ $candidate->position_rate }}</td>
                                        <td>{{ date_format((new DateTime($candidate->created_at))->setTimezone(new DateTimeZone('Australia/Melbourne')),'Y-m-d')}}</td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal"
                                               data-target="#updateCandidate"
                                               data-id={{$candidate->id}} data-name="{{ucfirst(sprintf('%s %s',$candidate->first_name, $candidate->last_name))}}"
                                               data-interview_time="{{$candidate->interview_time?Carbon\Carbon::parse($candidate->interview_time)->format('Y-m-d'):null}}"
                                               data-status="{{$candidate->status}}"
                                               data-comments="{{$candidate->comments}}"
                                               data-notice_period="{{$candidate->notice_period}}"
                                               data-visa_status="{{$candidate->visa_status}}"
                                               data-number_years_experience="{{$candidate->number_years_experience}}"
                                               data-reason_of_leaving="{{$candidate->reason_of_leaving}}"
                                               data-communication_skills="{{$candidate->communication_skills}}"
                                               data-completely_avoid="{{$candidate->completely_avoid}}"
                                               data-position_rate="{{$candidate->position_rate}}">
                                                {{ __('Update')}}</a>
                                        </td>
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
    <!-- Modal add PositionCandidate Connection -->
    <div class="modal fade" id="addCandidate" tabindex="-1" role="dialog"
         aria-labelledby="addCandidateTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="positionCandidateAddForm" action="{{route('positionCandidateCreated',$position->id)}}"
                      method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCandidateTitle">{{ __('Associate Existing Candidate')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" id="add_candidate_id" class="form-control" name="candidate_id">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Candidate Name *')}}</label>
                            <div id="candidate_typeahead" class="col-md-6">
                                <input id="add_candidate_name" class="form-control typeahead" name="candidate_name"
                                       required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Interview Time')}}</label>
                            <div class="col-md-6">
                                <input id="add_interview_time" class="form-control" name="interview_time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Status')}}</label>
                            <div class="col-md-6">
                                <select id="add_candidate_status"
                                        class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}"
                                        name="candidate_status">
                                    <option value="">--</option>
                                    @foreach($position_candidate_status_array as $key=>$position_candidate_status)
                                        <option value="{{$key}}">{{$position_candidate_status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Interview Comments')}}</label>
                            <div class="col-md-6">
                                <textarea id="add_comments" class="form-control" name="comments"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal update PositionCandidate Connection -->
    <div class="modal fade" id="updateCandidate" tabindex="-1" role="dialog"
         aria-labelledby="updateCandidateTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="positionCandidateUpdateForm" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateCandidateTitle">Update Candidate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" id="position_candidate_id" class="form-control"
                               name="position_candidate_id">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Candidate Name')}}</label>
                            <div class="col-md-6">
                                <input id="candidate_name" class="form-control" name="candidate_name" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Interview Time')}}</label>
                            <div class="col-md-6">
                                <input id="interview_time" class="form-control" name="interview_time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Status')}}</label>
                            <div class="col-md-6">
                                <select id="candidate_status"
                                        class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}"
                                        name="candidate_status">
                                    <option value="">--</option>
                                    @foreach($position_candidate_status_array as $key=>$position_candidate_status)
                                        <option value="{{$key}}">{{$position_candidate_status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Interview Comments')}}</label>
                            <div class="col-md-6">
                                <textarea id="comments" class="form-control" name="comments"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Notice Period')}}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="notice_period"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Visa Status')}}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="visa_status"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Number of Years Working Experience')}}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="number_years_experience"></textarea>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Reason of Leaving')}}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="reason_of_leaving"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Communication Skills')}}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="communication_skills"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Completely Avoid') }}</label>
                            <div class="col-md-6 form-check-inline">
                                <div class="form-check">
                                    <input class="form-check-input" name="completely_avoid" type="radio" value="1"
                                           id="is_completely_avoid">
                                    <label class="form-check-label"
                                           for="update_completely_avoid">{{ __('Yes') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="completely_avoid" type="radio" value="0" id="completely_avoid">
                                    <label class="form-check-label" for="update_completely_avoid">{{ __('No') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Position Rating')}}</label>
                            <div class="col-md-6">
                                <select id="rate" class="form-control" name="rate">
                                    <option value="">N/A</option>
                                    @for($i =1; $i <=10;$i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-toggle="modal" class="btn btn-danger" data-target="#deleteCandidate">
                            Dis-Associate
                        </button>
                        <button id="associate_update" type="submit" class="btn btn-primary">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteCandidate" tabindex="-1" role="dialog"
         aria-labelledby="deleteCandidateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="positionCandidateDeleteForm" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCandidateTitle">{{__('Dis-Associate')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete <b><i class="title"></i></b> record, this procedure is irreversible.
                        </p>
                        <p>Do you want to proceed?</p>
                        {{ csrf_field() }}
                        <input type="hidden" id="delete_position_candidate_id" class="form-control"
                               name="delete_position_candidate_id">
                    </div>
                    <div class="modal-footer">
                        <button id="disassociate_cancel" type="button" class="btn btn-default" data-dismiss="modal">
                            Cancel
                        </button>
                        <button id="disassociate" type="submit" class="btn btn-danger btn-ok">Delete</button>
                    </div>
                </form>
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
    <script type="text/javascript">
        $(document).ready(function () {
            var status = '{{$position->status}}';
            var closed_date_section = $('#closed_date_section');

            if (status === 'closed' && closed_date_section.hasClass('hidden')) {
                $('#closed_date_section').removeClass('hidden');
            }
            if (status != 'closed' && !closed_date_section.hasClass('hidden')) {
                $('#closed_date_section').addClass('hidden');
            }
            $('#updateCandidate').on('shown.bs.modal', function (e) {
                e.preventDefault();
                var positions_candidates_id = $(e.relatedTarget).data('id');
                var candidate_name = $(e.relatedTarget).data('name');
                var interview_time = $(e.relatedTarget).data('interview_time');
                var candidate_status = $(e.relatedTarget).data('status');
                var comments = $(e.relatedTarget).data('comments');
                var notice_period = $(e.relatedTarget).data('notice_period');
                var visa_status = $(e.relatedTarget).data('visa_status');
                var number_years_experience = $(e.relatedTarget).data('number_years_experience');
                var reason_of_leaving = $(e.relatedTarget).data('reason_of_leaving');
                var communication_skills = $(e.relatedTarget).data('communication_skills');
                var position_rate = $(e.relatedTarget).data('position_rate');
                var completely_avoid = $(e.relatedTarget).data('completely_avoid');
                $(e.currentTarget).find('input[name="position_candidate_id"]').val(positions_candidates_id);
                $(e.currentTarget).find('input[name="candidate_name"]').val(candidate_name);
                $(e.currentTarget).find('input[name="interview_time"]').val(interview_time);
                $(e.currentTarget).find('select[name="candidate_status"]').val(candidate_status);
                $(e.currentTarget).find('textarea[name="comments"]').val(comments);
                $(e.currentTarget).find('textarea[name="notice_period"]').val(notice_period);
                $(e.currentTarget).find('textarea[name="visa_status"]').val(visa_status);
                $(e.currentTarget).find('textarea[name="number_years_experience"]').val(number_years_experience);
                $(e.currentTarget).find('textarea[name="reason_of_leaving"]').val(reason_of_leaving);
                $(e.currentTarget).find('textarea[name="communication_skills"]').val(communication_skills);
                position_rate ? $(e.currentTarget).find('select[name="rate"]').val(Math.round(position_rate)) : $(e.currentTarget).find('select[name="rate"]').val('');
                $(e.currentTarget).find('input[name="completely_avoid"][value="' + completely_avoid + '"] ').prop("checked", true);
            });

            $('#deleteCandidate').on('shown.bs.modal', function (e) {
                $('#updateCandidate').modal('hide')
            });
        });

        $('#add_interview_time').datepicker({
            format: 'yyyy-mm-dd',
            sideBySide: true
        });
        $('#add_interview_time').val('');

        $('#interview_time').datepicker({
            format: 'yyyy-mm-dd',
            sideBySide: true
        });
        $('#interview_time').val('');

        $('#closed_date').datepicker({
            format: 'yyyy-mm-dd',
            sideBySide: true
        });

        $('#disassociate').click(function () {
            var positions_candidates_id = $('#position_candidate_id').val();
            $('#positionCandidateDeleteForm').attr('action', "/position_candidate/remove/" + positions_candidates_id);
        });

        $('#associate_update').click(function () {
            var positions_candidates_id = $('#position_candidate_id').val();
            $('#positionCandidateUpdateForm').attr('action', "/position_candidate/" + positions_candidates_id);
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

        var findKey = function (obj, value) {
            var key = null;
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    if (obj[prop] === value) {
                        key = prop;
                    }
                }
            }
            return key;
        };

        var candidates = {
        @foreach($candidates as $k => $info)
        {{$k}}:
        '{{ $info }}',
        @endforeach
        }
        ;

        $('#add_candidate_name').change(function () {
            var candidate_name = $('#add_candidate_name').val();
            var candidate_id = findKey(candidates, candidate_name);
            $('#add_candidate_id').val(candidate_id);
        }).on('typeahead:selected', function () {
            var candidate_name = $('#add_candidate_name').val();
            var candidate_id = findKey(candidates, candidate_name);
            $('#add_candidate_id').val(candidate_id);
        });

        var substringMatcher = function (strs) {
            return function findMatches(q, cb) {
                var matches, substringRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function (i, str) {
                    if (substrRegex.test(str)) {
                        matches.push(str);
                    }
                });

                cb(matches);
            };
        };

        $('#candidate_typeahead .typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'candidates',
            source: substringMatcher(candidates)
        });

        $('#candidateList').DataTable({
            "pageLength": 25
        });

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