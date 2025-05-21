<?php
class PDO_htmlspecialchars extends PDOStatement {
    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, mixed ...$args): array {
        $fetchAll = parent::fetchAll($mode, ...$args);
        foreach ($fetchAll as $key => $value) {
            if (is_object($value)) {
                $fetchAll[$key] = (array)$value;
            }
            foreach ($fetchAll[$key] as $keys => $value) {
                if ($value !== null){
                    $fetchAll[$key][$keys] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                }                
            }
        }
        return $fetchAll;
    }
    public function fetch(int $mode = PDO::FETCH_ASSOC, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed {
        $fetch = parent::fetch($mode, $cursorOrientation, $cursorOffset);
        if (!$fetch) {
            return false;
        }
        foreach ($fetch as $key => $value) {
            if ($value !== null)
                $fetch[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        return (object) $fetch;
    }
}
