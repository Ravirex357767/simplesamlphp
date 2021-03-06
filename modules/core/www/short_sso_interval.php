<?php

/**
 * Show a warning to an user about the SP requesting SSO a short time after
 * doing it previously.
 *
 * @package SimpleSAMLphp
 */

if (!array_key_exists('StateId', $_REQUEST)) {
	throw new \SimpleSAML\Error\BadRequest('Missing required StateId query parameter.');
}
$id = $_REQUEST['StateId'];
$state = \SimpleSAML\Auth\State::loadState($id, 'core:short_sso_interval');
$session = \SimpleSAML\Session::getSessionFromRequest();

if (array_key_exists('continue', $_REQUEST)) {
	// The user has pressed the continue/retry-button
	\SimpleSAML\Auth\ProcessingChain::resumeProcessing($state);
}

$globalConfig = \SimpleSAML\Configuration::getInstance();
$t = new \SimpleSAML\XHTML\Template($globalConfig, 'core:short_sso_interval.php');
$t->data['target'] = \SimpleSAML\Module::getModuleURL('core/short_sso_interval.php');
$t->data['params'] = array('StateId' => $id);
$t->data['trackId'] = $session->getTrackID();
$this->data['header'] = $this->t('{core:short_sso_interval:warning_header}');
$this->data['autofocus'] = 'contbutton';
$t->show();
