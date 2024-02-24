<?php
// v.1.15-L
if (
    // required inputs
    isset($_POST['Name-input']) && isset($_POST['Email-input']) && isset($_POST['Phone-input']) && isset($_POST['Contact-1-Message'])
    // honeypot
    && isset($_POST['stdmail']) && empty($_POST['stdmail'])
) {
    // mail configurations / komu se to posílá 1. krok uprav
    // $recipientTo = ['office@tcfcap.com'];
    $recipientTo = ['janina.kasova2@gmail.com'];
    // $recipientCc = ['jan.korecky@rvlt.digital', 'josef.havlik@rvlt.digital', 'tereza.stachova@rvlt.digital']; skrytá kopie 2. krok uprav
    $recipientBcc = ['gabriel.stebelova@rvlt.digital' ];

    $senderEmail = 'no-reply@janakase.cz';
    $senderName = '';
    $subject = 'Kontakt web formulář';

    // post data
    $attachments = $_FILES['files'] ?? [];
    $dataEmail = $_POST['Email-input'] ?? '';
    $dataEmail = trim($dataEmail);
    $dataPhone = $_POST['Phone-input'] ?? '';
    $dataPhone = trim($dataPhone);

    // body / message
    $dataBody = 'Jméno: ' . ($_POST['Name-input'] ?? '') . "\n";
    if (!empty($dataEmail)) {
        $dataBody .= 'Email: ' . $dataEmail . "\n";
    }

    if (!empty($dataPhone)) {
        $dataBody .= 'Telefon: ' . $dataPhone . "\n";
    }

    $dataBody .= 'Zpráva: ' . ($_POST['Contact-1-Message'] ?? '') . "\n";
    if (isset($attachments['name'])) {
        $attachmentsCount = count($attachments['name']);
        for ($i = 0; $i < $attachmentsCount; $i++) {    // unset "empty" files
            if (empty($attachments['name'][$i])) {
                unset($attachments['name'][$i]);
            }
        }

        $attachmentsCount = count($attachments['name']);
    } else {
        $attachmentsCount = 0;
    }

    // headers
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "From: =?utf-8?b?" . base64_encode($senderName) . "?= <" . $senderEmail . ">";
    if (!empty($dataEmail)) {
        $headers[] = "Reply-To: " . trim($dataEmail);
    }

    if (!empty($recipientCc)) {
        $headers[] = 'Cc: ' . implode(', ', $recipientCc);
    }

    if (!empty($recipientBcc)) {
        $headers[] = 'Bcc: ' . implode(', ', $recipientBcc);
    }

    $headers[] = "X-Mailer: PHP/" . phpversion();

    if ($attachmentsCount > 0) { // if any attachment exists
        //header
        $boundary = md5("rvlt_digital");
        $headers[] = "Content-Type: multipart/mixed; boundary = $boundary\r\n";

        //body
        $body[] = "--$boundary";
        $body[] = "Content-Type: text/plain;charset=utf-8";
        $body[] = "Content-Transfer-Encoding: base64\r\n";
        $body[] = chunk_split(base64_encode($dataBody));

        //attachments
        for ($i = 0; $i < $attachmentsCount; $i++) {
            if (!empty($attachments['name'][$i])) {
                if ($attachments['error'][$i] > 0) { //exit script and output error if we encounter any
                    $errors = [
                        1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
                        2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
                        3 => "The uploaded file was only partially uploaded",
                        4 => "No file was uploaded",
                        6 => "Missing a temporary folder",
                    ];

                    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
                    echo $errors[$attachments['error'][$i]];
                    exit(1);
                }

                //get file info
                $fileName = $attachments['name'][$i];
                $fileSize = $attachments['size'][$i];
                $fileType = $attachments['type'][$i];

                //read file & encode
                $handle = fopen($attachments['tmp_name'][$i], "r");
                $content = fread($handle, $fileSize);
                fclose($handle);

                $fileEncoded = chunk_split(base64_encode($content));

                $body[] = "--$boundary";
                $body[] = "Content-Type: $fileType; name=" . $fileName;
                $body[] = "Content-Disposition: attachment; filename=" . $fileName;
                $body[] = "Content-Transfer-Encoding: base64";
                $body[] = "X-Attachment-Id: " . rand(1000, 99999) . "\r\n";
                $body[] = $fileEncoded;
            }
        }
    } else {
        //header
        $headers[] = "Content-Type: text/plain;charset=utf-8";
        $body[] = $dataBody;
    }

    $sentResult = @mail(
        implode(', ', $recipientTo),
        $subject,
        implode("\r\n", $body),
        implode("\r\n", $headers)
    );

    if ($sentResult) {
        echo 'success';
        exit;
    }
}

header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
exit(1);
