@extends('layouts.master')

@section('title','Role Management')

@section('content')   
    <!-- table -->
    <div class="card">
        <div class="table-responsive">
            <table class="role-list-table table">
                <thead class="table-light">
                    <tr>
                        <th></th>                        
                        <th>Name</th>                        
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
                            @can('role-edit')
                                <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                            @endcan
                            @can('role-delete')
                                {!! Form::open(['method' => 'DELETE', 'class' => 'delete_form', 'route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
        <!-- table -->
@endsection

@section('page-script')


@endsection