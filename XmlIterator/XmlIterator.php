<?php
/**
 * @author Halil Özgür | halil · ozgur |o| gmail.com
 */

namespace XmlIterator;

class XmlIterator implements \Iterator
{
    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var string
     */
    protected $xmlFileUri;

    /**
     * @var string The name of the tag that delimits/separates each iterated element/row
     */
    protected $delimiterTagName;

    /**
     * @var array
     */
    protected $options = array(
        /**
         * @var string Encoding of source file
         */
        "encoding"      => null, //

        /**
         * @var null See __construct() for the default
         */
        "readerOptions" => null, //

        /**
         * @var bool Activate UTF8 filter agains invalid (e.g. "out of allowed range") characters?
         *
         * Disable for performance gain if you know that the xml is clean of bad characters.
         */
        "utf8Filter"    => true, //

        /**
         * @var bool Return current element as an array for ease of use?
         *
         * Disable for performance gain. Bu you need to do (string)$current->something for each sub-element.
         */
        "asArray"       => true, //
    );

    /**
     * @var \XMLReader
     */
    protected $reader;

    /**
     * @var \DOMDocument
     */
    protected $doc;

    /**
     * @param string $xmlFileUri
     * @param string $delimiterTagName
     * @param array  $options
     *
     * @throws \Exception
     */
    function __construct($xmlFileUri, $delimiterTagName, $options = array())
    {
        $this->xmlFileUri       = $xmlFileUri;
        $this->delimiterTagName = $delimiterTagName;

        // work-around for non-scalar default value
        $this->options["readerOptions"] = \XMLReader::VALIDATE | \XMLReader::SUBST_ENTITIES | LIBXML_NOCDATA;
        $this->options                  = array_replace_recursive($this->options, $options);

        $this->reader = new \XMLReader();
        $this->doc    = new \DOMDocument();

        if ($this->options["utf8Filter"]) {
            require_once "Utf8Filter.php";
            stream_filter_register('xmlutf8', __NAMESPACE__ . "\\Utf8Filter");
        }
    }

    /**
     * Return the current element, <b>FALSE</b> on error
     * @link http://php.net/manual/en/iterator.current.php
     * @link http://stackoverflow.com/a/1835324/372654
     * @return false|array|\SimpleXMLElement
     */
    public function current()
    {
        $node = $this->reader->expand();
        if ($node === false) {
            return false;
        }
        $node = $this->doc->importNode($node, true);
        if ($node === false) {
            return false;
        }
        $current = simplexml_import_dom($node);
        if ($current === false) {
            return false;
        }

        if ($this->options["asArray"]) {
            return json_decode(json_encode($current), true);
        } else {
            return $current;
        }
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        if ($this->reader->next($this->delimiterTagName)) {
            ++$this->position;
        }
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return int scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->reader->name === $this->delimiterTagName;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @throws \Exception
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $uri = $this->xmlFileUri;
        if ($this->options["utf8Filter"]) {
            $uri = "php://filter/read=xmlutf8/resource=" . $uri;
        }
        if (!$this->reader->open($uri, $this->options["encoding"], $this->options["readerOptions"])) {
            throw new \Exception("$this->xmlFileUri cannot be opened");
        }

        // move to the first element
        while ($this->reader->read() && $this->reader->name !== $this->delimiterTagName) {
            // intentionally empty
        }
    }
}
