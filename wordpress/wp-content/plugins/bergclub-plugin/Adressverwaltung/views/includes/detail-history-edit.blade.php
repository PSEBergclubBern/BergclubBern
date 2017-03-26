<form method="post">
    <table id="history">
        <thead>
        <tr>
            <th>Rolle</th>
            <th>von</th>
            <th>bis</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user->history as $key => $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td><input type="text" class="datepicker" name="history[{{ $key }}][date_from]" value="{{ $item['date_from'] }}"></td>
                <td>
                    @if(!empty($item['date_to']))
                        <input type="text" class="datepicker" name="history[{{ $key }}][date_to]" value="{{ $item['date_to'] }}">
                    @endif
                </td>
                <td class="dt-right no-link-underline">
                    @if(!empty($item['date_to']))
                    <a class="delete" data-key="{{ $key }}" title="Löschen" href="javascript:void(0)"><span class="dashicons dashicons-trash"></span></a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</form>

<script type="text/javascript">
    jQuery(".delete").click(function(){
        var key = jQuery(this).data('key');
        swal({
            title: 'Sind Sie sicher?',
            text: "Der Löschvorgang kann nicht Rückgängig gemacht werden",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ja, Löschen',
            cancelButtonText: 'Abbrechen'
        }).then(function () {
            document.location.href = document.location.href + '&action=delete&key=' + key;
        })
    });
</script>