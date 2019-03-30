@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{  __('Add Recruiter')}}</div>
                    <div class="card-body">
                        <form action="{{route('recruiterCreated')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Recruiter Name *')}}</label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           name="name" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
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
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{  __('Recruiter List')}}</div>
                    <div class="card-body">
                        @if (session('recruiter_success'))
                            <div class="alert alert-success">
                                {{ session('recruiter_success') }}
                            </div>
                        @endif
                        <div class="anyClass">
                            <table id="recruiterList" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('ID')}}</th>
                                    <th>{{ __('Recruiter Name')}}</th>
                                    <th>{{ __('Create Time')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recruiters as $recruiter)
                                    <tr>
                                        <td>{{ $recruiter->id  }}</td>
                                        <td>{{ $recruiter->name }}</td>
                                        <td>{{ date_format((new DateTime($recruiter->created_at))->setTimezone(new DateTimeZone('Australia/Melbourne')),'Y-m-d H:i:s') }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#updateRecruiter" data-id={{$recruiter->id}}
                                                    data-name="{{$recruiter->name}}">{{ __('Edit Recruiter')}} </button>
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
    <div class="modal fade" id="updateRecruiter" tabindex="-1" role="dialog"
         aria-labelledby="updateRecruiterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="recruiterUpdateForm" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateRecruiterTitle">{{ __('Update Recruiter')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Recruiter Name *')}}</label>
                            <div class="col-md-6">
                                <input class="form-control" name="name" required>
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
    <script>
        $(document).ready(function () {
            $('#updateRecruiter').on('shown.bs.modal', function (e) {
                e.preventDefault();
                var recruiter_id = $(e.relatedTarget).data('id');
                var recruiter_name = $(e.relatedTarget).data('name');
                $(e.currentTarget).find('input[name="name"]').val(recruiter_name);
                $('#recruiterUpdateForm').attr('action', "/recruiter/" + recruiter_id);
            });
        });
        $('#recruiterList').DataTable();
        $('.dataTables_length').addClass('bs-select');
    </script>
@endsection
