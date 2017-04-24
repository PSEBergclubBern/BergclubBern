<div class="container-fluid grid-table">
    @forelse($entries as $entry)
        <div class="row row-header">
            <div class="col-sm-12">
                {{ $entry['title'] }}
            </div>
        </div>
        @foreach($entry['users'] as $user)
            <div class="row">
                <div class="col-sm-4">
                    {{ $user->name }}<br/>
                    {!! join('<br>', $user->address) !!}<br/>
                </div>
                <div class="col-sm-4">
                    @if(!empty($user->phone_private))
                        P: {{ $user->phone_private }}<br/>
                    @endif
                    @if(!empty($user->phone_work))
                        G: {{ $user->phone_work }}<br/>
                    @endif
                    @if(!empty($user->phone_mobile))
                        M: {{ $user->phone_mobile }}
                    @endif
                </div>
                <div class="col-sm-4">
                    {!! bcb_email($user->email)  !!}
                </div>
            </div>
        @endforeach
    @empty
        <p>Keine Daten vorhanden.</p>
    @endforelse
</div>