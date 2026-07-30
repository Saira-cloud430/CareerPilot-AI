<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Smalot\PdfParser\Parser;

function extractResumeText($filePath)
{
    try
    {
        $parser = new Parser();

        $pdf = $parser->parseFile($filePath);

        return $pdf->getText();
    }
    catch(Exception $e)
    {
        return "";
    }
}