@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ $roundOrder->exists ? 'Edit Round Order' : 'Create Round Order' }}
    @parent
@stop

@section('header_right')
    <a href="{{ route('round-orders.index') }}" class="btn btn-primary pull-right">
        {{ trans('general.back') }}</a>
@stop

{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $roundOrder->exists ? 'Edit Round Order' : 'Create Round Order' }}</h3>
                </div>
                <div class="box-body">
                    <form class="form-horizontal" method="post" action="{{ $roundOrder->exists ? route('round-orders.update', $roundOrder->id) : route('round-orders.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if($roundOrder->exists)
                            @method('PUT')
                        @endif

                        <!-- Location -->
                        <div class="form-group {{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="col-md-3 control-label">Location</label>
                            <div class="col-md-7">
                                <select class="form-control" name="location" id="location">
                                    <option value="">Select a location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ (old('location', $roundOrder->location) == $location->id) ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('location', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="type" class="col-md-3 control-label">Type</label>
                            <div class="col-md-7">
                                <select class="form-control" name="type" id="type">
                                    <option value="">Select a type</option>
                                    <option value="Daily" {{ old('type', $roundOrder->type) == 'Daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="Weekly" {{ old('type', $roundOrder->type) == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="Monthly" {{ old('type', $roundOrder->type) == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="3 Month" {{ old('type', $roundOrder->type) == '3 Month' ? 'selected' : '' }}>3 Month</option>
                                    <option value="6 Month" {{ old('type', $roundOrder->type) == '6 Month' ? 'selected' : '' }}>6 Month</option>
                                </select>
                                {!! $errors->first('type', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        </div>

                        <!-- Checklist -->
                        <div class="form-group {{ $errors->has('checklist') ? ' has-error' : '' }}">
                            <label for="checklist" class="col-md-3 control-label">Checklist</label>
                            <div class="col-md-7">
                                <select class="form-control" name="checklist" id="checklist">
                                    <option value="">Select a checklist</option>
                                    @foreach($checklists as $checklist)
                                        <option value="{{ $checklist->id }}" {{ (old('checklist', $roundOrder->checklist) == $checklist->id) ? 'selected' : '' }}>
                                            {{ $checklist->name }} 
                                        </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('checklist', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-3 control-label">Description</label>
                            <div class="col-md-7">
                                <textarea class="form-control" name="description" id="description" rows="3">{{ old('description', $roundOrder->description) }}</textarea>
                                {!! $errors->first('description', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        </div>

                        <!-- Attachment -->
                        <div class="form-group {{ $errors->has('attachment') ? ' has-error' : '' }}">
                            <label for="attachment" class="col-md-3 control-label">Attachment</label>
                            <div class="col-md-7">
                                @if($roundOrder->attachment)
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fas fa-paperclip"></i>
                                        </span>
                                        <input type="text" class="form-control" value="{{ basename($roundOrder->attachment) }}" readonly>
                                    </div>
                                    <p class="help-block">Current file: {{ basename($roundOrder->attachment) }}</p>
                                @endif
                                <input type="file" name="attachment" id="attachment">
                                {!! $errors->first('attachment', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-md-3 control-label">Status</label>
                            <div class="col-md-7">
                                <select class="form-control" name="status" id="status">
                                    <option value="pending" {{ old('status', $roundOrder->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status', $roundOrder->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $roundOrder->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                {!! $errors->first('status', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        </div>

                        <!-- Technician -->
                        <div class="form-group {{ $errors->has('technician') ? ' has-error' : '' }}">
                            <label for="technician" class="col-md-3 control-label">Technician</label>
                            <div class="col-md-7">
                                <select class="form-control" name="technician" id="technician">
                                    <option value="">Select a technician</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (old('technician', $roundOrder->technician) == $user->id) ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('technician', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="form-group {{ $errors->has('department') ? ' has-error' : '' }}">
                            <label for="department" class="col-md-3 control-label">Department</label>
                            <div class="col-md-7">
                                <select class="form-control" name="department" id="department">
                                    <option value="">Select a department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ (old('department', $roundOrder->department) == $department->id) ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('department', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="text-left col-md-6">
                                <a class="btn btn-link" href="{{ route('round-orders.index') }}">{{ trans('button.cancel') }}</a>
                            </div>
                            <div class="text-right col-md-6">
                                <button type="submit" class="btn btn-success"><i class="fas fa-check icon-white" aria-hidden="true"></i> {{ trans('general.save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop 