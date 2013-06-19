<?php
/**
 * @author Halil Özgür | halil · ozgur |o| gmail.com
 */

namespace XmlIterator\Tests;

require_once __DIR__ . "/../XmlIterator.php";

use XmlIterator;

class XmlIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected $iterator;

    protected function setUp()
    {
        $this->iterator = new XmlIterator\XmlIterator(__DIR__ . "/Fixtures/test.xml", "product");
    }

    protected function tearDown()
    {
        $this->iterator = null;
    }

    public function testXmlIterator()
    {
        $this->assertInstanceOf("\\Iterator", $this->iterator, "XmlIterator is an Iterator");

        $ct = 0;
        foreach ($this->iterator as $node) {
            $ct++;
            switch ($ct) {
                case 1:
                    $this->assertInternalType("array", $node, "can convert nodes to array");
                    $this->assertEquals("Lorem", $node["title"], "can strip invalid characters");
                    break;

                case 2:
                    $this->assertEquals('cdata description <>" & <foo></bar>', $node["description"], "can read CDATA");
                    break;

                default:
                    break;
            }
        }
        $this->assertEquals(2, $ct, "reads all elements");
    }
}
