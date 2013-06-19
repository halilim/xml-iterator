<?php
/**
 * @author Halil Özgür | halil · ozgur |o| gmail.com
 *
 * Usage: php benchmark.php <FILE_URI/PATH> <DELIMITER_TAG> <METHOD>, e.g.:
 *   php benchmark.php example.xml product 1
 *   php benchmark.php http://www.example.com/example.xml product 2
 *
 * Use with very large files (50 MB+) and watch the memory usage.
 */

$url         = $argv[1];
$elemTagName = $argv[2];

$startTime = microtime(true);
$ct        = 0;
$method    = "";

switch ($argv[3]) {
    case 1:
        // V1
        $method = "XmlIterator\\XmlIterator";
        require_once "XmlIterator/XmlIterator.php";
        $it = new XmlIterator\XmlIterator($url, $elemTagName);
        foreach ($it as $k => $v) {
            $ct++;
//            echo $k . " => " . var_export($v, true) . "\n\n";
        }
        break;

    case 2:
        // V2 the memory aggressor
        $method = "SPL SimpleXMLIterator";
        require_once "XmlIterator/Utf8Filter.php";
        stream_filter_register('xmlutf8', "XmlIterator\\Utf8Filter");
        $it = new SimpleXMLIterator("php://filter/read=xmlutf8/resource=" . $url, LIBXML_NOENT | LIBXML_NOCDATA, true);
        foreach ($it->{$elemTagName} as $v) {
            $ct++;
//            echo var_export($v, true) . "\n\n";
//            echo (string)$v->element_1 . "\n\n";
        }
        break;

    default:
        break;
}


$time = microtime(true) - $startTime;
echo "\n";
echo "method      : $method\n";
echo "elem count  : $ct\n";
echo "time passed : $time s\n";

// Useless since the memory used by libxml libraries is not reported
//echo "peak memory : " . (memory_get_peak_usage(true) / pow(2, 20)) . " MiB\n";
