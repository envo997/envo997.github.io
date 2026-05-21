<?php
require_once 'Encoder/CsvEncoder.php';
require_once 'Encoder/JsonEncoder.php';
require_once 'Encoder/YamlEncoder.php';

class Serializer {
    private $encoders = [];

    public function __construct() {
        $this->encoders[] = new CsvEncoder('csv', ',');
        $this->encoders[] = new CsvEncoder('ssv', ';');
        $this->encoders[] = new CsvEncoder('tsv', "\t");
        $this->encoders[] = new JsonEncoder();
        $this->encoders[] = new YamlEncoder();
    }

    public function convert($input, $from, $to) {
        if (empty(trim($input))) return "";
        if ($from === $to) return $input;

        $dec = null;
        $enc = null;

        foreach ($this->encoders as $e) {
            if ($e->supports($from)) $dec = $e;
            if ($e->supports($to)) $enc = $e;
        }
        if (!$dec || !$enc) return "Błąd: Brak obsługi formatu.";

        $arr = $dec->decode($input);
        if (empty($arr)) return "Błąd parsowania danych.";
        return $enc->encode($arr);
    }
}