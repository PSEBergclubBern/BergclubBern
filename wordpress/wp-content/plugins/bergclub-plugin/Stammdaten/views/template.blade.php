<h1>{{ $title }}</h1>

{!! \BergclubPlugin\FlashMessage::show() !!}

<div class="content">
    @yield('content')
</div>

@yield('script')