<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use Laminas\Mail\Message;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Part as MimePart;
use Laminas\Mail\Transport\Sendmail;
use Laminas\View\Model\ViewModel;
use MelisCore\Service\MelisGeneralService;

class MelisEngineSendMailService extends MelisGeneralService implements MelisEngineSendMailInterface
{

	public function sendEmail(
		$email_template_path,
		$email_from,
		$email_from_name,
		$email_to,
		$email_to_name,
		$email_subject,
		$email_content,
		$email_content_tag_replace = array(),
		$email_reply_to = null
	) {

		// $config = $this->getServiceManager()->get('config');

		// $default = ['mailTemplate' => $config['view_manager']['template_map']['MelisEngine/emailLayout']];
		// // email template
		// $tplPathStack = isset($config['view_manager']['template_map'][$email_template_path]) ?
		// 	['mailTemplate' => $config['view_manager']['template_map'][$email_template_path]] : $default;

		// $view       = new \Laminas\View\Renderer\PhpRenderer();
		// $resolver   = new \Laminas\View\Resolver\TemplateMapResolver();
		$viewRenderer = $this->getServiceManager()->get('ViewRenderer');
		$viewModel  = new ViewModel();
		$viewModel->setTerminal(true);
		$viewModel->setTemplate($email_template_path);

		// $resolver->setMap($tplPathStack);
		// $view->setResolver($resolver);

		$emailConfig = $this->makeArrayFromParameters(__METHOD__, func_get_args());

		foreach ($email_content_tag_replace as $key => $value) {
			$emailConfig['email_content'] =  str_ireplace('[' . $key . ']', $value, $emailConfig['email_content']);
		}

		// $viewModel->setTemplate('mailTemplate')->setVariables($emailConfig);
		$viewModel->setVariables($emailConfig);

		$html = new MimePart($viewRenderer->render($viewModel));
		$html->type = 'text/html';
		$html->charset = 'UTF-8';

		$body = new MimeMessage();

		$body->addPart($html);

		$message = new Message();
		$message->setFrom($email_from, $email_from_name);
		$message->addTo($email_to);
		$message->setSubject($email_subject);
		$message->setEncoding('UTF-8');
		$message->setSender($email_from, $email_from_name);
		$message->setBody($body);
		if ($email_reply_to != null) {
			$message->addReplyTo($email_reply_to);
		}

		$transport = new Sendmail();
		$transport->send($message);
	}
}
