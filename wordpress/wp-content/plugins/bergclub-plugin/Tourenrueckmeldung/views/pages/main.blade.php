@extends('template')

@section('content')
    <h2 class="nav-tab-wrapper">
            <a href="?page={{ $_GET['page'] }}&view=main&tab=tab1" class="nav-tab @if($tab=='tab1') nav-tab-active @endif ">Tab1</a>
            <a href="?page={{ $_GET['page'] }}&view=main&tab=tab2" class="nav-tab @if($tab=='tab2') nav-tab-active @endif ">Tab2</a>
            <a href="?page={{ $_GET['page'] }}&view=detail&tab=tab3" class="nav-tab @if($tab=='tab3') nav-tab-active @endif ">Tab3</a>
    </h2>
    <div class="container">
        @include($tab_file)
    </div>
@endsection