Modifie php.ini dans xampp : C:\xampp\php : 

[mail function]
; For Win32 only.
; https://php.net/smtp
;SMTP=localhost
; https://php.net/smtp-port
;smtp_port=25

SMTP=smtp.gmail.com
smtp_port=587
sendmail_from = ynovmailoff@gmail.com
sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"

----------------------------------------------------------------------

Modifie sendmail.ini dans xampp : C:\xampp\sendmail : 

smtp_server=smtp.gmail.com
smtp_port=587
error_logfile=error.log
debug_logfile=debug.log
auth_username=ynovmailoff@gmail.com
auth_password=azkkssxmkrjslbog
force_sender=ynovmailoff@gmail.com

