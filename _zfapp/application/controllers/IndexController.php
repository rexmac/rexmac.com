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
 * @subpackage Application_Controller
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */

/**
 * Index controller
 *
 * @category   Zyndax
 * @package    Rexmac_Zyndax
 * @subpackage Application_Controller
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
class IndexController extends \Zend_Controller_Action {

  /**
   * Index action
   *
   * @return void
   */
  public function indexAction() {
    $this->_redirector->gotoUrl('http://rexmac.com/');
  }
}
