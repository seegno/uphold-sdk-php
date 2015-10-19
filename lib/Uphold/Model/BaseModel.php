<?php

namespace Uphold\Model;

/**
 * Base Model.
 */
abstract class BaseModel
{
    /**
     * Uphold client.
     *
     * @var UpholdClient
     */
    protected $client;

    /**
     * Gets client.
     *
     * @return $client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Returns all fields from the model.
     *
     * @return array
     */
    public function toArray()
    {
        $fields = array();

        foreach ($this as $key => $value) {
            if ('client' === $key) {
                continue;
            }

            $fields[$key] = $value;
        }

        return $fields;
    }

    /**
     * Update Model fields based on data received.
     *
     * @param array $data Object fields.
     *
     * @return BaseModel
     */
    protected function updateFields($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }
}
