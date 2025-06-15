@extends('layouts/default')

@php
use Illuminate\Support\Str;
@endphp

{{-- Page title --}}
@section('title')
    Round Orders
    @parent
@stop

@section('header_right')
    <a href="{{ route('round-orders.create') }}" class="btn btn-primary pull-right">
        {{ trans('general.create') }}</a>
@stop

{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Round Orders</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Technician</th>
                                    <th>Department</th>
                                    <th>Description</th>
                                    <th>Checklist</th>
                                    <th>Attachment</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roundOrders as $index => $roundOrder)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $roundOrder->innerLocation->name ?? 'N/A' }}</td>
                                        <td>{{ $roundOrder->type }}</td>
                                        <td>
                                            <span class="label label-{{ $roundOrder->status === 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($roundOrder->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $roundOrder->technicianUser->first_name }} {{ $roundOrder->technicianUser->last_name }}</td>
                                        <td>{{ $roundOrder->departmentData->name }}</td>
                                        <td>{{ $roundOrder->description }}</td>
                                        <td>{{ App\Models\Checklists::find($roundOrder->checklist)->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($roundOrder->attachment)
                                                <img src="{{ asset($roundOrder->attachment) }}" 
                                                     alt="Attachment" 
                                                     style="max-width: 100px; max-height: 100px; cursor: pointer;" 
                                                     onclick="window.open('{{ asset( $roundOrder->attachment) }}', '_blank')">
                                            @else
                                                No Attachment
                                            @endif
                                        </td>
                                        <td>{{ $roundOrder->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            {{-- <a href="{{ route('round-orders.show', $roundOrder->id) }}" class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a> --}}
                                            <a href="{{ route('round-orders.edit', $roundOrder->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <form action="{{ route('round-orders.destroy', $roundOrder->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $roundOrders->links() }}
                </div>
            </div>
        </div>
    </div>
@stop 