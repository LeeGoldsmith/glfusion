<?php
/**
*   SFS.Misc.class.php
*   Special examiner to check email and IP addresses during registration.
*   Checks stopforumspam.com and, if the result is positive, addes the
*   email and/or IP address to the spamx table.
*
*   @author     Lee Garner <lee@leegarner.com>
*   @copyright  Copyright (c) 2010 Lee Garner <lee@leegarner.com>
*   @package    spamx
*   @subpackage Modules
*   @version    1.0.0
*   @license    http://opensource.org/licenses/gpl-2.0.php
*               GNU Public License v2 or later
*/

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own!');
}

/**
* Include Abstract Examine Class
*/
require_once $_CONF['path'] . 'plugins/spamx/' . 'BaseCommand.class.php';

/**
* Examines Comment according to Personal IP Blacklist
*
* @author Tom Willett, tomw AT pigstye DOT net
*
* @package Spam-X
*
*/
class SFSreg extends BaseCommand {
    /**
     * No Constructor - use BaseCommand constructor
     */

    /**
     * The execute method examines the IP address a comment is coming from,
     * comparing it against a blacklist of banned IP addresses.
     *
     * @param   string  $comment    Comment text to examine
     * @return  int                 0: no spam, else: spam detected
     */
    function execute($email)
    {
        global $result;

        $result = $this->_process($email, $_SERVER['REMOTE_ADDR']);
        return $result;
    }


    /**
     * Private internal method, this actually processes a given ip
     * address against a blacklist of IP regular expressions.
     *
     * @param   strint  $ip     IP address of comment poster
     * @return  int             0: no spam, else: spam detected
     * @access  private
     */
    function _process($email, $ip)
    {
        global $_TABLES, $LANG_SX00;

        require_once 'HTTP/Request2.php';

// for local development you need to uncomment this - stopforumspam.com
//  thinks that 127.0.0.1 is a spammer address
//        if ( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
//            return 0;
//        }

        $em = $email;

        $request = new HTTP_Request2('http://www.stopforumspam.com/api',
                                     HTTP_Request2::METHOD_GET, array('use_brackets' => true));
        $url = $request->getUrl();

        $checkData['f'] = 'serial';
        if ( $ip != '' ) {
            $checkData['ip'] = $ip;
        }
        if ( $em != '' ) {
            $checkData['email'] = $em;
        }
        $url->setQueryVariables($checkData);
        $url->setQueryVariable('cmd', 'display');
        try {
            $response = $request->send();
        } catch (Exception $e) {
            return 0;
        }
        $result = @unserialize($response->getBody());

        if (!$result) return 0;     // invalid data, assume ok

        if ( (isset($result['email']) && $result['email']['appears'] == 1) ||
           ($result['ip']['appears'] == 1 ) ) {
            return 1;
        }
        // Passed the checks
        return 0;
    }
}

?>