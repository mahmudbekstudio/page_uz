<?php
use Illuminate\Support\Arr;
use App\Helpers\DataFormat;
use Illuminate\Support\Carbon;

if (! function_exists('getFormattedField')) {
    function getFormattedField($meta, array $typeField): string
    {
        $value = DataFormat::toFormat($meta->meta_value, $meta->meta_format);

        switch (Arr::get($typeField, 'type')) {
            case 'text':
                return getTextField($value);
            case 'textarea':
                return getTextField($value);
            case 'number':
                return getTextField($value);
            case 'password':
                $pass = '';
                for ($i = 0; $i < strlen($value); $i++) {
                    $pass .= '*';
                }
                return getTextField($pass);
            case 'select':
                return getOptionsField($value, $typeField);
            case 'file':
                return getFilesField($value, $typeField);
            case 'switch':
                return translateText('words.' . ($value ? 'yes' : 'no'));
            /*case 'divider':
                return '';*/
            case 'datetime':
                return getDateField($value, true, true);
            case 'date':
                return getDateField($value, true);
            case 'dateRange':
                return getDateField(json_decode($value), true, false, ' - ');
            case 'dateMultiple':
                return getDateField(json_decode($value), true, false, ', ');
            case 'time':
                return getDateField($value, false, true);
            case 'radio':
                return getOptionsField($value, $typeField);
            case 'checkbox':
                return getOptionsField($value, $typeField);
            case 'editor':
                return getTextField($value);
            /*case 'advancedParent':
                return '';*/
            /*case 'advancedChildOf':
                return '';*/
            case 'requiredPublishStart':
                return getDateField($value, true);
            case 'requiredPublishEnd':
                return getDateField($value, true);
            /*case 'requiredRouteName':
                return '';*/
            case 'requiredSeoKeyword':
                return getTextField($value);
            case 'requiredSeoDescription':
                return getTextField($value);
            /*case 'requiredStatus':
                return '';*/
            /*case 'requiredTemplate':
                return '';*/
            case 'requiredTitle':
                return getTextField($value);
        }
        return '';
    }
}

if (! function_exists('getTextField')) {
    function getTextField($value): string
    {
        return gettype($value) === DataFormat::FORMAT_ARRAY ? translateText($value) : $value;
    }
}

if (! function_exists('getFilesField')) {
    function getFilesField($files, $typeField): string
    {
        $files = !empty($files) ? $files : Arr::get($typeField, 'value', []);

        if (empty($files)) {
            return '';
        }

        $fileType = Arr::get($typeField, 'params.fileType');
        $className1 = 'field_' . $typeField['type'];
        $className2 = $className1 . '_' . $typeField['params']['fileType'];
        $className3 = $className2 . '_' . $typeField['name'];
        $fileClassName = implode(' ', [
            $className1,
            $className2,
            $className3
        ]);
        $list = [];
        foreach ($files as $file) {
            $fileUrl = getFileUrl($file);
            $fileName = $file['name'] . '.' . $file['extension'];
            $list[] = ($fileType === 'image') ?
                '<img class="img-fluid" alt="' . $fileName . '" src="' .  $fileUrl. '">' :
                '<a href="' . $fileUrl . '" target="_blank">' . $fileName . '</a>';
        }
        $div = '<div class="' . $fileClassName . '">';

        return $div . implode('</div>' . $div, $list) . '</div>';
    }
}

if (! function_exists('getDateField')) {
    function getDateField($value, $isDate = false, $isTime = false, $splitter = ''): string
    {
        $formats = [];

        if ($isDate) {
            $formats[] = config('app.format.date');
        }

        if ($isTime) {
            $formats[] = config('app.format.time');
        }

        $format = implode(' ', $formats);

        if (is_array($value)) {
            $list = [];

            foreach ($value as $item) {
                $list[] = (new Carbon($item))->format($format);
            }

            return implode($splitter, $list);
        }

        return $value ? (new Carbon($value))->format($format) : '';
    }
}

if (! function_exists('getOptionsField')) {
    function getOptionsField($value, $typeField): string
    {
        $options = Arr::get($typeField, 'params.options');

        if (gettype($value) === 'array') {
            $result = [];
            foreach ($options as $item) {
                if (in_array($item['value'], $value)) {
                    $result[] = getTextField($item['text']);
                }
            }

            return implode(', ', $result);
        } else {
            foreach ($options as $item) {
                if ($item['value'] == $value) {
                    return getTextField($item['text']);
                }
            }
        }

        return '';
    }
}
