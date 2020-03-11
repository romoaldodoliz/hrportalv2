@extends('layouts.user-view-app')

    @section('content')
        <userprofileview useremployeeid="{{ $employee_id }}" url="{{ url('/')}}"></userprofileview>
    @endsection

    @push('js')

@endpush
