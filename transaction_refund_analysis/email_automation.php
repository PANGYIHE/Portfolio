<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = new SQLite3('payments.db');

# Data Query
// $yesterday = date('Y-m-d', strtotime('-1 day'));
$yesterday = '2025-12-31';

$query = "
SELECT merchant_id, 
    SUM(txn_count) AS txn_count, 
    SUM(total_amt) AS total_amt
FROM txn_summary
WHERE date = '$yesterday'
GROUP BY merchant_id
ORDER BY txn_count DESC, total_amt DESC
";

$result = $db->query($query);

# Create Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1','Merchant');
$sheet->setCellValue('B1','Transactions Count');
$sheet->setCellValue('C1','Total Sales (RM)');

$rowNum = 2;

while ($data = $result->fetchArray(SQLITE3_ASSOC)) {

    $sheet->setCellValue('A'.$rowNum, $data['merchant_id']);
    $sheet->setCellValue('B'.$rowNum, $data['txn_count']);
    $sheet->setCellValue('C'.$rowNum, $data['total_amt']);

    $rowNum++;
}

$file = 'daily_top_merchants.xlsx';

$writer = new Xlsx($spreadsheet);
$writer->save($file);

# Send Email
$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = $_ENV['EMAIL_FROM'];
$mail->Password = $_ENV['EMAIL_APP_PASSWORD'];
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom($_ENV['EMAIL_FROM'], 'Daily Report');
$mail->addAddress($_ENV['EMAIL_TO']);

$mail->Subject = 'Daily Top Merchant Sales Report';
$mail->Body = 'Attached below is the Daily Top Merchant Sales Report.';

$mail->addAttachment($file);

$mail->send();

echo "Report sent successfully";