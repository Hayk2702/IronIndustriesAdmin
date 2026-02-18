@extends('layouts.app')

@section('content')
    <main-component
            :locale="'{{app()->getLocale()}}'"
            :authUser="{{Auth::user()}}"
            :roles="{{Auth::user()->roles}}"
    />
@endsection
