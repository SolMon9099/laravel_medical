@extends('layouts.master')

@section('title', 'Create Role')

@section('content')
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Role Creation</h4>
                </div>

                <div class="card-body">                    
                    {!! Form::open(array('route' => 'roles.store','method'=>'POST', 'class'=>'add-new-role')) !!}
                    <div class="row">
                        <div class="col-md-12">
                            @include('layouts.error')
                        </div>

                        <div class="col-12">
                            <div class="mb-1">
                                <label class="form-label" for="name">Name</label>
                                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}                        
                            </div>
                        </div>                        
                        
                        <div class="col-6 mb-1 mb-md-0">
                            <h4 class="mt-1 mb-1">Role Permissions</h4>
                                                                         
                            <div class="row" style="padding-left:15px;">
                                @foreach($permission as $value)
                                    <div class="form-check col-3 mb-1">
                                        <input class="form-check-input" name="permission[]" type="checkbox" id="{{$value->id}}" value={{$value->id}} />
                                        <label class="form-check-label text-capitalize" for="{{$value->id}}"> {{ str_replace('-', ' ',$value->name) }} </label>                                       
                                    </div>                                    
                                    <br>
                                @endforeach        
                            </div>                    
                        </div>      
                        
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Save</button>
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary waves-effect">Back</a>
                        </div>
                        
                        
                    </div>
                    {!! Form::close() !!}
                </div>                
            </div>
        </div>
    </div>        
</section>
@endsection