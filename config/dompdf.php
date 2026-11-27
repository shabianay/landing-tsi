<?php

/*
 * Only the keys that differ from barryvdh/laravel-dompdf's defaults; the rest is merged in
 * from the package config. The web root here is public_html (see bootstrap/app.php), and
 * dompdf otherwise assumes base_path('public'), which does not exist on the server.
 */
return [
    'public_path' => public_path(),
];
