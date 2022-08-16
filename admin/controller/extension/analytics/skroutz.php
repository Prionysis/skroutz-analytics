<?php

/**
 * Shopflix Analytics
 * @author Prionysis
 * @website https://github.com/Prionysis
 * @version 1.0
 */

class ControllerExtensionAnalyticsSkroutz extends Controller
{
	private $error = [];

	public function index()
	{
		$this->load->language('extension/analytics/skroutz');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('analytics_skroutz', $this->request->post, $this->request->get['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics', true));
		}

		// Errors
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		if (!isset($this->request->get['store_id'])) {
			$this->request->get['store_id'] = 0;
		}

		// Breadcrumbs
		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics', true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/analytics/skroutz', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true)
		];

		$data['action'] = $this->url->link('extension/analytics/skroutz', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics', true);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['analytics_skroutz_code'])) {
			$data['analytics_skroutz_code'] = $this->request->post['analytics_skroutz_code'];
		} else {
			$data['analytics_skroutz_code'] = $this->model_setting_setting->getSettingValue('analytics_skroutz_code', $this->request->get['store_id']);
		}

		if (isset($this->request->post['analytics_skroutz_status'])) {
			$data['analytics_skroutz_status'] = $this->request->post['analytics_skroutz_status'];
		} else {
			$data['analytics_skroutz_status'] = $this->model_setting_setting->getSettingValue('analytics_skroutz_status', $this->request->get['store_id']);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/analytics/skroutz', $data));
	}

	protected function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/analytics/skroutz')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['analytics_skroutz_code'])) {
			$this->error['code'] = $this->language->get('error_code');
		}

		return !$this->error;
	}

	public function install()
	{
		$this->load->language('extension/analytics/skroutz');

		if (!$this->user->hasPermission('modify', 'extension/extension/analytics')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/event');
		$this->model_setting_event->addEvent('analytics_skroutz', 'catalog/view/common/success/after', 'extension/analytics/skroutz/success');
	}

	public function uninstall()
	{
		$this->load->language('extension/analytics/skroutz');

		if (!$this->user->hasPermission('modify', 'extension/extension/analytics')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('analytics_skroutz');
	}
}