### PHP-SMTP-Mailer
This is a lightweight SMTP PHPMailer.<br>
The PHP Class supports TLS, SSL and File Attachments in mail.<br>
Simple, powerful and easy to use.

##### Features:
* Sends mail using one SMTP Server like 'smtp.gmail.com'.
* Auth login with username and password.
* Uses security protocols TLS and SSL.
* Supports 'text/html' or 'text/plain' messages.
* Supports any number of file attachments.
* Default Charset is 'UTF-8' but can be changed.
* 8bit, 7bit, Binary or Quoted-Printable transfer encoding.
* Logging of the transaction for debug.

##### Email Headers:
* From     - one email
* Reply-To - multiple possible
* To  - multiple possible
* Cc  - multiple possible
* Bcc - multiple possible

### Usage
1. Begin with running **setup_config.php**<br>
This will store your server connection settings.

2. After this you can try **example_minimal.php**<br>
It is a basic example like this:
```php
<?php

require 'class/SMTPMailer.php';
$mail = new SMTPMailer();

$mail->addTo('someaccount@hotmail.com');

$mail->Subject('Mail message for you');
$mail->Body(
    '<h3>Mail message</h3>
    This is a <b>html</b> message.<br>
    Greetings!'
);

if ($mail->Send()) echo 'Mail sent successfully';
else               echo 'Mail failure';

?>
```
