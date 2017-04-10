<?php
	
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

interface MelisEngineSendMailInterface 
{
    /**
     * This method sends email
     * @param string $email_template_path email template path
     * @param string $email_from email address of the sender
     * @param string $email_from_name name of the sender
     * @param string $email_to email address of the recipient
     * @param string $email_to_name of the recipient
     * @param string $email_subject subject of the email
     * @param string $email_content content of  the email
     * @param array $email_content_tag_replace tag replacement
     * @param string $email_reply_to email to reply to
     */
	public function sendEmail($email_template_path, $email_from, $email_from_name, 
	                           $email_to, $email_to_name, $email_subject, 
	                           $email_content, $email_content_tag_replace = array(), $email_reply_to = null);
}