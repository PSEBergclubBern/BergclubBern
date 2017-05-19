<h1>{{ $title }}</h1>

{!! \BergclubPlugin\FlashMessage::show() !!}

<div class="wrapper">
    @yield('content')
</div>

@yield('scripts')