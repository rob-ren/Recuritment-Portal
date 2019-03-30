@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{  __('Add User')}}</div>
                    <div class="card-body">
                        <form action="{{route('userCreated')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('User Name *')}}</label>
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
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Email *')}}</label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" required autofocus>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Password *')}}</label>
                                <div class="col-md-6">
                                    <input type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password" required autofocus>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Re-type Password *')}}</label>
                                <div class="col-md-6">
                                    <input type="password"
                                           class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                           name="password_confirmation" required autofocus>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Role *')}}</label>
                                <div class="col-md-6">
                                    <select id="add_user_role"
                                            class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}"
                                            name="role">
                                        @foreach($user_role_array as $key=>$user_role)
                                            <option value="{{$key}}">{{$user_role}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">{{ __('Status') }}</label>
                                <div class="col-md-6 form-check-inline">
                                    <div class="form-check">
                                        <input class="form-check-input" name="status" type="radio" id="activate"
                                               value="1" checked>
                                        <label class="form-check-label" for="activate">{{ __('Active') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" name="status" type="radio" id="inactivate"
                                               value="0">
                                        <label class="form-check-label" for="inactivate">{{ __('Inactive') }}</label>
                                    </div>
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
                    <div class="card-header">{{  __('User List')}}</div>
                    <div class="card-body">
                        @if (session('user_success'))
                            <div class="alert alert-success">
                                {{ session('user_success') }}
                            </div>
                        @endif
                        <div class="anyClass">
                            <table id="userList" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('User Name')}}</th>
                                    <th>{{ __('Email')}}</th>
                                    <th>{{ __('Role')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th>{{ __('Create Time')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role? $user_role_array[$user->role]:null }}</td>
                                        <td>{{ $user->status? 'Active':'Inactive' }}</td>
                                        <td>{{ date_format((new DateTime($user->created_at))->setTimezone(new DateTimeZone('Australia/Melbourne')),'Y-m-d H:i:s') }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#updateUser" data-id="{{$user->id}}"
                                                    data-name="{{$user->name}}" data-email="{{$user->email}}"
                                                    data-role="{{$user->role}}" data-status="{{$user->status}}"
                                            >{{ __('Edit User')}} </button>
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
    <div class="modal fade" id="updateUser" tabindex="-1" role="dialog"
         aria-labelledby="updateUserTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="userUpdateForm" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateUserTitle">{{ __('Update User')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('User Name *')}}</label>
                            <div class="col-md-6">
                                <input class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Email *')}}</label>
                            <div class="col-md-6">
                                <input class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Role *')}}</label>
                            <div class="col-md-6">
                                <select id="update_user_role" class="form-control" name="role">
                                    @foreach($user_role_array as $key=>$user_role)
                                        <option value="{{$key}}">{{$user_role}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-md-right">{{ __('Status') }}</label>
                            <div class="col-md-6 form-check-inline">
                                <div class="form-check">
                                    <input class="form-check-input" name="status" type="radio" id="update_activate"
                                           value="1">
                                    <label class="form-check-label" for="update_activate">{{ __('Active') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="status" type="radio" id="update_inactivate"
                                           value="0">
                                    <label class="form-check-label" for="update_inactivate">{{ __('Inactive') }}</label>
                                </div>
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
            $('#updateUser').on('shown.bs.modal', function (e) {
                e.preventDefault();
                var user_id = $(e.relatedTarget).data('id');
                var user_name = $(e.relatedTarget).data('name');
                var email = $(e.relatedTarget).data('email');
                var role = $(e.relatedTarget).data('role');
                var status = $(e.relatedTarget).data('status');
                $(e.currentTarget).find('input[name="name"]').val(user_name);
                $(e.currentTarget).find('input[name="email"]').val(email);
                $(e.currentTarget).find('select[name="role"]').val(role);
                $(e.currentTarget).find('input[name="status"][value="' + status + '"]').prop("checked", true);
                $('#userUpdateForm').attr('action', "/users/" + user_id);
            });
        });
        $('#userList').DataTable();
        $('.dataTables_length').addClass('bs-select');
    </script>
@endsection