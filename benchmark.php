<?php
/**
 * @author Halil Özgür | halil · ozgur |o| gmail.com
 *
 * Usage: php benchmark.php <FILE_URI/PATH> <DELIMITER_TAG> <METHOD>, e.g.:
 *   php benchmark.php example.xml product 1
 *   php benchmark.php http://www.example.com/example.xml product 2
 */

$urlPrefix = "";
/**
 * Enable filter
 *
 * @link http://stackoverflow.com/a/3466609/372654
 */
/*stream_filter_register('xmlutf8', 'ValidUTF8XMLFilter');
class ValidUTF8XMLFilter extends php_user_filter
{
    protected static $pattern = '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u';

    function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = preg_replace(self::$pattern, '', $bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}
$urlPrefix = "php://filter/read=xmlutf8/resource=";*/

$url         = $urlPrefix . $argv[1];
$elemTagName = $argv[2];

$startTime = microtime(true);
$ct        = 0;
$method    = "";

switch ($argv[3]) {
    case 1:
        // V1
        $method = "SimpleXmlIterator\\XmlIterator";
        require_once "SimpleXmlIterator/XmlIterator.php";
        $it = new SimpleXmlIterator\XmlIterator($url, $elemTagName);
        foreach ($it as $k => $v) {
            $ct++;
//            echo $k . " => " . var_export($v, true) . "\n\n";
        }
        break;

    case 2:
        // V2 the memory aggressor
        $method = "SPL SimpleXMLIterator";
        $it     = new SimpleXMLIterator($url, null, true);
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
