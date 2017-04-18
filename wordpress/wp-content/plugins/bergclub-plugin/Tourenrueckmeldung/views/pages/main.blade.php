@extends('template')

@section('content')
    <h2 class="nav-tab-wrapper">
            <a href="?page={{ $_GET['page'] }}&view=main&tab=nofeedback" class="nav-tab @if($tab=='nofeedback') nav-tab-active @endif ">Keine Rückmeldung</a>
            <a href="?page={{ $_GET['page'] }}&view=main&tab=feedback" class="nav-tab @if($tab=='feedback') nav-tab-active @endif ">Erfasst</a>
            <a href="?page={{ $_GET['page'] }}&view=detail&tab=approved" class="nav-tab @if($tab=='approved') nav-tab-active @endif ">Freigegeben</a>
    </h2>
    <div class="container">
        @include($tab_file)
    </div>
@endsection