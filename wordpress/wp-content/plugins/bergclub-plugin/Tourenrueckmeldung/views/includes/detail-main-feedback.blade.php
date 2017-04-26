@if( $edit )

    @if( $rueckmeldung != null )

        <form method="post">

            @include('includes.tourenrueckmeldung-form')

            <p><input type="submit" class="button button-primary" value="Freigeben" /></p>

        </form>

    @else
        <p>Die Rückmeldung konnte nicht gefunden werden.</p>
    @endif

@else

    @if( $tours != null )

        @include('includes.tour-overview')

    @else
        <p>Momentan gibt es keine Touren, welche überprüft werden müssen.</p>

    @endif

@endif

