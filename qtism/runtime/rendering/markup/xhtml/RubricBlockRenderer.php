<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts, <jerome@taotesting.com>
 * @license GPLv2
 * @package qtism
 * @subpackage
 *
 */

namespace qtism\runtime\rendering\markup\xhtml;

use qtism\data\View;
use qtism\runtime\rendering\AbstractRenderingContext;
use qtism\data\QtiComponent;
use \DOMDocumentFragment;

/**
 * RubricBlock renderer. Rendered components will be transformed as 
 * 'div' elements with a 'qti-rubricBlock' additional CSS class.
 * 
 * Moreover, if the view information will be added ass CSS additional classes.
 * For instance, if qti:rubricBlock->view = 'proctor candidate', the resulting
 * element will be '<rubricBlock
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class RubricBlockRenderer extends BodyElementRenderer {
    
    public function __construct(AbstractRenderingContext $renderingContext = null) {
        parent::__construct($renderingContext);
        $this->transform('div');
        $this->additionalClass('qti-rubricBlock');
    }
    
    protected function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component) {
        
        $dataView = array();
        
        if ($component->getViews()->contains(View::AUTHOR)) {
            $this->additionalClass('qti-view-author');
            $dataView[] = 'author';
        }
        else if ($component->getViews()->contains(View::CANDIDATE)) {
            $this->additionalClass('qti-view-candidate');
            $dataView[] = 'candidate';
        }
        else if ($component->getViews()->contains(View::PROCTOR)) {
            $this->additionalClass('qti-view-proctor');
            $dataView[] = 'proctor';
        }
        else if ($component->getViews()->contains(View::SCORER)) {
            $this->additionalClass('qti-view-scorer');
            $dataView[] = 'scorer';
        }
        else if ($component->getViews()->contains(View::TEST_CONSTRUCTOR)) {
            $this->additionClass('qti-view-testConstructor');
            $dataView[] = 'testConstructor';
        }
        else if ($component->getViews()->contains(View::TUTOR)) {
            $this->additionalClass('qti-view-tutor');
            $dataView[] = 'tutor';
        }
        
        parent::appendAttributes($fragment, $component);
        
        $fragment->firstChild->setAttribute('data-view', implode("\x20", $dataView));
    }
}