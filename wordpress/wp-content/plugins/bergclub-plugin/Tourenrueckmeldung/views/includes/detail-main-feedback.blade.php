@if( $edit )

    @if( $tour != null )

        <form method="post">

            @include('includes.tourenrueckmeldung-form')

            <input type="submit" value="Freigeben!" />

        </form>

    @else
        <p>Die ausgewählte Tour konnte nicht gefunden werden.</p>
    @endif

@else

    @if( $tours != null )

        @include('includes.tour-overview')

    @else
        <p>Momentan gibt es keine Touren, welche überprüft werden müssen.</p>

    @endif

@endif

