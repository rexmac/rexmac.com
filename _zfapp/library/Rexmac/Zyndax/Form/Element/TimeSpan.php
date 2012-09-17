<?php
/**
 * Zyndax
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL:
 * http://rexmac.com/license/mit.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email to
 * license@rexmac.com so we can send you a copy.
 *
 * @category   Zyndax
 * @package    Rexmac_Zyndax
 * @subpackage Form_Element
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
namespace Rexmac\Zyndax\Form\Element;

use \Exception;

/**
 * Timespan form element
 *
 * @category   Zyndax
 * @package    Rexmac_Zyndax
 * @subpackage Form_Element
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
class TimeSpan extends \Zend_Form_Element_Xhtml {

  protected $_units = null;
  protected $_time  = null;

  const MINUTES = 1;
  const HOURS   = 2;
  const DAYS    = 3;
  const WEEKS   = 4;
  const MONTHS  = 5;
  #const CUSTOM  = 6;

  public function __construct($spec, $options = null) {
    $this->addPrefixPath(
      'Rexmac\Zyndax\Form\Decorator\\',
      'Rexmac/Zyndax/Form/Decorator',
      'decorator'
    );
    if(isset($options['units'])) {
      $this->setUnits($options['units']);
      unset($options['units']);
    } else {
      $this->setUnits(self::DAYS);
    }
    parent::__construct($spec, $options);
  }

  public function loadDefaultDecorators() {
    if($this->loadDefaultDecoratorsIsDisabled()) {
      return;
    }

    $decorators = $this->getDecorators();
    if(empty($decorators)) {
      $this
        ->addDecorator('TimeSpan')
        ->addDecorator('Description', array('tag' => 'p', 'class' => 'description', 'escape' => false))
        ->addDecorator('Label', array(
          'optionalSuffix' => '<span class="optional">&nbsp;&nbsp;</span>',
          'requiredSuffix' => '<span class="required">&nbsp;*</span>',
          'escape' => false
        ))
        ->addDecorator('HtmlTag', array('tag' => 'li'));
    }
  }

  public function getUnits() {
    return $this->_units;
  }

  public function getTime() {
    return $this->_time;
  }

  public function getTimeInSeconds() {
    $time = $this->_time;
    switch($this->_units) {
      case self::MINUTES: $time *= 60; break;
      case self::HOURS: $time *= 60 * 60; break;
      case self::DAYS: $time *= 60 * 60 * 24; break;
      case self::WEEKS: $time *= 60 * 60 * 24 * 7; break;
      case self::MONTHS: $time *= 60 * 60 * 24 * 30; break;
    }
    return $time;
  }

  public function setUnits($units) {
    if(!in_array($units, array(self::MINUTES, self::HOURS, self::DAYS, self::WEEKS, self::MONTHS))) {
      throw new Exception('Invalid units');
    }
    $this->_units = $units;
    return $this;
  }

  public function setTime($time) {
    $this->_time = $time;
    return $this;
  }

  public function setValue($value) {
    if(is_array($value)) {
      if(isset($value['time']) && '' !== $value['time'] && isset($value['units'])) {
        $this->setTime($value['time']);
        $this->setUnits($value['units']);
      } else {
        throw new Exception('Invalid time span provided');
      }
    } else {
      throw new Exception('Invalid time span provided');
    }

    return $this;
  }

  public function getValue() {
    return $this;
  }
}

