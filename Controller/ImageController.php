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

use Symfony\Component\Validator\Constraints\Image;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Console\Input\ArrayInput;

use Symfony\Bundle\FrameworkBundle\Console\Application;

use Imagine\Exception\RuntimeException;

use Imagine\Exception\InvalidArgumentException;

use Imagine\Exception\OutOfBoundsException;

use Imagine\Image\Point;

use Imagine\Image\Box;

use Symfony\Component\HttpFoundation\Response;

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
     * This actions is responsible for validatin, uploading and clearing unused images
     */
    public function uploadAction ()
    {

        if ($this->getRequest()->isMethod('POST') && $this->getRequest()->files->get('file', false)) {

            $translator = $this->container->get('translator');
            $filesystem = $this->get('filesystem');
            $options = $this->container->getParameter('neutron_form.plupload.configs');
            $handle = $this->getRequest()->files->get('file');
            $name = uniqid() . '.' . $handle->guessExtension();
            $dirs = $this->getImageDirs($name, $options);

            $validate = $this->validateImage($handle, $this->getConfigs());

            if ($validate !== true) {
                return new Response(json_encode(array(
                    'success' => false,
                    'err_msg' => $validate
                )));
            }

            $this->clearFiles($options);
            $handle->move($dirs['originalDir'], $name);
            $this->normalizeImage($dirs['originalImagePath'], $options);
            $filesystem->copy($dirs['originalImagePath'], $dirs['imagePath'], true);
            $hash = md5_file($dirs['imagePath']);

            return new Response(json_encode(
                array(
                    'success' => true,
                    'name' => $name,
                    'hash' => $hash
                )
            ));
        }

    }

    /**
     * This action performs image cropping
     */
    public function cropAction ()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {

            $options = $this->container->getParameter('neutron_form.plupload.configs');
            $name = $this->getRequest()->get('name', false);
            $dirs = $this->getImageDirs($name, $options);

            $x = $this->getRequest()->get('x', false);
            $y = $this->getRequest()->get('y', false);
            $w = $this->getRequest()->get('w', false);
            $h = $this->getRequest()->get('h', false);


            try {
                $imagine = $this->get('imagine');
                $image = $imagine->open($dirs['imagePath']);
            } catch (InvalidArgumentException $e) {
                return new Response(json_encode(array(
                    'success' => false,
                    'err_msg' => $this->get('translator')->trans('exception.image.open')
                )));
            }

            try {
                $image->crop(new Point($x, $y), new Box($w, $h))->save($dirs['imagePath']);
            } catch (OutOfBoundsException $e) {
                return new Response(
                    json_encode(array(
                        'success' => false,
                        'err_msg' => $this->get('translator')->trans('exception.image.out_of_bounds')
                    ))
                );
            } catch (InvalidArgumentException $e) {
                return new Response(
                    json_encode(array(
                        'success' => false,
                        'err_msg' => $this->get('translator')->trans('exception.image.crop')
                    ))
                );
            }

            $hash = md5_file($dirs['imagePath']);

            return new Response(
                json_encode(array(
                    'success' => true,
                    'name' => $name,
                    'hash' => $hash
                ))
            );

        }
    }

    /**
     * This action performs image rotation
     */
    public function rotateAction ()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $options = $this->container->getParameter('neutron_form.plupload.configs');
            $name = $this->getRequest()->get('name', false);
            $dirs = $this->getImageDirs($name, $options);

            $imagine = $this->get('imagine');
            $image = $imagine->open($dirs['imagePath']);
            $image->rotate(90)->save($dirs['imagePath']);
            $hash = md5_file($dirs['imagePath']);

            return new Response(
                json_encode(array(
                    'success' => true,
                    'name' => $name,
                    'hash' => $hash
                ))
            );

        }
    }

    /**
     * This action revert the image to original one
     */
    public function resetAction ()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $options = $this->container->getParameter('neutron_form.plupload.configs');
            $name = $this->getRequest()->get('name', false);
            $dirs = $this->getImageDirs($name, $options);

            $this->get('filesystem')->copy($dirs['originalImagePath'], $dirs['imagePath'], true);

            $hash = md5_file($dirs['imagePath']);

            return new Response(
                json_encode(array(
                    'success' => true,
                    'name' => $name,
                    'hash' => $hash
                ))
            );
        }
    }

    /**
     * Returns an array of image paths if folders do not exist it creates them.
     *
     * @param string $name
     * @param array $options
     * @throws \InvalidArgumentException
     * @return array
     */
    private function getImageDirs($name, $options)
    {
        $dir = $this->get('kernel')->getRootDir() . '/../web' . DIRECTORY_SEPARATOR . $options['temporary_dir'];
        $originalDir = $dir . DIRECTORY_SEPARATOR . 'original';

        $filesystem = $this->get('filesystem');

        if (!is_dir($dir)){
            $filesystem->mkdir($dir, 0777);
        }

        if (!is_dir($originalDir)){
            $filesystem->mkdir($originalDir, 0777);
        }

        $originalImagePath = $originalDir . DIRECTORY_SEPARATOR . $name;
        $imagePath = $dir . DIRECTORY_SEPARATOR .  $name;

        return array(
            'originalDir'       => $originalDir,
            'dir'               => $dir,
            'originalImagePath' => $originalImagePath,
            'imagePath'         => $imagePath
        );
    }

    /**
     * Validates image and return true on success and
     * array of error messages on failure
     *
     * @param unknown_type $handle
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
        $errors = $this->get('validator')->validateValue($handle, $imageConstraint);

        if (count($errors) == 0) {
            return true;
        } else {
            return $this->get('translator')
                ->trans(/** @Ignore */$errors[0]->getMessageTemplate(), $errors[0]->getMessageParameters());
        }
    }

    /**
     * Normalize image to given width or height
     *
     * @param string $imagePath
     * @param array $options
     */
    private function normalizeImage ($imagePath, array $options)
    {
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
     * Clears junk images
     *
     * @param array $options
     */
    private function clearFiles (array $options)
    {
        $application = new Application($this->get('kernel'));
        $application->setAutoExit(false);

        $olderThan = (int) $options['older_than'];
        $command = 'neutron:form:file-clear';

        $input = new ArrayInput(array(
            'command' => $command,
            'olderThan' => $olderThan
        ));

        return $application->run($input);
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
