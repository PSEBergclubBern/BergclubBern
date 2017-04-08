<?php

function bcb_vorstand_table(){
    return \BergclubPlugin\MVC\View::render(__DIR__ . '/views', 'table', ['entries' => \BergclubPlugin\MVC\Models\User::findVorstand()]);
}

\BergclubPlugin\TagHelper::addTag('bcb_vorstand_table', 'bcb_vorstand_table');

function bcb_erweiterter_vorstand_table(){
    return \BergclubPlugin\MVC\View::render(__DIR__ . '/views', 'table', ['entries' => \BergclubPlugin\MVC\Models\User::findErweiterterVorstand()]);
}

\BergclubPlugin\TagHelper::addTag('bcb_erweiterter_vorstand_table', 'bcb_erweiterter_vorstand_table');

function bcb_leiter_table(){
    if(bcb_is_jugend()) {
        return \BergclubPlugin\MVC\View::render(__DIR__ . '/views', 'table', ['entries' => \BergclubPlugin\MVC\Models\User::findLeiterJugend()]);
    }
    return \BergclubPlugin\MVC\View::render(__DIR__ . '/views', 'table', ['entries' => \BergclubPlugin\MVC\Models\User::findLeiter()]);
}

\BergclubPlugin\TagHelper::addTag('bcb_leiter_table', 'bcb_leiter_table');
