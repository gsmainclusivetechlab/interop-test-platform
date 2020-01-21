@extends('layouts.errors')

@section('title', __('503 Service Unavailable'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'We are sorry but the service is temporarily unavailable'))
