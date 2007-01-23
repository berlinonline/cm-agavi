<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2003-2006 the Agavi Project.                                |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.agavi.org/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * A renderer produces the output as defined by a View
 *
 * @package    agavi
 * @subpackage renderer
 *
 * @author     David Zuelke <dz@bitxtender.com>
 * @author     Agavi Project <info@agavi.org>
 * @copyright  (c) Authors
 * @since      0.11.0
 *
 * @version    $Id$
 */
class AgaviPhpRenderer extends AgaviRenderer implements AgaviIReusableRenderer
{
	/**
	 * @var        string A string with the default template file extension,
	 *                    including the dot.
	 */
	protected $defaultExtension = '.php';
	
	/**
	 * @var        AgaviTemplateLayer Temporary storage for the template layer,
	 *                                used during rendering.
	 */
	protected $_layer = null;
	
	/**
	 * @var        array Temporary storage for the template layer, used during
	 *                   rendering.
	 */
	protected $_attributes = null;
	
	/**
	 * @var        array Temporary storage for the template layer, used during
	 *                   rendering.
	 */
	protected $_slots = null;
	
	/**
	 * Render the presentation to the Response.
	 *
	 * @author     David Zuelke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function render(AgaviTemplateLayer $layer, array &$attributes, array &$slots = array())
	{
		// DO NOT USE VARIABLES IN HERE, THEY MIGHT INTERFERE WITH TEMPLATE VARS
		$this->_layer = $layer;
		$this->_attributes =& $attributes;
		$this->_slots =& $slots;
		unset($layer, $attributes, $slots);
		
		if($this->extractVars) {
			extract($this->_attributes, EXTR_REFS | EXTR_PREFIX_INVALID, '_');
		} else {
			${$this->varName} =& $this->_attributes;
		}
		
		if($this->extractSlots === true || ($this->extractVars && $this->extractSlots !== false)) {
			extract($this->_slots, EXTR_REFS | EXTR_PREFIX_INVALID, '_');
		} else {
			if(!isset(${$this->slotsVarName})) {
				${$this->slotsVarName} = array();
			}
			${$this->slotsVarName} = array_merge(${$this->slotsVarName}, $this->_slots);
		}
		
		$collisions = array_intersect(array_keys($this->assigns), array_keys($this->_attributes));
		if(count($collisions)) {
			throw new AgaviException('Could not import system objects due to variable name collisions ("' . implode('", "', $collisions) . '" already in use).');
		}
		unset($collisions);
		
		extract($this->assigns);
		
		ob_start();
		
		require($this->_layer->getResourceStreamIdentifier());
		
		$retval = ob_get_contents();
		ob_end_clean();
		
		unset($this->_layer, $this->_attributes, $this->_slots);
		
		return $retval;
	}
}

?>