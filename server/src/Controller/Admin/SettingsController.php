<?php

namespace App\Controller\Admin;

use App\Form\ModifySettingsForm;
use Craue\ConfigBundle\CacheAdapter\CacheAdapterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class SettingsController extends \Craue\ConfigBundle\Controller\SettingsController {
    public function modifySettingAction(CacheAdapterInterface $cache, FormFactoryInterface $formFactory, Request $request,
                                 SessionInterface $session, EntityManagerInterface $em, TranslatorInterface $translator): Response {
        $repo = $em->getRepository($this->getParameter('craue_config.entity_name'));
        $allStoredSettings = $repo->findAll();

        $formData = [
            'settings' => $allStoredSettings,
        ];

        $form = $formFactory->create(ModifySettingsForm::class, $formData);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // update the cache
                foreach ($formData['settings'] as $formSetting) {
                    $storedSetting = $this->getSettingByName($allStoredSettings, $formSetting->getName());
                    if ($storedSetting !== null) {
                        $cache->set($storedSetting->getName(), $storedSetting->getValue());
                    }
                }

                $em->flush();

                if ($session instanceof Session) {
                    $session->getFlashBag()->set('notice', $translator->trans('settings_changed', [], 'CraueConfigBundle'));
                }

                return $this->redirectToRoute($this->getParameter('craue_config.redirectRouteAfterModify'));
            }
        }

        return $this->render('admin/settings/modify.html.twig', [
            'form' => $form->createView(),
            'sections' => $this->getSections($allStoredSettings),
        ]);
    }
}