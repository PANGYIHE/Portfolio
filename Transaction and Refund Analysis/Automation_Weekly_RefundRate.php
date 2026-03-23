<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;
use PhpOffice\PhpSpreadsheet\Style\Border;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = new SQLite3('payments.db');

# Data Query
// $yesterday = date('Y-m-d', strtotime('-1 day'));
// $last_week = date('Y-m-d', strtotime('-7 day'));
$yesterday = '2025-12-31';
$last_week = date('Y-m-d', strtotime($yesterday . ' -6 days'));

$query = "
WITH refund_sum AS (
    SELECT t.merchant_id, 
        COUNT(r.refund_id) AS refund_count
    FROM refunds r
    LEFT JOIN transactions t ON r.tran_id = t.tran_id
    WHERE t.date BETWEEN '2025-12-25 00:00:00' AND '2025-12-31 23:59:59' 
        AND r.status = 'completed' 
    GROUP BY t.merchant_id
)
SELECT 
    t.merchant_id, 
    COUNT(t.tran_id) AS txn_count,
    COALESCE(r.refund_count, 0) AS refund_count,
    ROUND(
        COALESCE(r.refund_count, 0) * 100.0 
        / COUNT(t.tran_id), 
        2
    ) AS refund_rate
FROM transactions t
LEFT JOIN refund_sum r  ON t.merchant_id = r.merchant_id
WHERE t.status IN ('recorded', 'completed') 
    AND t.date BETWEEN '2025-12-25 00:00:00' AND '2025-12-31 23:59:59'
GROUP BY t.merchant_id
ORDER BY refund_rate DESC, txn_count DESC
";

$result = $db->query($query);

# Create Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1','Merchant');
$sheet->setCellValue('B1','Transactions Count');
$sheet->setCellValue('C1','Refunds Count');
$sheet->setCellValue('D1','Refund Rate (%)');

$rowNum = 2;

while ($data = $result->fetchArray(SQLITE3_ASSOC)) {

    $sheet->setCellValue('A'.$rowNum, $data['merchant_id']);
    $sheet->setCellValue('B'.$rowNum, $data['txn_count']);
    $sheet->setCellValue('C'.$rowNum, $data['refund_count']);
    $sheet->setCellValue('D'.$rowNum, $data['refund_rate']);

    $rowNum++;
}

foreach (range('A','D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$lastRow = $rowNum - 1;

$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];

$sheet->getStyle('A1:D' . $lastRow)->applyFromArray($styleArray);

$sheet->getStyle('A1:D1')->getFont()->setBold(true);

$dateObj = new DateTime($yesterday);
$file = "csv_file/Merchant Refund Rate ". $last_week . " to " . $dateObj->format('d') . ".xlsx";

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

$mail->setFrom($_ENV['EMAIL_FROM'], 'Weekly Report');
$mail->addAddress($_ENV['EMAIL_TO']);

$mail->Subject = 'Weekly Merchant Refund Rate Report '.$last_week.' to '.$dateObj->format('d');
$mail->Body = 'Attached below is the Weekly Merchant Refund Rate Report.';

$mail->addAttachment($file);

$mail->send();

echo "Report sent successfully";