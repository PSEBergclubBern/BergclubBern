@extends('template')

@section('content')
    <form method="post">
        <div class="container">
            @include($tab_file)
            <div class="action-buttons">
                <input type="submit" class="button-primary" value="Speichern"/>
                <a class="button-secondary" href="?page={{ $_GET['page'] }}">Abbrechen</a>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @include('js.data')
@endsection