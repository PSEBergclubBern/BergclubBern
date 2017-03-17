@extends('template')

@section('content')
    <table id="mitglieder" class="row-border stripe responsive" cellspacing="0" width="100%">
        <thead>
        <tr>
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
                <td>{{ $user->last_name . ' ' . $user->first_name }}</td>
                <td>{{ $user->street }}</td>
                <td>{{ $user->zip . ' ' .$user->location }}</td>
                <td>{{ $user->phone_private }}</td>
                <td>{{ $user->phone_work }}</td>
                <td>{{ $user->phone_mobile }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->type }}</td>
                <td class="dt-right"><a href="?page={{ $_GET['page'] }}&view=detail&id={{ $user->ID }}" title="Bearbeiten"><span class="dashicons dashicons-edit"></span></a></td>
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
        } );
    </script>
@endsection