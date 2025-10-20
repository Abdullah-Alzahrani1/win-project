<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'win');
if (!$conn) {
    echo "Error: " . mysqli_connect_error();
}

