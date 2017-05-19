@if( $edit )

    @if( $rueckmeldung != null )
        <form method="post">

            @include('includes.tourenrueckmeldung-form')

            <p><input type="submit" class="button button-primary" value="Freigabe beantragen"/></p>

        </form>


    @else
        <p>Die ausgewählte Tour konnte nicht gefunden werden.</p>
    @endif

@else

    @if( $tours != null )

        @include('includes.tour-overview')

    @else
        <p>Momentan gibt es keine Touren, welche zurückgemeldet werden müssen.</p>
    @endif

@endif








