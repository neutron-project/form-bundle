<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Console\Input\ArrayInput;

use Symfony\Bundle\FrameworkBundle\Console\Application;

use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * This controller handles image manipulations
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class FileController extends Controller
{

    /**
     * This actions is responsible for validation, uploading and clearing unused files
     */
    public function uploadAction ()
    { 
        if ($this->getRequest()->isMethod('POST') && $this->getRequest()->files->get('file')) {

            $fileManager = $this->container->get('neutron_form.manager.file_manager');
            $options = $this->container->getParameter('neutron_form.plupload.configs');
            $handle = $this->getRequest()->files->get('file');
            $name = uniqid() . '.' . $handle->guessExtension();

            $validate = $this->validateFile($handle);

            if ($validate !== true) {
                return new JsonResponse(array(
                    'success' => false,
                    'err_msg' => $validate
                ));
            }

            $handle->move($fileManager->getTempDir(), $name);
            $hash = $fileManager->getHashOfTempFile($name);

            return new JsonResponse(array(
                'success' => true,
                'name' => $name,
                'hash' => $hash,
            ));
        }
    }



    /**
     * Validates file and return true on success and
     * array of error messages on failure
     *
     * @param UploadFile $handle
     * @return boolean | string
     */
    private function validateFile (UploadedFile $handle)
    {
        $configs = $this->getConfigs();
        $maxSize = $configs['maxSize'];
        $extensions = $configs['extensions'];
        $fileConstraint = new File();
        $fileConstraint->maxSize = $maxSize;
        $fileConstraint->mimeTypes = explode( ',',  $extensions);

        $errors = $this->container->get('validator')->validateValue($handle, $fileConstraint);

        if (count($errors) == 0) {
            return true;
        } else {
            return $this->container->get('translator')
                ->trans(/** @Ignore */$errors[0]->getMessageTemplate(), $errors[0]->getMessageParameters());
        }
    }

    /**
     * Gets File configs
     *
     * @return array
     */
    private function getConfigs()
    {
    	$session = $this->get('session');
    	if (!$session->has($this->getRequest()->get('neutron_id', false))){
    		throw new \RuntimeException('Invalid request');
    	}
    	
    	return $session->get($this->getRequest()->get('neutron_id'));
    }

}
