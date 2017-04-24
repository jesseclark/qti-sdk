<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 20.04.17
 * Time: 15:20
 */

namespace qtismtest\data\expressions;

use qtismtest\QtiSmTestCase;
use qtism\data\storage\xml\XmlDocument;
use qtism\runtime\expressions\ExpressionEngine;


class ExpressionTest extends QtiSmTestCase
{
    public function testIsPure()
    {
        $doc = new XmlDocument();
        $doc->load(self::samplesDir() . 'custom/tests/branchingpath_v2.xml');
        $test = $doc->getDocumentComponent();

        $itemq3 = $doc->getDocumentComponent()->getComponentByIdentifier('Q03');
        $itemq1 = $doc->getDocumentComponent()->getComponentByIdentifier('Q01');

        $this->assertEquals(true, $itemq3->getBranchRules()[0]->getExpression()->IsPure());
        $this->assertEquals(false, $itemq1->getBranchRules()[0]->getExpression()->IsPure());

        $doc->load(self::samplesDir() . 'custom/tests/branchingexpressions.xml');
        $test = $doc->getDocumentComponent();
        $impures = ['Q5', 'Q37', 'Q47', 'Q48', 'Q49', 'Q50', 'Q52', 'Q53', 'Q55'];

        for ($i = 1; $i < 56; $i++) {
            $this->assertEquals(!in_array('Q' . $i, $impures),
                $test->getComponentByIdentifier('Q' . $i)->getBranchRules()[0]->getExpression()->IsPure());
        }
    }

    public function testIsPureWithEngine()
    {
        // TODO : a bouger dans les tests runtime ?

        $doc = new XmlDocument();
        $doc->load(self::samplesDir() . 'custom/tests/branchingpath_v2.xml');
        $test = $doc->getDocumentComponent();

        $itemq3 = $doc->getDocumentComponent()->getComponentByIdentifier('Q03');
        $ee = new ExpressionEngine($itemq3->getBranchRules()[0]->getExpression());

        $this->assertEquals(true, $ee->process()->getValue());
    }
}