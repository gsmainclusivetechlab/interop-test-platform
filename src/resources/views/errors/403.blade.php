@extends('layouts.errors')

@section('title', __('403 Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'We are sorry but you do not have permission to access this page'))
