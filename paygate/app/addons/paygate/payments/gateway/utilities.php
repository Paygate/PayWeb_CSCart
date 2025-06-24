<?php

namespace Payweb\Utility;

class Utilities
{
    /**
     * Sanitize an array of fields.
     *
     * @param array $fields The fields to sanitize.
     *
     * @return array The sanitized fields.
     */
    public function sanitizeFields(array $fields): array
    {
        $result = [];
        foreach ($fields as $key => $field) {
            $result[$key] = htmlspecialchars($field, ENT_QUOTES, 'UTF-8');
        }

        return $result;
    }
}
