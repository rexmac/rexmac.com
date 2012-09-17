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
use Rexmac\Zyndax\Log\Logger,
    \Zend_Controller_Plugin_ErrorHandler as ErrorHandler,
    \Zend_Registry;

/**
 * Error controller
 *
 * @category   Zyndax
 * @package    Rexmac_Zyndax
 * @subpackage Application_Controller
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
class ErrorController extends \Zend_Controller_Action {

  /**
   * Initialization
   *
   * @return void
   */
  public function init() {
    $this->_helper->contextSwitch()
      ->addContext('html', array())
      ->addActionContext('error', array('json', 'xml'))
      ->initContext();
  }

  /**
   * Pre-dispatch
   *
   * @return void
   */
  public function preDispatch() {
    if('xml' === $this->getRequest()->getParam('format')) {
      $this->view->xmlWrapperTag = mb_strtolower(sanitize_string_for_xml_tag(Zend_Registry::get('siteName'))) . 'Response';
    }
  }

  /**
   * Post-dispatch
   *
   * @return void
   */
  public function postDispatch() {
    unset($this->view->now);
    if('xml' === $this->getRequest()->getParam('format')) {
      $this->render();
      $response = $this->getResponse();
      $response->setBody(preg_replace('/>\s+</', '><', $response->getBody()));
    }
  }

  /**
   * Error action
   *
   * @return void
   */
  public function errorAction() {
    $errors = $this->_getParam('error_handler');
    switch ($errors->type) {
      case ErrorHandler::EXCEPTION_NO_ROUTE:
      case ErrorHandler::EXCEPTION_NO_CONTROLLER:
      case ErrorHandler::EXCEPTION_NO_ACTION:
        // 404 error -- controller or action not found
        $this->getResponse()->setHttpResponseCode(404);
        $this->view->message = 404;
        $this->_helper->redirector->gotoUrlAndExit('http://rexmac.com/404/');
        break;
      default:
        // Application error
        $this->getResponse()->setHttpResponseCode(500);
        if($errors->exception instanceof ApiControllerException) {
          $this->view->message = $errors->exception->getMessage();
        } else {
          $this->view->message = 'Application error: ' . $errors->exception->getMessage();
        }
    }

    // Log exception
    Logger::crit(__METHOD__ . ':: ' . $this->view->message . ' - ' . $errors->exception);

    // Conditionally display exceptions
    if($this->getInvokeArg('displayExceptions') == true) {
      $this->view->exception = $errors->exception;
    }

    $this->view->request = $errors->request;

    if($this->view->message === 404) {
      $this->view->pageTitle(' - Page not found');
    } else {
      $this->view->pageTitle(' - An error occurred');
    }

    $this->getRequest()->setParams(array(
      'controller' => 'error',
      'action'     => 'error'
    ));
  }

  /**
   * Forbidden action
   *
   * @return void
   */
  public function forbiddenAction() {
  }
}
