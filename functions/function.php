<?php

    // エスケープ処理
    function escape($char) {
        return htmlspecialchars($char, ENT_QUOTES);
    }

?>