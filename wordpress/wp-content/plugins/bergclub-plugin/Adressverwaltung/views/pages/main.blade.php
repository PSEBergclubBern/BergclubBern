@extends('template')

@section('content')
    {{ get_post_type_archive_link('touren') }}
    @if($showEdit)
    <p><a class="button-primary" href="?page={{ $_GET['page'] }}&view=new">Neuer Eintrag</a></p>
    @endif
    <table id="mitglieder" class="row-border stripe responsive no-link-underline" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th class="dt-left">Firma</th>
            <th class="dt-left">Name</th>
            <th class="dt-left">Strasse</th>
            <th class="dt-left">PLZ Ort</th>
            <th class="dt-left">Tel P</th>
            <th class="dt-left">Tel G</th>
            <th class="dt-left">Tel M</th>
            <th class="dt-left">Email</th>
            <th class="dt-left">Typ</th>
            <th class="dt-right no-sort"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->company }}</td>
                <td>{{ $user->last_name . ' ' . $user->first_name }}</td>
                <td>{{ $user->street }}</td>
                <td>{{ $user->zip . ' ' .$user->location }}</td>
                <td>{{ $user->phone_private }}</td>
                <td>{{ $user->phone_work }}</td>
                <td>{{ $user->phone_mobile }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->address_role_name }}</td>
                <td class="dt-right"><a href="?page={{ $_GET['page'] }}&view=detail&id={{ $user->ID }}" title="Anzeigen"><span class="dashicons dashicons-visibility"></span></a> @if($showEdit) <a class="delete" data-id="{{ $user->ID }}" title="Löschen" href="javascript:void(0)"><span class="dashicons dashicons-trash"></span></a> @endif</td>
            </tr>
        @endforeach
        </tbody>
@endsection

@section('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#mitglieder').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.13/i18n/German.json"
                },
                columnDefs: [
                    { targets: 'no-sort', orderable: false }
                ]
            });

            jQuery('.delete').click(function(){
                var id = jQuery(this).data('id');
                swal({
                    title: 'Sind Sie sicher?',
                    text: "Der Löschvorgang kann nicht Rückgängig gemacht werden",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ja, Löschen',
                    cancelButtonText: 'Abbrechen',
                }).then(function () {
                        document.location.href = document.location.href + '&action=delete&id=' + id;
                })
            });
        } );
    </script>
@endsection