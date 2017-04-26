@extends('template')

@section('content')
    <h2 class="nav-tab-wrapper">
        @foreach($tabs as $key => $tabName)
            <a href="?page={{ $_GET['page'] }}&view=main&tab={{ $key }}" class="nav-tab @if($tab==$key) nav-tab-active @endif ">{{ $tabName }}</a>
        @endforeach
    </h2>
    <div class="container">
        @include($tab_file)
        @if(isset($edit) && $edit)
            <p><a class="button button-primary" href="?page={{ $_GET['page'] }}&tab={{ $_GET['tab'] }}">Zurück</a></p>
        @endif
    </div>

@endsection