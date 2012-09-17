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
 * @subpackage Application_Form
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
use Rexmac\Zyndax\Form\Element\TimeSpan,
    \Zend_Locale;

/**
 * MTruk Create external HIT form
 *
 * @category   Zyndax
 * @package    Rexmac_Zyndax
 * @subpackage Application_Form
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/licenses/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */

class Application_Form_MTurkExternalHitCreate extends \Rexmac\Zyndax\Form\Form {
  /**
   * Initialization
   *
   * @return void
   */
  public function init() {
    $this->setName('mturkExternalHitCreateForm');
    $this->setMethod('post');

    $this->_addAwsCredentialsFieldset();
    $this->_addHitDetailsFieldset();

    $this->addElement('checkbox', 'sandbox', array(
      'label'      => 'Test',
      'title'      => 'Create the HIT in Amazon\'s Mechanical Turk Sandbox',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('placement' => 'APPEND', 'class' => 'right'))
      ),
      'checked'    => 'checked'
    ));

    $this->addElement('hidden', 'signature', array(
      'filters'    => array('HtmlEntities', 'StringTrim'),
      'required'   => true,
      'decorators' => array('ViewHelper'),
    ));

    $this->addElement('hidden', 'timestamp', array(
      'filters'    => array('HtmlEntities', 'StringTrim'),
      'required'   => true,
      'decorators' => array('ViewHelper'),
    ));

    $this->addElement('submit', 'create', array(
      'required'   => false,
      'ignore'     => true,
      'label'      => 'Create',
      'decorators' => array('ViewHelper'),
    ));
  }

  public function _addAwsCredentialsFieldset() {
    $this->addElement('text', 'accessKeyId', array(
      'filters'    => array('HtmlEntities', 'StringTrim'),
      'validators' => array(),
      'required'   => true,
      'label'      => 'Access key ID:',
      'class'      => 'extended'
    ));

    $this->addElement('text', 'accessKey', array(
      'filters'    => array('HtmlEntities', 'StringTrim'),
      'validators' => array(),
      'required'   => true,
      'label'      => 'Secret access key:',
      'class'      => 'extended',
      'description' => 'Your secret access key will never be stored or transmitted. It is only used by your browser to generate your AWS signature.'
    ));

    $this->addDisplayGroup(array('accessKeyId', 'accessKey'), 'mturkExternalHitCreateForm_awsAuth', array(
      'legend'     => 'Amazon Web Services Credentials',
    ));
  }

  public function _addHitDetailsFieldset() {
    $this->addElement('text', 'title', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => true,
      'label'       => 'Title:',
      'title'       => 'The HIT\'s title. Will be displayed to public through Amazon\'s Mechanical Turk Worker interface.',
      'class'       => 'extended',
      'placeholder' => 'The name of your HIT as it will be seen in MTurk',
    ));

    $this->addElement('text', 'surveyUrl', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => true,
      'label'       => 'Survey URL:',
      'title'       => 'The public URL for your Qualtrics.com survey.',
      'class'       => 'extended',
      'placeholder' => 'https://qtrial.qualtrics.com/SE/?SID=[SURVEY_ID]',
    ));

    $this->addElement('textarea', 'description', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => true,
      'label'       => 'Description:',
      'title'       => 'Description of the HIT. Will be displayed to public through Amazon\'s Mechanical Turk Worker interface.',
      'class'       => 'extended',
      'rows'        => 2,
      'placeholder' => 'Describe your survey...',
    ));

    $this->addElement('textarea', 'keywords', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => false,
      'label'       => 'Keywords:',
      'title'       => 'A comma-delimited list of keywords describing the HIT. Although this field is optional, it can help workers find your HIT when searching through Amazon\'s Mechanical Turk Worker interface.',
      'class'       => 'extended',
      'rows'        => 2,
      'placeholder' => 'lorem, ipsum, dolor (optional)',
    ));

    $this->addElement('text', 'frameHeight', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => true,
      'label'       => 'Height of survey frame:',
      'title'       => 'When viewed from within MTurk, your survey will be displayed within a frame. The default height of this frame is 400 pixels which is quite small. It is really only adequate for surveys that display one question at a time. Larger values allow the user to see more of the survey page thereby lessening the amount of scrolling required. The value required by individual surveys will vary depending on their contents. Please utilize the &ldquo;Test&rdquo; feature below to experiment with different height values.',
      'placeholder' => '800',
    ));

    $this->addElement('text', 'max', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => true,
      'label'       => 'Max # of participants:',
      'title'       => 'Maximum number of HIT completions.',
      'placeholder' => '1000'
    ));

    $this->addElement('text', 'reward', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => true,
      'label'       => 'Reward amount:',
      'title'       => 'Amount to be paid to each worker (in USD)',
      'description' => 'USD',
      'placeholder' => '0.50'
    ))->getDecorator('Description')->setOption('class', 'description beside');

    $this->addElement(new TimeSpan('duration', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => true,
      'label'       => 'Duration:',
      'title'       => 'Amount of time worker has to complete the HIT',
      'units'       => TimeSpan::HOURS,
      'placeholder' => '1'
    )));

    $this->addElement(new TimeSpan('autoApprovalDelay', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => true,
      'label'       => 'Auto-approval delay:',
      'title'       => 'The amount of time (in seconds) after a completed HIT has been submitted, after which the HIT is automatically approved for payment.',
      'units'       => TimeSpan::HOURS,
      'placeholder' => '1'
    )));

    $this->addElement(new TimeSpan('lifetime', array(
      'filters'     => array('HtmlEntities', 'StringTrim'),
      'validators'  => array(),
      'required'    => true,
      'label'       => 'Lifetime:',
      'title'       => 'The amount of time after which the HIT will no longer be available to AMT workers.',
      'placeholder' => '1'
    )));

    $this->addElement('Select', 'countriesFlag', array(
      'filters'      => array('HtmlEntities', 'StringTrim'),
      'validators'   => array(),
      'required'     => false,
      'label'        => 'Country:',
      'title'        => 'The list of countries in which AMT workers must reside (or not reside).',
      #'placeholder'  => 'Select one or more countries',
      'description'  => 'the following countries:',
      'multiOptions' => array('include' => 'Include', 'exclude' => 'Exclude'),
      'class'        => 'flag'
    ))->getDecorator('Description')->setOption('class', 'description beside');

    $locale = new Zend_Locale('en_US');
    $countries = ($locale->getTranslationList('Territory', 'en', 2));
    asort($countries, SORT_LOCALE_STRING);
    #\Rexmac\Zyndax\Log\Logger::debug(var_export($countries, true));

    $this->addElement('Multiselect', 'countries', array(
      'filters'      => array('HtmlEntities', 'StringTrim'),
      'validators'   => array(),
      'required'     => false,
      'label'        => '&nbsp;',
      'title'        => 'The list of countries in which AMT workers must reside (or not reside).',
      'placeholder'  => 'Select one or more countries',
      'multiOptions' => $countries,
    ));

    $this->addDisplayGroup(array('title', 'surveyUrl', 'description', 'keywords', 'frameHeight', 'max', 'reward', 'duration', 'autoApprovalDelay', 'lifetime', 'countriesFlag', 'countries'), 'mturkExternalHitCreateForm_hitDetails', array(
      'legend' => 'HIT Details',
    ));
  }
}
