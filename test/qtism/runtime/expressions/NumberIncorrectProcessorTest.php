<?php

use qtism\common\datatypes\Point;
use qtism\common\datatypes\DirectedPair;
use qtism\common\datatypes\Pair;
use qtism\runtime\common\MultipleContainer;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\runtime\common\ResponseVariable;
use qtism\runtime\common\State;
use qtism\common\collections\IdentifierCollection;
use qtism\data\expressions\NumberIncorrect;
use qtism\runtime\expressions\NumberIncorrectProcessor;

require_once (dirname(__FILE__) . '/../../../QtiSmItemSubsetTestCase.php');

class NumberIncorrectProcessorTest extends QtiSmItemSubsetTestCase {
	
	public function testNumberIncorrect() {
		$session = $this->getTestSession();
		
		$overallCorrect = self::getNumberIncorrect();
		$includeMathResponded = self::getNumberIncorrect('', new IdentifierCollection(array('mathematics')));
		$processor = new NumberIncorrectProcessor($overallCorrect);
		$processor->setState($session);
		
		// Nothing responded yet.
		$this->assertEquals(9, $processor->process());
		$processor->setExpression($includeMathResponded);
		$this->assertEquals(3, $processor->process());
		
		// Q01
		$session->beginAttempt();
		$responses = new State();
		// Correct!
		$responses->setVariable(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::IDENTIFIER, 'ChoiceA'));
		$session->endAttempt($responses);
		$processor->setExpression($overallCorrect);
	    $this->assertEquals(8, $processor->process());
	    $processor->setExpression($includeMathResponded);
	    $this->assertEquals(2, $processor->process());
	    $session->moveNext();
	    
	    // Q02
	    $responses->reset();
	    $session->beginAttempt();
	    // Incorrect!
	    $responses->setVariable(new ResponseVariable('RESPONSE', Cardinality::MULTIPLE, BaseType::PAIR, new MultipleContainer(BaseType::PAIR, array(new Pair('A', 'P'), new Pair('D', 'L')))));
	    $session->endAttempt($responses);
	    $processor->setExpression($overallCorrect);
	    $this->assertEquals(8, $processor->process());
	    $processor->setExpression($includeMathResponded);
	    $this->assertEquals(2, $processor->process());
	    $session->moveNext();
	    
	    // Q03
	    // Incorrect!
	    $session->beginAttempt();
	    $session->skip();
	    $processor->setExpression($overallCorrect);
	    $this->assertEquals(8, $processor->process());
	    
	    // Q04
	    // Correct!
	    $responses->reset();
	    $session->beginAttempt();
	    $responses->setVariable(new ResponseVariable('RESPONSE', Cardinality::MULTIPLE, BaseType::DIRECTED_PAIR, new MultipleContainer(BaseType::DIRECTED_PAIR, array(new DirectedPair('W', 'G1'), new DirectedPair('Su', 'G2')))));
	    $session->endAttempt($responses);
	    $this->assertEquals(7, $processor->process());
	    $session->moveNext();
	    
	    // Q05
	    // Incorrect!
	    $session->beginAttempt();
	    $session->skip();
	    $this->assertEquals(7, $processor->process());
	    
	    // Q06
	    // Correct!
	    $responses->reset();
	    $session->beginAttempt();
	    $responses->setVariable(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::IDENTIFIER, 'A'));
	    $session->endAttempt($responses);
	    $this->assertEquals(6, $processor->process());
	    $session->moveNext();
	    
	    // Q07.1
	    // Incorrect
	    $responses->reset();
	    $session->beginAttempt();
	    $responses->setVariable(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::POINT, new Point(100, 100)));
	    $session->endAttempt($responses);
	    $this->assertEquals(6, $processor->process());
	    $session->moveNext();
	    
	    // Q07.2
	    // Incorrect!
	    $responses->reset();
	    $session->beginAttempt();
	    $responses->setVariable(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::POINT, new Point(10, 10)));
	    $session->endAttempt($responses);
	    $this->assertEquals(6, $processor->process());
	    $session->moveNext();
	    
	    // Q07.3
	    // Correct!
	    $responses->reset();
	    $session->beginAttempt();
	    $responses->setVariable(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::POINT, new Point(102, 113)));
	    $session->endAttempt($responses);
	    $this->assertEquals(5, $processor->process());
	}
	
    protected static function getNumberIncorrect($sectionIdentifier = '', IdentifierCollection $includeCategories = null, IdentifierCollection $excludeCategories = null) {
	    $numberIncorrect = new NumberIncorrect();
	    $numberIncorrect->setSectionIdentifier($sectionIdentifier);
	    
	    if (empty($includeCategories) === false) {
	        $numberIncorrect->setIncludeCategories($includeCategories);
	    }
	    
	    if (empty($excludeCategories) === false) {
	        $numberIncorrect->setExcludeCategories($excludeCategories);
	    }

	    return $numberIncorrect;
	}
}