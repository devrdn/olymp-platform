<?php

namespace App\Form;

use Craue\ConfigBundle\Entity\SettingInterface;
use Craue\ConfigBundle\Form\Type\SettingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

class ModifySettingsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $settingsForm = $builder->create('settings', FormType::class);

        foreach ($options['data']['settings'] as $setting) {
            /* @var $setting SettingInterface */
            $settingsForm->add($setting->getName(), SettingType::class, [
                'data' => $setting,
            ]);
        }

        $builder->add($settingsForm);
    }

    public function getBlockPrefix(): string
    {
        return 'craue_config_modifySettings';
    }

}