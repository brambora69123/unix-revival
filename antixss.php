<?php
function nx($input) {
    if (!is_string($input)) {
        return '';
    }
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}
?>
