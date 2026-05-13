<?php
class ExportService {
    public static function toCsv(array $data, array $headers, string $filename): void {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($out, $headers);

        foreach ($data as $row) {
            fputcsv($out, array_values($row));
        }

        fclose($out);
        exit();
    }
}