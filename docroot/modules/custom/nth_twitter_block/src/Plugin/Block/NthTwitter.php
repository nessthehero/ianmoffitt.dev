<?php

	namespace Drupal\nth_twitter_block\Plugin\Block;

	use Drupal\Core\Block\BlockBase;
	use Drupal\Core\Form\FormStateInterface;

	/**
	 * Provides a 'Example: configurable text string' block.
	 *
	 * Drupal\Core\Block\BlockBase gives us a very useful set of basic functionality
	 * for this configurable block. We can just fill in a few of the blanks with
	 * defaultConfiguration(), blockForm(), blockSubmit(), and build().
	 *
	 * @Block(
	 *   id = "nth_twitter",
	 *   admin_label = @Translation("Twitter Expander")
	 * )
	 */
	class NthTwitter extends BlockBase {

		/**
		 * {@inheritdoc}
		 *
		 * This method sets the block default configuration. This configuration
		 * determines the block's behavior when a block is initially placed in
		 * a
		 * region. Default values for the block configuration form should be
		 * added to the configuration array. System default configurations are
		 * assembled in BlockBase::__construct() e.g. cache setting and block
		 * title visibility.
		 *
		 * @see \Drupal\block\BlockBase::__construct()
		 */
		public function defaultConfiguration() {
			return [
				'heading'        => $this->t(''),
				'twitter_widget' => $this->t(''),
			];
		}

		/**
		 * {@inheritdoc}
		 *
		 * This method defines form elements for custom block configuration.
		 * Standard block configuration fields are added by
		 * BlockBase::buildConfigurationForm()
		 * (block title and title visibility) and BlockFormController::form()
		 * (block visibility settings).
		 *
		 * @see \Drupal\block\BlockBase::buildConfigurationForm()
		 * @see \Drupal\block\BlockFormController::form()
		 */
		public function blockForm($form, FormStateInterface $form_state) {

			$form['nth_twitter'] = [
				'#type'  => 'fieldset',
				'#title' => t('Twitter Expand Widget settings'),
				'#tree'  => TRUE,
			];

			$form['nth_twitter']['heading'] = [
				'#type'          => 'textfield',
				'#title'         => $this->t('Heading'),
				'#description'   => $this->t('Heading of block'),
				'#default_value' => $this->configuration['heading'],
			];

			$form['nth_twitter']['twitter_widget'] = [
				'#type'          => 'textarea',
				'#rows'          => 10,
				'#resizeable'    => 'vertical',
				'#title'         => $this->t('Twitter Widget'),
				'#description'   => $this->t('HTML of Twitter Widget'),
				'#default_value' => $this->configuration['twitter_widget'],
			];

			return $form;
		}

		/**
		 * {@inheritdoc}
		 *
		 * This method processes the blockForm() form fields when the block
		 * configuration form is submitted.
		 *
		 * The blockValidate() method can be used to validate the form
		 * submission.
		 */
		public function blockSubmit($form, FormStateInterface $form_state) {

			$this->configuration['heading'] = $form_state->getValue([
				'nth_twitter',
				'heading',
			]);
			$this->configuration['twitter_widget'] = $form_state->getValue([
				'nth_twitter',
				'twitter_widget',
			]);

		}

		/**
		 * {@inheritdoc}
		 */
		public function build() {
			$heading = $this->configuration['heading'];
			$twitter_widget = $this->configuration['twitter_widget'];

			return [
				'#cache'   => [
					'nth:twitter'
				],
				'#theme'   => 'nth_twitter',
				'#heading' => $heading,
				'#widget' => $twitter_widget,
			];
		}

	}