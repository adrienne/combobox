<?php
namespace Craft;

class ComboboxFieldType extends DropdownFieldType
{
	public function getName()
	{
		return Craft::t('Combobox');
	}

	public function getOptionsSettingsLabel()
	{
		return Craft::t('Combobox Options');
	}

	protected function defineSettings()
	{
		return array(
			'options' => array(AttributeType::Mixed, 'default' => array()),
			'newPersistent' => AttributeType::Bool,
		);
	}

	public function getSettingsHtml()
	{
		$html = parent::getSettingsHtml();
		$html .= craft()->templates->renderMacro('_includes/forms.html', 'checkboxField', array(
			array(
				'label' => Craft::t('Make new options persistent?'),
				'id' => 'newPersistent',
				'name' => 'newPersistent',
				'checked' => $this->getSettings()->newPersistent,
			)
		));
		return $html;
	}

	public function getInputHtml($name, $value)
	{
		$options = array();
		$options = $this->getOptions();

		// If this is a new entry, look for a default option
		if ($this->isFresh())
		{
			foreach ($options as $option)
			{
				if (!empty($option['default']))
				{
					$value = $option['value'];
					break;
				}
			}
		}

		$existing = false;
		foreach ($options as $option)
		{
			if ($option['value'] == $value)
			{
				$existing = true;
				break;
			}
		}
		if (!$existing)
		{
			$options[] = array(
				'label'   => $value,
				'value'   => $value,
				'default' => false,
			);
		}

		return craft()->templates->render('combobox/combobox', array(
			'id'      => craft()->templates->formatInputId($name),
			'name'    => $name,
			'value'   => $value,
			'options' => $options
		));
	}

	public function prepValueFromPost($value)
	{
		if ($this->getSettings()->newPersistent)
		{
			$options = $this->getOptions();

			$existing = false;
			foreach ($options as $option)
			{
				if ($option['value'] == $value)
				{
					$existing = true;
					break;
				}
			}
			if (!$existing)
			{
				$options[] = array(
					'label'   => $value,
					'value'   => $value,
					'default' => false,
				);
			}

			// Make new option persistent
			$settings = $this->model->settings;
			$settings['options'] = $options;
			$this->model->settings = $settings;
			craft()->fields->saveField($this->model);
		}

		return $value;
	}
}
