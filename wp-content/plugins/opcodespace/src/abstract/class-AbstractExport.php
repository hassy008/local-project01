<?php

/**
 *
 */
class AbstractExport
{

    public function csv($data, $filename = "export.csv", $direct_download = true, $path = "")
    {
        if ($direct_download) {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');

            $f = fopen('php://output', 'w');

            foreach ($data as $line) {
                fputcsv($f, $line);
            }
        } else {
            $f = fopen($path, $line);
        }
    }
}
