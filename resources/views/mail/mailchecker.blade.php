<?php
$mailbox = new PhpImap\Mailbox(
    '{imap.gmail.com:993/imap/ssl}INBOX', // IMAP server and mailbox folder
    'bastrucksmail@gmail.com', // Username for the before configured mailbox
    'B4STr5cks12#', // Password for the before configured username
    __DIR__, // Directory, where attachments will be saved (optional)
    'US-ASCII' // Server encoding (optional)
);
?>
