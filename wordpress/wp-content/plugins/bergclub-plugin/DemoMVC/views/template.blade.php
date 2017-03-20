<h1>{{ $title }}</h1>

{!! \BergclubPlugin\FlashMessage::show() !!}

<div class="container pull-left">
        @yield('content')
</div>