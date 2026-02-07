<?php

declare(strict_types=1);

namespace XmlIterator;

use PHPUnit\Framework\TestCase;

class XmlIteratorTest extends TestCase
{
    protected $iterator;

    protected function setUp(): void
    {
        $this->iterator = new XmlIterator(__DIR__ . "/Fixtures/test.xml", "product");
    }

    public function testXmlIterator(): void
    {
        $this->assertInstanceOf("\\Iterator", $this->iterator, "XmlIterator is an Iterator");

        $ct = 0;
        foreach ($this->iterator as $node) {
            $ct++;
            switch ($ct) {
                case 1:
                    $this->assertIsArray($node, "can convert nodes to array");
                    $this->assertEquals("Lorem", $node["title"], "can strip invalid characters");
                    $this->assertIsArray(
                        $node["images"],
                        "converts deep objects to nested arrays"
                    );
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
