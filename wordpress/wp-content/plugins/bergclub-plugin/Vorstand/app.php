<?php

/**
 * Returns a rendered view for the "Vorstand" table.
 *
 * @see \BergclubPlugin\MVC\View
 *
 * @return string the "Vorstand" table.
 */
function bcb_vorstand_table()
{
    return \BergclubPlugin\MVC\View::render(__DIR__ . '/views', 'table', ['entries' => \BergclubPlugin\MVC\Models\User::findVorstand()]);
}

// assigning the tag [bcb_vorstand_table] to the bcb_vorstand_table function.
\BergclubPlugin\TagHelper::addTag('bcb_vorstand_table', 'bcb_vorstand_table');

/**
 * Returns a rendered view for the "Erweiterter Vorstand" table.
 *
 * @see \BergclubPlugin\MVC\View
 *
 * @return string the "Erweiterter Vorstand" table.
 */
function bcb_erweiterter_vorstand_table()
{
    return \BergclubPlugin\MVC\View::render(__DIR__ . '/views', 'table', ['entries' => \BergclubPlugin\MVC\Models\User::findErweiterterVorstand()]);
}

// assigning the tag [bcb_erweiterter_vorstand_table] to bcb_erweiterter_vorstand_table function.
\BergclubPlugin\TagHelper::addTag('bcb_erweiterter_vorstand_table', 'bcb_erweiterter_vorstand_table');

/**
 * Returns a rendered view for the "Leiter" table.
 * Distinguishes between "BCB" / "BCB Jugend" mode and uses the corresponding data.
 *
 * @see \BergclubPlugin\MVC\View
 *
 * @return string the "Vorstand" table.
 */
function bcb_leiter_table()
{
    if (bcb_is_jugend()) {
        return \BergclubPlugin\MVC\View::render(__DIR__ . '/views', 'table', ['entries' => \BergclubPlugin\MVC\Models\User::findLeiterJugend()]);
    }
    return \BergclubPlugin\MVC\View::render(__DIR__ . '/views', 'table', ['entries' => \BergclubPlugin\MVC\Models\User::findLeiter()]);
}

// assigning the tag [bcb_leiter_table] to bcb_leiter_table function.
\BergclubPlugin\TagHelper::addTag('bcb_leiter_table', 'bcb_leiter_table');
