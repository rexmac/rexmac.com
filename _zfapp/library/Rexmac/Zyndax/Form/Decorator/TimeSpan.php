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
 * @subpackage Form_Decorator
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
namespace Rexmac\Zyndax\Form\Decorator;

use Rexmac\Zyndax\Form\Element\TimeSpan as TimeSpanElement,
    \Zend_View_Interface;

/**
 * Timespan form element decorator
 *
 * @category   Zyndax
 * @package    Rexmac_Zyndax
 * @subpackage Form_Decorator
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */

class TimeSpan extends \Zend_Form_Decorator_Abstract {

  public function render($content) {
    $element = $this->getElement();
    if(!$element instanceof TimeSpanElement) {
      return $content;
    }

    $view = $element->getView();
    if(!$view instanceof Zend_View_Interface) {
      return $content;
    }

    $name = $element->getFullyQualifiedName();

    $options = array(
      TimeSpanElement::MINUTES => 'Minutes',
      TimeSpanElement::HOURS   => 'Hours',
      TimeSpanElement::DAYS    => 'Days',
      TimeSpanElement::WEEKS   => 'Weeks',
      TimeSpanElement::MONTHS  => 'Months',
    );

    $id = $element->getId();
    $timeId = $id . '-time';
    $unitsId = $id . '-units';

    $attribs = $element->getAttribs();
    unset($attribs['helper']);
    $attribs['id']    = $timeId;
    $attribs['size']  = 10;
    $attribs['class'] = 'beside';

    $markup = '<ol class="timespan">' .
      '<li>' .
      $view->formText($name.'[time]', $element->getTime(), $attribs) .
      $view->formSelect($name.'[units]', $element->getUnits(), array('id' => $unitsId), $options) .
      '</li>' .
      '</ol>';

    switch($this->getPlacement()) {
      case self::PREPEND:
        return $markup . $this->getSeparator() . $content;
      case self::APPEND:
      default:
        return $content . $this->getSeparator() . $markup;
    }
  }
}
