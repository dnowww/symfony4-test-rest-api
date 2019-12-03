<?php

namespace App\Listener;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class AnnotationListener {

    protected $reader;

    public function __construct(Reader $reader) {
        $this->reader = $reader;
    }

    public function onKernelController(FilterControllerEvent $event) {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        list($controller, $method, ) = $controller;

        $this->ignoreSoftDeleteAnnotation($controller, $method);
    }

    private function readAnnotation($controller, $method, $annotation) {
        $classReflection = new \ReflectionClass(ClassUtils::getClass($controller));
        $classAnnotation = $this->reader->getClassAnnotation($classReflection, $annotation);

        $objectReflection = new \ReflectionObject($controller);
        $methodReflection = $objectReflection->getMethod($method);
        $methodAnnotation = $this->reader->getMethodAnnotation($methodReflection, $annotation);

        if (!$classAnnotation && !$methodAnnotation) {
            return false;
        }

        return [$classAnnotation, $classReflection, $methodAnnotation, $methodReflection];
    }

    private function ignoreSoftDeleteAnnotation($controller, $method) {
        static $class = 'App\Annotation\IgnoreSoftDelete';

        if ($this->readAnnotation($controller, $method, $class)) {
            //$em = $controller->get('doctrine.orm.entity_manager');
            $em = $controller->getEntityManagerPublic();
            //$em = $controller->getDoctrine()->getManager();
            $em->getFilters()->disable('softdeleteable');
        }
    }

}