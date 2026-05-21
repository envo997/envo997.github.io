<?php
require_once 'EncoderInterface.php';

class CsvEncoder implements EncoderInterface {
    private $delim;

    public function __construct($format, $delim) {
        $this->delim = $delim;
    }

    public function supports(string $format): bool {
        return $format === $this->delim
            || ($this->delim === ',' && $format === 'csv')
            || ($this->delim === ';' && $format === 'ssv')
            || ($this->delim === "\t" && $format === 'tsv');
    }

    public function decode(string $input): array {
        $lines = explode("\n", trim($input));
        if (count($lines) < 2) return [];

        $headers = str_getcsv(array_shift($lines), $this->delim);
        $data = [];
        foreach ($lines as $line) {
            if (trim($line) === '') continue;
            $row = str_getcsv($line, $this->delim);
            if (count($headers) === count($row)) {
                $data[] = array_combine($headers, $row);
            }
        }
        return $data;
    }

    public function encode(array $data): string {
        if (empty($data)) return "";
        $output = fopen('php://memory', 'r+');
        fputcsv($output, array_keys($data[0]), $this->delim);
        foreach ($data as $row) {
            fputcsv($output, array_values($row), $this->delim);
        }
        rewind($output);
        $res = stream_get_contents($output);
        fclose($output);
        return trim($res);
    }
}