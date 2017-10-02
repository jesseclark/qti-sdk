<?php
require_once (dirname(__FILE__) . '/../../../QtiSmTestCase.php');

use qtism\common\storage\MemoryStream;

class MemoryStreamTest extends QtiSmTestCase 
{
    public function testMemoryStream()
    {
        $stream = new MemoryStream();
        $stream->open();
        
        $stream->write('abc');
        $this->assertEquals('abc', $stream->getBinary());
        
        $stream->write('def');
        $this->assertEquals('abcdef', $stream->getBinary());
        
        $stream->rewind();
        $stream->write('xyz');
        
        $this->assertEquals('xyzabcdef', $stream->getBinary());
    }
}
