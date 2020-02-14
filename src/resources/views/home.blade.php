@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
<form action="#" v-cloak>
    <web-editor
        :options="{fontSize: 14}"
        >---
receipt: Oz-Ware Purchase
Invoice date: 2007-08-06

customer:
    given: Dorothy
    family: Gale</web-editor>
</form>

@endsection
