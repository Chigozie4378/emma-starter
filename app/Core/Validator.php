<?php

class Validator
{
    private $data;
    private $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function make(array $data)
    {
        return new self($data);
    }

    public function required($field, $label = null)
    {
        $label = $label ?: ucfirst($field);
        $value = $this->data[$field] ?? null;

        if (is_array($value)) {
            if (empty($value)) {
                $this->errors[$field][] = "{$label} is required.";
            }
        } elseif (trim((string)$value) === '') {
            $this->errors[$field][] = "{$label} is required.";
        }

        return $this;
    }

    public function numeric($field, $label = null)
    {
        $label = $label ?: ucfirst($field);
        $value = $this->data[$field] ?? null;

        if ($value !== null && $value !== '' && !is_numeric($value)) {
            $this->errors[$field][] = "{$label} must be numeric.";
        }

        return $this;
    }

    public function min($field, $min, $label = null)
    {
        $label = $label ?: ucfirst($field);
        $value = $this->data[$field] ?? null;

        if ($value !== null && is_numeric($value) && (float)$value < $min) {
            $this->errors[$field][] = "{$label} must be at least {$min}.";
        }

        return $this;
    }

    public function email($field, $label = null)
    {
        $label = $label ?: ucfirst($field);
        $value = $this->data[$field] ?? null;

        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "{$label} must be a valid email address.";
        }

        return $this;
    }

    public function fails()
    {
        return !empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function firstError()
    {
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0];
        }

        return null;
    }
}