<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.07.2015
 * Time: 16:29
 */
namespace webapp\components;

use Exception;
use system\core\App;
use system\core\base\Component;
use system\core\mail\PHPMailer;

class EmailComponent extends Component
{

	/**
	 * @var null|PHPMailer $mailer
	 */
	private $mailer = null;
	private $error = [
		'mailer' => null,
		'errors' => null,
	];

	public function newLetter()
	{
		$mailConfig   = App::getConfig()['components']['mail'];
		$this->mailer = new PHPMailer();
		$this->mailer->isSMTP();
		$this->mailer->SMTPDebug = 0;
		$this->mailer->SMTPAuth  = true;
		$this->mailer->setFrom($mailConfig['login'], $mailConfig['login']);
		$this->mailer->Host       = $mailConfig['host'];
		$this->mailer->Username   = $mailConfig['login'];
		$this->mailer->Password   = $mailConfig['password'];
		$this->mailer->SMTPSecure = $mailConfig['SMTPSecure'];
		$this->mailer->Port       = $mailConfig['port'];
		return $this;
	}

	/**
	 * Добавить адресата для письма
	 * @param $mail
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function addAddress($mail)
	{
		$this->checkMailer();
		$this->mailer->addAddress($mail, $mail);
		return $this;
	}

	/**
	 * Добавить тему для письма
	 * @param $subject
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function setSubject($subject)
	{
		$this->checkMailer();
		$this->mailer->Subject = $subject;
		return $this;
	}

	/**
	 * Добавить текст письма
	 * @param $body
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function setBody($body)
	{
		$this->checkMailer();
		$this->mailer->Body = $body;
		return $this;
	}

	/**
	 * Отправить письмо
	 * @return bool
	 * @throws Exception
	 */
	public function send()
	{
		$this->checkMailer();
		if (!$this->mailer->preSend()) {
			$this->error['mailer'] = $this->mailer;
			$this->error['errors'] = $this->mailer->ErrorInfo;
			return false;
		}
		$this->mailer->send();
		$this->mailer = null;
		return true;
	}

	private function checkMailer()
	{
		if(is_null($this->mailer)){
			throw new Exception('Не создано новое письмо методом компонента newLetter.');
		}
	}
}
