<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["user"]["id"])) {
    if (!isset($_COOKIE["identification"])) {
        setcookie("identification", $_SESSION["user"]["id"], time() + 30 * 24 * 60 * 60, "/");
    }
} elseif (!isset($_SESSION["user"]["id"]) && isset($_COOKIE["identification"])) {
    recoverInfo($_COOKIE["identification"]);
}
?>