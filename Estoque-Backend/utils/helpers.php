<?php
function prepareWhere(array $filtros): string {
    $where = [];
    foreach ($filtros as $coluna => $valor) {
        $where[] = "$coluna = :$coluna";
    }
    return $where ? "WHERE " . implode(" AND ", $where) : "";
}
