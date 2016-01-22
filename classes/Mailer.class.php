<?php

class Mailer {
    private $_headers;

    public function __construct() {
        $this->_headers =
        "From: camagrustaff@email.com".
        "\r\n"."Reply-To: camagrustaff@email.com".
        "\r\n"."X-Mailer: PHP/".phpversion().
        "\r\n"."MIME-Version: 1.0".
        "\r\n"."Content-type: text/html; charset=iso-8859-1";
    }

    public function send($to, $subject, $message) {
        $headers = $this->_headers;

        return mail($to, $subject, $message, $headers);
    }
}
 ?>