<?php
function prepareWhere($busca) {
    $whereParts = [];
    $params = [];

    $ignore = ["orderBy", "direction", "offset", "limit"];

    foreach ($busca as $key => $value) {
        if (in_array($key, $ignore)) {
            continue;
        }

        $whereParts[] = "$key = :$key";
        $params[":$key"] = $value;
    }

    return implode(" AND ", $whereParts);
}
