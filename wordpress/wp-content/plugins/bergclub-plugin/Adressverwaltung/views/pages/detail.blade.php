@extends('template')

@section('content')
<pre>{!! print_r($user, true) !!}</pre>
@endsection