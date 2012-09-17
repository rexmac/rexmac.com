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
 * @subpackage Controller_Action
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/license/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
namespace Rexmac\Zyndax\Controller;

use \DateTime,
    \DateTimeZone,
    \Zend_Registry;

/**
 * Abstract Zend controller action class to extract messages from session data
 * for XmlHttpRequest requests.
 *
 * @category   Zyndax
 * @package    Rexmac_Zyndax
 * @subpackage Controller_Action
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/license/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
abstract class Action extends \Zend_Controller_Action {

  /**
   * Post-dispatch method to extract messages from session data for
   * XmlHttpRequest requests. Messages are stored in view variable.
   *
   * @return void
   */
  public function postDispatch() {
    if($this->getRequest()->isXmlHttpRequest()) {
      $messages = $this->view->messages()->getMessages();
      if(!empty($messages)) {
        $this->view->messages = $messages;
      }
    }
  }
}
