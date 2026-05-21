<?php
require_once 'EncoderInterface.php';

class YamlEncoder implements EncoderInterface {
    public function supports(string $format): bool {
        return $format === 'yaml';
    }

    public function decode(string $input): array {
        return function_exists('yaml_parse') ? (yaml_parse($input) ?? []) : (json_decode($input, true) ?? []);
    }

    public function encode(array $data): string {
        return function_exists('yaml_emit') ? yaml_emit($data) : "--- # [Brak PECL yaml]\n" . print_r($data, true);
    }
}