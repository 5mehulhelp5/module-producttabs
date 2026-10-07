<?php
declare(strict_types=1);

namespace Panth\ProductTabs\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\Serialized\ArraySerialized;

class DynamicRows extends ArraySerialized
{
    private const ROW_PREFIX_PATTERN = '/^(?:(?:cms|attr)_)+(?=_)/';

    public function beforeSave()
    {
        $value = $this->getValue();
        if (is_array($value)) {
            $this->setValue($this->normalizeRowKeys($value));
        }

        return parent::beforeSave();
    }

    public function normalizeRowKeys(array $rows): array
    {
        $result = [];
        foreach ($rows as $key => $row) {
            $key = (string) $key;
            if ($key === '__empty') {
                $result[$key] = $row;
                continue;
            }
            $normalized = (string) preg_replace(self::ROW_PREFIX_PATTERN, '', $key);
            $candidate = $normalized;
            $suffix = 1;
            while (array_key_exists($candidate, $result)) {
                $candidate = $normalized . '_' . $suffix++;
            }
            $result[$candidate] = $row;
        }

        return $result;
    }
}
