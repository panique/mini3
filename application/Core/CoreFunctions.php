<?php

namespace Mini\Controller;

function view(string $file, array $data = [])
{
    extract($data);
    require sprintf('%sview/%s.php', APP, $file);
}

function redirect($path) {
    header('location: ' . URL . $path);
}
