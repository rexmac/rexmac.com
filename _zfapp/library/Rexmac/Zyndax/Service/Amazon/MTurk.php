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
 * @subpackage Service_Amazon
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/license/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
namespace Rexmac\Zyndax\Service\Amazon;


use \Exception,
    Rexmac\Zyndax\Log\Logger,
    \SimpleXMLElement;

/**
 * Amazon Mechanical Turk service class
 *
 * @category   Zyndax
 * @package    Rexmac_Zyndax
 * @subpackage Service_Amazon
 * @copyright  Copyright (c) 2011-2012 Rex McConnell (http://rexmac.com/)
 * @license    http://rexmac.com/license/mit.txt MIT License
 * @author     Rex McConnell <rex@rexmac.com>
 */
class MTurk {

  const SERVICE_NAME = 'AWSMechanicalTurkRequester';
  const URL          = 'http://mechanicalturk.amazonaws.com/';
  const TEST_URL     = 'http://mechanicalturk.sandbox.amazonaws.com/';

  public static $TESTING = false;

  /**
   * Create AMT HIT
   *
   * @param array $options
   * @return string XML response from AMT
   */
  public static function createHit($options) {
    return self::_sendRequest('CreateHIT', $options);
  }

  /**
   * Create AMT external HIT
   *
   * @param array $options
   * @return string XML response from AMT
   */
  public static function createExternalHit($options) {
    if(!isset($options['Question']) && isset($options['ExternalUrl'])) {
      $frameHeight = 400;
      if(isset($options['FrameHeight'])) {
        $frameHeight = $options['FrameHeight'];
        unset($options['FrameHeight']);
      }
      $options['Question'] = '<ExternalQuestion xmlns="http://mechanicalturk.amazonaws.com/AWSMechanicalTurkDataSchemas/2006-07-14/ExternalQuestion.xsd">' .
        '<ExternalURL>' . $options['ExternalUrl'] . '</ExternalURL>' .
        '<FrameHeight>' . $frameHeight . '</FrameHeight>' .
        '</ExternalQuestion>';
      unset($options['ExternalUrl']);
    }
    $response = self::createHit($options);
    if($response->HIT->Request->IsValid == 'True') {
      return array(
        'hitId'     => $response->HIT->HITId,
        'hitTypeId' => $response->HIT->HITTypeId
      );
    }
    throw new Exception('Unknown error occurred: ' . $response);
  }

  /**
   * Get URL for accessing HIT management interface
   *
   * @param string $hitId ID of HIT
   * @param bool $sandbox Set to TRUE to retrieve URL for managing a sandboxed HIT. Default FALSE.
   * @return string URL
   */
  public static function getHitManagementUrl($hitId, $sandbox = false) {
    return sprintf('https://requester%s.mturk.com/mturk/manageHIT?HITId=%s',
      $sandbox ? 'sandbox' : '',
      $hitId
    );
  }

  /**
   * Get URL for accessing HIT preview interface
   *
   * @param string $hitTypeId ID of HIT type
   * @param bool $sandbox Set to TRUE to retrieve URL for previewing a sandboxed HIT. Default FALSE.
   * @return string URL
   */
  public static function getHitPreviewUrl($hitId, $sandbox = false) {
    return sprintf('https://%s.mturk.com/mturk/preview?groupId=%s',
      $sandbox ? 'workersandbox' : 'www',
      $hitTypeId
    );
  }

  /**
   * Create URL for an AMT request
   *
   * @param string $operation AMT operation
   * @param mixed $data
   * @return string URL
   */
  private static function _createUrl($operation, $data = null) {
    if(is_array($data) && isset($data['Sandbox'])) {
      self::$TESTING = !!$data['Sandbox'];
      unset($data['Sandbox']);
    }
    $url = (self::$TESTING ? self::TEST_URL : self::URL) .
      '?Service='.urlencode(self::SERVICE_NAME) .
      '&Version='.urlencode('2008-08-02') .
      '&Operation='.urlencode($operation);

    if(is_array($data)) {
      Logger::debug(
        __METHOD__ . ':: BEGIN DATA ::' . PHP_EOL .
        var_export($data, true) . PHP_EOL .
        __METHOD__ . '::: END DATA :::'
      );
      foreach($data as $k => $v) {
        $url .= '&'.$k.'='.urlencode($v);
      }
    }
    Logger::debug(
      __METHOD__ . ':: BEGIN URL ::' . PHP_EOL .
      $url . PHP_EOL .
      __METHOD__ . '::: END URL :::'
    );
    return $url;
  }

  /**
   * Send request to AMT
   *
   * @param string $operation AMT operation
   * @param mixed $data
   * @return string XML response from AMT
   */
  private static function _sendRequest($operation, $data = null) {
    $url = self::_createUrl($operation, $data);
    $xml = simplexml_load_file($url);
    #$xml = false;
    Logger::debug(
      __METHOD__ . ':: BEGIN RESPONSE :: ' . PHP_EOL .
      var_export($xml, true) . PHP_EOL .
      __METHOD__ . '::: END RESPONSE :::'
    );

    if(!$xml || !($xml instanceof SimpleXMLElement)) {
      throw new Exception('Received invalid response from Amazon');
    }

    if($xml->OperationRequest->Errors) {
      $error = $xml->OperationRequest->Errors->Error;
      throw new Exception($error->Message, $error->Code);
    }

    $result = $xml->{$operation.'Result'};
    if($result->Request && $result->Request->Errors) {
      $error = $result->Request->Errors->Error;
      throw new Exception($error->Message, $error->Code);
    }

    return $xml;
  }
}
