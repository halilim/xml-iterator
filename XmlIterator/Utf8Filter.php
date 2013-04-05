<?php
/**
 * @author Halil Özgür | halil · ozgur |o| gmail.com
 */

namespace XmlIterator;

class Utf8Filter extends \php_user_filter
{
    /**
     * @param $in
     * @param $out
     * @param $consumed
     * @param $closing
     *
     * @return int|void
     *
     * @link http://stackoverflow.com/a/3466609/372654
     */
    function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = preg_replace(
                '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u',
                '',
                $bucket->data
            );
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}
