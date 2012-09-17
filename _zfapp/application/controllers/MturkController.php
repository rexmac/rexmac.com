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
use \Exception,
    Rexmac\Zyndax\Service\Amazon\MTurk,
    Rexmac\Zyndax\Log\Logger;

/**
 * MTurk controller
 *
 * @category   Zyndax
 * @package    Rexmac_Zyndax
 * @subpackage Application_Controller
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
class MturkController extends \Rexmac\Zyndax\Controller\Action {

  /**
   * Initialization
   *
   * Initializes ajax contexts
   *
   * @return void
   */
  public function init() {
    /*$request = $this->getRequest();
    if(null === $request->getParam('format')) {
      $request->setParam('format', 'json');
    }*/
    $this->getRequest()->setParam('format', 'json');

    $contextSwitch = $this->_helper->getHelper('contextSwitch');
    $contextSwitch
      ->addActionContext('createexternalhit', 'json')
      ->initContext();
    parent::init();
  }

  /**
   * Index action
   *
   * @return void
   */
  public function indexAction() {
    $this->_helper->redirector->gotoUrlAndExit('http://rexmac.com/projects/mturk/');
  }

  /**
   * Create external HIT action
   *
   * @return void
   */
  public function createexternalhitAction() {
    Logger::debug(__METHOD__);
    $request = $this->getRequest();
    //if(!$request->isxmlHttpRequest()) { $this->_helper->redirector('index'); }

    $form = new \Application_Form_MTurkExternalHitCreate();

    if($request->isPost()) {
      // The accessKey field is initally flagged as required to force the
      // user to input a value, but the value is only used client-side, thus,
      // it is never submitted to the server. Remove the required flag
      // before processing the form.
      $form->getElement('accessKey')->setRequired(false);

      // Is the submitted form data valid?
      if($form->isValid($request->getPost())) {
        $data = $form->getValues();
        try {
          #Logger::debug(__METHOD__.':: data :: '.var_export($data, true));
          #Logger::debug(__METHOD__.':: data.duration.time  :: '.var_export($data['duration']->getTime(), true));
          #Logger::debug(__METHOD__.':: data.duration.units :: '.var_export($data['duration']->getUnits(), true));
          #Logger::debug(__METHOD__.':: data.duration.units :: '.var_export($data['duration']->getTimeInSeconds(), true));
          #Logger::debug(__METHOD__.':: data :: '.var_export($data['countriesFlag'], true));
          #Logger::debug(__METHOD__.':: data :: '.var_export($data['countries'], true));

          // Prepare parameters for creating the HIT
          $hitParams = array(
            'ExternalUrl'           => $data['surveyUrl'] . ($data['sandbox'] ? '&amp;test=1' : ''),
            'Sandbox'               => $data['sandbox'],
            'AWSAccessKeyId'        => $data['accessKeyId'],
            'Signature'             => $data['signature'],
            'Timestamp'             => $data['timestamp'],
            'Title'                 => $data['title'],
            'Description'           => $data['description'],
            'Keywords'              => $data['keywords'],
            'FrameHeight'           => $data['frameHeight'],
            'MaxAssignments'        => $data['max'],
            'Reward.1.Amount'       => $data['reward'],
            'Reward.1.CurrencyCode'       => 'USD',
            'AssignmentDurationInSeconds' => $data['duration']->getTimeInSeconds(),
            'AutoApprovalDelayInSeconds'  => $data['autoApprovalDelay']->getTimeInSeconds(),
            'LifetimeInSeconds'           => $data['lifetime']->getTimeInSeconds(),
            'RequesterAnnotation'         => 'uid',
          );
          if(is_array($data['countries']) && count($data['countries']) > 0) {
            $comparator = ($data['countriesFlag'] === 'exclude' ? 'NotEqualTo' : 'EqualTo');
            foreach($data['countries'] as $i => $country) {
              $hitParams['QualificationRequirement.' . ++$i . '.QualificationTypeId'] = '00000000000000000071';
              $hitParams['QualificationRequirement.' .   $i . '.Comparator']          = $comparator;
              $hitParams['QualificationRequirement.' .   $i . '.LocaleValue.Country'] = $country;
            }
          }

          // Attempt to create the HIT
          $response = MTurk::createExternalHit($hitParams);
          Logger::debug(__METHOD__.':: BEGIN RESPONSE ::'.PHP_EOL.var_export($response, true).PHP_EOL.__METHOD__.'::: END RESPONSE :::');

          // Generate HIT management and preview URLs
          #$response = array('hitId' => 'foo', 'hitTypeId' => 'bar');
          $this->view->manageUrl = MTurk::getHitManagementUrl($response['hitId'], $data['sandbox']);
          $this->view->previewUrl = MTurk::getHitPreviewUrl($response['hitTypeId'], $data['sandbox']);

          // Return success response
          $message = 'Your HIT has been created.';
          $this->view->messages()->addMessage($message, 'success');
          $this->view->success = true;
        } catch(Exception $e) { // Something bad happened, return error response and inform user
          $this->view->success = false;
          $this->getResponse()->setHttpResponseCode(500);
          $this->view->messages()->addMessage($e->getMessage(), 'error');
        }
      } else { // Submitted form data is invalid, return error response and inform user
        $this->view->success = false;
        $this->getResponse()->setHttpResponseCode(500);
        #Logger::debug(__METHOD__.':: invalid form data :: '.var_export($form->getMessages(), true));
        $this->view->messages()->addMessage($form->getMessages(), 'error');
      }
    } else { // Not a POST request, do nothing
    }
  }
}
