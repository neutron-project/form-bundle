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

use Symfony\Component\Validator\Constraints\Image;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Console\Input\ArrayInput;

use Symfony\Bundle\FrameworkBundle\Console\Application;

use Imagine\Exception\RuntimeException;

use Imagine\Exception\InvalidArgumentException;

use Imagine\Exception\OutOfBoundsException;

use Imagine\Image\Point;

use Imagine\Image\Box;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * This controller handles image manipulations
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class ImageController extends Controller
{

    /**
     * This actions is responsible for validating, uploading of images
     */
    public function uploadAction ()
    {
        if ($this->getRequest()->isMethod('POST') && $this->getRequest()->files->get('file')) {

            $imageManager = $this->container->get('neutron_form.manager.image_manager');

            $handle = $this->getRequest()->files->get('file');
            $name = uniqid() . '.' . $handle->guessExtension();

            $validate = $this->validateImage($handle, $this->getConfigs());

            if ($validate !== true) {
                return new JsonResponse(array(
                    'success' => false,
                    'err_msg' => $validate
                ));
            }

            $handle->move($imageManager->getTempOriginalDir(), $name);
            $this->normalizeImage($imageManager->getPathOfTempOriginalImage($name));
            $imageManager->makeImageCopy($name);
            
            $hash = $imageManager->getHashOfTempImage($name);

            return new JsonResponse(array(
                'success' => true,
                'name' => $name,
                'hash' => $hash
            ));
        }

    }

    /**
     * This action performs image cropping
     */
    public function cropAction ()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {

            $imageManager = $this->container->get('neutron_form.manager.image_manager');
            $name = $this->getRequest()->get('name');

            $x = $this->getRequest()->get('x', false);
            $y = $this->getRequest()->get('y', false);
            $w = $this->getRequest()->get('w', false);
            $h = $this->getRequest()->get('h', false);


            try {
                $imagine = $this->get('imagine');
                $image = $imagine->open($imageManager->getPathOfTempImage($name));
            } catch (InvalidArgumentException $e) {
                return new JsonResponse(array(
                    'success' => false,
                    'err_msg' => $this->get('translator')->trans('exception.image.open', array('name' => $name), 'NeutronFormBundle')
                ));
            }

            try {
                $image->crop(new Point($x, $y), new Box($w, $h))->save($imageManager->getPathOfTempImage($name));
            } catch (OutOfBoundsException $e) {
                return new JsonResponse(array(
                    'success' => false,
                    'err_msg' => $this->get('translator')->trans('exception.image.out_of_bounds', array('name' => $name), 'NeutronFormBundle')
                ));
            } catch (InvalidArgumentException $e) {
                return new JsonResponse(array(
                    'success' => false,
                    'err_msg' => $this->get('translator')->trans('exception.image.crop', array('name' => $name), 'NeutronFormBundle')
                ));
            }

            $hash = $imageManager->getHashOfTempImage($name);

            return new JsonResponse(array(
                'success' => true,
                'name' => $name,
                'hash' => $hash
            ));

        }
    }

    /**
     * This action performs image rotation
     */
    public function rotateAction ()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $imageManager = $this->container->get('neutron_form.manager.image_manager');
            $name = $this->getRequest()->get('name');

            $imagine = $this->get('imagine');
            $image = $imagine->open($imageManager->getPathOfTempImage($name));
            $image->rotate(90)->save($imageManager->getPathOfTempImage($name));
            
            $hash = $imageManager->getHashOfTempImage($name);

            return new JsonResponse(array(
                'success' => true,
                'name' => $name,
                'hash' => $hash
            ));
        }
    }

    /**
     * This action revert the image to original one
     */
    public function resetAction ()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {

            $imageManager = $this->container->get('neutron_form.manager.image_manager');
            $name = $this->getRequest()->get('name', false);

            $imageManager->makeImageCopy($name);         

            $hash = $imageManager->getHashOfTempImage($name);

            return new JsonResponse(array(
                'success' => true,
                'name' => $name,
                'hash' => $hash
            ));
        }
    }

    /**
     * Validates image and return true on success and
     * array of error messages on failure
     *
     * @param UploadedFile $handle
     */
    private function validateImage (UploadedFile $handle, array $configs)
    {
        $maxSize = $configs['maxSize'];
        $extensions = $configs['extensions'];
        $imageConstraint = new Image();
        $imageConstraint->minWidth = $configs['minWidth'];
        $imageConstraint->minHeight = $configs['minHeight'];
        $imageConstraint->maxSize = $maxSize;
        $imageConstraint->mimeTypes =
            array_map(function($item){return 'image/' . $item;}, explode( ',',  $extensions));
        $errors = $this->container->get('validator')->validateValue($handle, $imageConstraint);

        if (count($errors) == 0) {
            return true;
        } else {
            return $this->container->get('translator')
                ->trans(/** @Ignore */$errors[0]->getMessageTemplate(), $errors[0]->getMessageParameters());
        }
    }

    /**
     * Normalize image to given width or height
     *
     * @param string $imagePath
     * @param array $options
     */
    private function normalizeImage ($imagePath)
    {
        $options = $this->container->getParameter('neutron_form.plupload.configs');
        $imagine = $this->get('imagine');
        $image = $imagine->open($imagePath);
        $size = $image->getSize();
        $box = new \Imagine\Image\Box($size->getWidth(), $size->getHeight());

        if ($size->getWidth() >= $size->getHeight() && $size->getWidth() > $options['normalize_width']) {
            $image->resize($box->widen($options['normalize_width']))->save($imagePath);
        } elseif ($size->getWidth() < $size->getHeight() && $size->getHeight() > $options['normalize_height']) {
            $image->resize($box->heighten($options['normalize_height']))->save($imagePath);
        }

    }

    /**
     * Gets Image configs
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
