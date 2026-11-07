<?php

class verifica
{
    function leesesion()
    {
        if (empty($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['nivel'])) {
            $s = $_SESSION['nivel'];
        } else {
            $s = "";
        }

        return $s;
    }

    function destruyesesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();
        session_destroy();
        header("Location: ?pagina=login");

        exit;
    }
}

?>