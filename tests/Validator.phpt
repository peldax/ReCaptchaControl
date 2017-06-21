<?php

use Tester\Assert;
use Nette\Forms\Form;
use ReCaptchaControl\Control;
use ReCaptchaControl\Renderer;
use Nette\Http\RequestFactory;
use ReCaptchaControl\Validator;
use ReCaptchaControl\Http\RequestDataProvider;

require_once __DIR__ . '/bootstrap.php';


test(function () {

	// multiple validation
	$httpRequest = (new RequestFactory())->createHttpRequest();
	$requestDataProvider = new RequestDataProvider($httpRequest);

	$validator = new Validator($requestDataProvider, RECAPTCHA_SECRETKEY);
	$renderer = new Renderer(RECAPTCHA_SITEKEY);

	Control::register($validator, $renderer);

	$form = new Form;
	$control = $form->addRecaptcha('recaptcha');

	Assert::error(function () use ($control) {
		$control->addRule([Control::class, 'validateValid']);

	}, E_USER_DEPRECATED);

	Assert::error(function () use ($control) {
		$control->addRule(Control::class . '::validateValid');

	}, E_USER_DEPRECATED);

});
