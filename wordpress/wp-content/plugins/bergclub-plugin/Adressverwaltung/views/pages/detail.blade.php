@extends('template')

@section('content')
    @if($edit)
        <form method="post">
            @endif
            <h2 class="nav-tab-wrapper">
                @if($tab=='data' || !$edit)
                    <a href="?page={{ $_GET['page'] }}&view=detail&id={{ $_GET['id'] }}" class="nav-tab @if($tab=='data') nav-tab-active @endif ">Daten</a>
                @endif
                @if($tab=='functions' || !$edit)
                    <a href="?page={{ $_GET['page'] }}&view=detail&tab=functions&id={{ $_GET['id'] }}" class="nav-tab @if($tab=='functions') nav-tab-active @endif ">Funktionen</a>
                @endif
                @if($tab=='history' || !$edit)
                    <a href="?page={{ $_GET['page'] }}&view=detail&tab=history&id={{ $_GET['id'] }}" class="nav-tab @if($tab=='history') nav-tab-active @endif ">Historie</a>
                @endif
            </h2>
            <div class="container">
                @include($tab_file)
                <div class="action-buttons">
                    @if($edit)
                        <input type="submit" class="button-primary" value="Speichern"/>
                        <a class="button-secondary" href="?page={{ $_GET['page'] }}&view={{ $_GET['view'] }}&tab={{ $tab }}&id={{ $_GET['id'] }}">Abbrechen</a>
                    @else
                        <a class="button-primary" href="?page={{ $_GET['page'] }}&view={{ $_GET['view'] }}&tab={{ $tab }}&id={{ $_GET['id'] }}&edit=1">Bearbeiten</a>
                    @endif
                </div>
            </div>
            @if($edit)
        </form>
    @endif
    <a class="button-primary" href="?page={{ $_GET['page'] }}">&laquo; Zurück</a>
@endsection