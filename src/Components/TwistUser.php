<?php

namespace Drupal\hybrid_user\Components;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\hybrid_user\Gateway\rest\ResponseHandler;
use Masterminds\HTML5\Exception;

class HybridUser {

    /**
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    private $entity;

    /**
     * @var ResponseHandler
     */
    private $responseHandler;

    /**
     * TwistUser constructor.
     */
    public function __construct() {
        $this->entity = \Drupal::entityTypeManager();
        $this->responseHandler = new ResponseHandler();
        $this->fields = unserialize(FIELDS);
    }

    /**
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(array $data) {
        try {
            $this->prepareFields($data);
        } catch (Exception $e) {
            return $this->responseHandler->onError($e->getMessage());
        }

        return $this->save();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function save() {
        $storage = $this->entity->getStorage(ENTITY);
        $entry = $storage->create($this->fields);
        try {
            $entry->save();
        } catch (EntityStorageException $e) {
            $this->responseHandler->onError($e->getMessage());
        }

        return $this->responseHandler->onSuccess();
    }

    /**
     * @param array $data
     * @throws Exception
     */
    private function prepareFields(array $data) {
        $field_required = array();
        $field_set = array();

        foreach ($this->fields as $field) {
            $field_set[$field[0]] = null;
            if ($field[1] === R) {
                $field_required[] = $field[0];
            }
        }

        foreach ($field_required as $fr) {
            if (!array_key_exists($fr, $data) || empty($data[$fr])) {
                throw new Exception('required ' . $fr);
            } else {
                $this->fields = array_merge($field_set, $data);
            }
        }

        $this->fields["type"] = BUNDLE;
    }
}