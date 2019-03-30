@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{  __('Add Role')}}</div>
                    <div class="card-body">
                        <form action="{{route('roleCreated')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Role Name *')}}</label>
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
                    <div class="card-header">{{  __('Role List')}}</div>
                    <div class="card-body">
                        @if (session('role_success'))
                            <div class="alert alert-success">
                                {{ session('role_success') }}
                            </div>
                        @endif
                        <div class="anyClass">
                            <table id="roleList" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('ID')}}</th>
                                    <th>{{ __('Role Name')}}</th>
                                    <th>{{ __('Create Time')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role->id  }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ date_format((new DateTime($role->created_at))->setTimezone(new DateTimeZone('Australia/Melbourne')),'Y-m-d H:i:s') }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#updateRole" data-id={{$role->id}}
                                                    data-name="{{$role->name}}">{{ __('Edit Role')}} </button>
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
    <div class="modal fade" id="updateRole" tabindex="-1" role="dialog"
         aria-labelledby="updateRoleTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="roleUpdateForm" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateRoleTitle">{{ __('Update Role')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Role Name *')}}</label>
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
            $('#updateRole').on('shown.bs.modal', function (e) {
                e.preventDefault();
                var role_id = $(e.relatedTarget).data('id');
                var role_name = $(e.relatedTarget).data('name');
                $(e.currentTarget).find('input[name="name"]').val(role_name);
                $('#roleUpdateForm').attr('action', "/role/" + role_id);
            });
        });
        $('#roleList').DataTable();
        $('.dataTables_length').addClass('bs-select');
    </script>
@endsection