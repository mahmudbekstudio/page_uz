<?php

use App\Repositories\WebsiteRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use App\Services\Admin\TemplateService;

if (! function_exists('getFileContentFromTemplateLayout')) {
    function getFileContentFromTemplateLayout(\App\Models\Template $template)
    {
        $html = "<?php\n";
        $html .= "use App\Helpers\GlobalVariable;\n";
        $html .= '$variables = app(GlobalVariable::class);' . "\n";
        $html .= "?>\n";
        $html .= "<html lang=\"en\">\n";
        $html .= "<head>\n";
        $html .= "<meta charset=\"utf-8\">\n";
        $html .= '<?php echo $variables->get("website-metas.indexing", "") ? "" : "<meta name=\"robots\" content=\"noindex, nofollow\" />";?>';
        $html .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">\n";
        $html .= '<meta name="description" content="<?php echo $variables->get("fields.seoDescription", translateText($variables->get("website-metas.seoDescription", ""))); ?>">';
        $html .= '<meta name="keywords" content="<?php echo $variables->get("fields.seoKeyword", translateText($variables->get("website-metas.seoKeyword", ""))); ?>">';
        $html .= '<link rel="icon" type="image/x-icon" href="<?php echo \Illuminate\Support\Arr::get(getFilesList($variables->get("website-metas.favicon")), "0.url", "") ?>">';

        $themeConfig = app(TemplateService::class)->getThemeConfig();
        $html .= implode("\n", array_map(function (string $css) {
            return '<link rel="stylesheet" href="' . $css . '">';
        }, $themeConfig['css']));

        $addedStyleFiles = [];
        $addedScriptFiles = [];

        foreach ($template->content as $item) {
            $styleFiles = Arr::get($item, 'styleFiles', []);

            foreach ($styleFiles as $styleFile) {
                if (!in_array($styleFile, $addedStyleFiles)) {
                    $addedStyleFiles[] = $styleFile;
                    $html .= "<link rel=\"stylesheet\" href=\"" . $styleFile . "\">\n";
                }
            }

            $scriptFiles = Arr::get($item, 'scriptFiles', []);
            foreach ($scriptFiles as $scriptFile) {
                if (!in_array($scriptFile, $addedScriptFiles)) {
                    $addedScriptFiles[] = $scriptFile;
                }
            }
        }

        $styles = strip_tags(Arr::get($template->params, 'styles', ''));
        $html .= "<style>" . str_replace("\n", "", $styles) . "</style>\n";
        $customStyles = strip_tags(Arr::get($template->params, 'customStyles', ''));
        $customStyles = str_replace("\n", "", $customStyles);

        if ($customStyles) {
            $html .= "<style>" . $customStyles . "</style>\n";
        }

        $html .= '<title><?php echo translateText($variables->get("website-metas.name")); ?> - <?php echo $variables->get("fields.title"); ?></title>';
        $html .= "</head>\n";
        $html .= "<body>\n";

        $contentHtml = str_replace('<?', '', Arr::get($template->params, 'contentHtml'));

        preg_match_all('/<!--translateStart-->(.*)<!--translateEnd-->/U', $contentHtml, $matches);
        $replaceTranslations = [];
        $replaceTranslationsKey = 0;
        foreach ($matches[0] as $key => $item) {
            if (str_starts_with($matches[1][$key], '{') && str_ends_with($matches[1][$key], '}')) {
                $matches[1][$key] = json_decode($matches[1][$key], true);

                $keysArr = [];
                foreach ($matches[1][$key] as $subKey => $val) {
                    $keysArr[] = '"' . $subKey . '"=>"' . addslashes($val) . '"';
                }

                $matches[1][$key] = '[' . implode(',', $keysArr) . ']';
            } else {
                $matches[1][$key] = '"' . $matches[1][$key] . '"';
            }

            $replaceTranslations[$replaceTranslationsKey] = '<?php echo translateText(' . $matches[1][$key] . ') ?>';
            $contentHtml = str_replace($item, '---echoTranslationText_' . $replaceTranslationsKey . '---', $contentHtml);
            $replaceTranslationsKey++;
        }

        if ($contentHtml) {
            $dom = new DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($contentHtml), LIBXML_NOERROR | LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

            $tags_to_remove = ['script', 'style', 'iframe', 'link', 'html', 'head', 'body'];

            foreach ($tags_to_remove as $tag) {
                $element = $dom->getElementsByTagName($tag);
                foreach ($element as $item) {
                    $item->parentNode->removeChild($item);
                }
            }

            foreach ($dom->getElementsByTagname('*') as $element) {
                foreach (iterator_to_array($element->attributes) as $name => $attribute) {
                    if (substr_compare($name, 'on', 0, 2, TRUE) === 0) {
                        $element->removeAttribute($name);
                    }
                }
            }

            $replaceFrom = '{ $content }';
            $replaceTo = '<?php include($variables->get("pageTemplatePath")); ?>';
            $contentHtml = str_replace($replaceFrom, $replaceTo, $dom->saveHTML());

            foreach ($replaceTranslations as $key => $value) {
                $contentHtml = str_replace('---echoTranslationText_' . $key . '---', $value, $contentHtml);
            }

            $html .= $contentHtml;
        }

        $html .= implode("\n", array_map(function (string $js) {
            return '<script src="' . $js . '"></script>';
        }, $themeConfig['js']));

        foreach ($addedScriptFiles as $scriptFile) {
            $html .= "<script src=\"" . $scriptFile . "\"></script>\n";
        }

        $html .= "</body>\n";
        $html .= "</html>";

        return $html;
    }
}

if (! function_exists('getFileContentFromTemplatePage')) {
    function getFileContentFromTemplatePage(\App\Models\Template $template)
    {
        $html = "<?php\n";
        $html .= "use App\Helpers\GlobalVariable;\n";
        $html .= '$variables = app(GlobalVariable::class);' . "\n";
        $html .= "?>\n";

        //$addedStyleFiles = [];
        $addedScriptFiles = [];

        /*foreach ($template->content as $item) {
            $styleFiles = Arr::get($item, 'styleFiles', []);

            foreach ($styleFiles as $styleFile) {
                if (!in_array($styleFile, $addedStyleFiles)) {
                    $addedStyleFiles[] = $styleFile;
                    $html .= "<link rel=\"stylesheet\" href=\"" . $styleFile . "\">\n";
                }
            }

            $scriptFiles = Arr::get($item, 'scriptFiles', []);
            foreach ($scriptFiles as $scriptFile) {
                if (!in_array($scriptFile, $addedScriptFiles)) {
                    $addedScriptFiles[] = $scriptFile;
                }
            }
        }*/

        $styles = strip_tags(Arr::get($template->params, 'styles', ''));
        $html .= "<style>" . str_replace("\n", "", $styles) . "</style>\n";
        $customStyles = strip_tags(Arr::get($template->params, 'customStyles', ''));
        $html .= "<style>" . str_replace("\n", "", $customStyles) . "</style>\n";

        $contentHtml = str_replace('<?', '', Arr::get($template->params, 'contentHtml', ''));

        if (!$contentHtml) {
            return $html;
        }

        preg_match_all('/<!--translateStart-->(.*)<!--translateEnd-->/U', $contentHtml, $matches);
        $replaceTranslations = [];
        $replaceTranslationsKey = 0;

        foreach ($matches[0] as $key => $item) {
            if (str_starts_with($matches[1][$key], '{') && str_ends_with($matches[1][$key], '}')) {
                $matches[1][$key] = json_decode($matches[1][$key], true);

                $keysArr = [];
                foreach ($matches[1][$key] as $subKey => $val) {
                    $keysArr[] = '"' . $subKey . '"=>"' . addslashes($val) . '"';
                }

                $matches[1][$key] = '[' . implode(',', $keysArr) . ']';
            } else {
                $matches[1][$key] = '"' . $matches[1][$key] . '"';
            }

            $replaceTranslations[$replaceTranslationsKey] = '<?php echo translateText(' . $matches[1][$key] . ') ?>';
            $contentHtml = str_replace($item, '---echoTranslationText_' . $replaceTranslationsKey . '---', $contentHtml);
            $replaceTranslationsKey++;
            /*$contentHtml = str_replace($item, '<?php echo translateText(' . $matches[1][$key] . ') ?>', $contentHtml);*/
        }

        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->loadHTML(utf8_decode($contentHtml), LIBXML_NOERROR|LIBXML_HTML_NODEFDTD|LIBXML_HTML_NOIMPLIED);

        $tags_to_remove = ['script', 'style', 'iframe', 'link', 'html', 'head', 'body'];

        foreach($tags_to_remove as $tag){
            $element = $dom->getElementsByTagName($tag);
            foreach($element  as $item){
                $item->parentNode->removeChild($item);
            }
        }

        foreach ($dom->getElementsByTagname('*') as $element)
        {
            foreach (iterator_to_array($element->attributes) as $name => $attribute)
            {
                if (substr_compare($name, 'on', 0, 2, TRUE) === 0)
                {
                    $element->removeAttribute($name);
                }
            }
        }

        $contentHtml = $dom->saveHTML();

        foreach ($template->typeInstance->fields as $item) {
            $name = Arr::get($item, 'name');
            $type = Arr::get($item, 'type');
            $replaceFrom = '<div><span>{ $' . $name . ' }</span>';
            $typeClassName = 'field-' . $type . '-wrapper';
            $nameClassName = 'field-' . $name . '-wrapper';
            $replaceTo = '<div class="field-' . $type . '-' . $name . '-wrapper' . ' ' . $typeClassName . ' ' . $nameClassName . '"><?php echo $variables->get("fields.' . $name . '"); ?>';
            $contentHtml = str_replace($replaceFrom, $replaceTo, $contentHtml);
        }

        foreach ($replaceTranslations as $key => $value) {
            $contentHtml = str_replace('---echoTranslationText_' . $key . '---', $value, $contentHtml);
        }

        $html .= $contentHtml;

        /*foreach ($addedScriptFiles as $scriptFile) {
            $html .= "<script src=\"" . $scriptFile . "\"></script>\n";
        }*/

        return $html;
    }
}

//if (! function_exists('getFileContentFromTemmplate')) {
//    function getFileContentFromTemmplate(\App\Models\Template $template)
//    {
//        $addedStyleFiles = [];
//        $addedScriptFiles = [];
//        $html = "<?php\n";
//        $html .= "use App\Helpers\GlobalVariable;\n";
//        $html .= '$variables = app(GlobalVariable::class);' . "\n";
/*        $html .= "?>\n";*/
//        $html .= "<html lang=\"en\">\n";
//        $html .= "<head>\n";
//        $html .= "<meta charset=\"utf-8\">\n";
//        $html .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">\n";
//
//        foreach (config('app.website.css') as $css) {
//            $cssTag = '<link rel="stylesheet" href="' . $css['href'] . '"';
//
//            if (isset($css['integrity'])) {
//                $cssTag .= ' integrity="' . $css['integrity'] . '"';
//            }
//
//            if (isset($css['crossorigin'])) {
//                $cssTag .= ' crossorigin="' . $css['crossorigin'] . '"';
//            }
//
//            $cssTag .= ">\n";
//
//            $html .= $cssTag;
//        }
//
//        foreach ($template->content as $item) {
//            $styleFiles = Arr::get($item, 'styleFiles', []);
//
//            foreach ($styleFiles as $styleFile) {
//                if (!in_array($styleFile, $addedStyleFiles)) {
//                    $addedStyleFiles[] = $styleFile;
//                    $html .= "<link rel=\"stylesheet\" href=\"" . $styleFile . "\">\n";
//                }
//            }
//
//            $scriptFiles = Arr::get($item, 'scriptFiles', []);
//            foreach ($scriptFiles as $scriptFile) {
//                if (!in_array($scriptFile, $addedScriptFiles)) {
//                    $addedScriptFiles[] = $scriptFile;
//                }
//            }
//        }
//
//        $styles = strip_tags(Arr::get($template->params, 'styles', ''));
//        $html .= "<style>" . str_replace("\n", "", $styles) . "</style>\n";
//        $customStyles = strip_tags(Arr::get($template->params, 'customStyles', ''));
//        $html .= "<style>" . str_replace("\n", "", $customStyles) . "</style>\n";
//
//        $html .= "</head>\n";
//        $html .= "<body>\n";
//
//        $contentHtml = str_replace('<?', '', Arr::get($template->params, 'contentHtml'));
//        $dom = new DOMDocument();
//        $dom->loadHTML($contentHtml, LIBXML_NOERROR|LIBXML_HTML_NODEFDTD|LIBXML_HTML_NOIMPLIED);
//
//        $tags_to_remove = ['script', 'style', 'iframe', 'link', 'html', 'head', 'body'];
//
//        foreach($tags_to_remove as $tag){
//            $element = $dom->getElementsByTagName($tag);
//            foreach($element  as $item){
//                $item->parentNode->removeChild($item);
//            }
//        }
//
//        foreach ($dom->getElementsByTagname('*') as $element)
//        {
//            foreach (iterator_to_array($element->attributes) as $name => $attribute)
//            {
//                if (substr_compare($name, 'on', 0, 2, TRUE) === 0)
//                {
//                    $element->removeAttribute($name);
//                }
//            }
//        }
//
//        //$contentHtml = $dom->saveHTML();
//        /*foreach ($template->typeInstance->fields as $item) {
//            $name = Arr::get($item, 'name');
//            $replaceFrom = '{ $' . $name . ' }';
/*            $replaceTo = '<?php echo $variables->get("fields.' . $name . '"); ?>';*/
//            $contentHtml = str_replace($replaceFrom, $replaceTo, $contentHtml);
//        }
//
//        $html .= $contentHtml;*/
//
//        foreach (config('app.website.js') as $js) {
//            $jsTag = '<script src="' . $js['src'] . '"';
//
//            if (isset($js['integrity'])) {
//                $jsTag .= ' integrity="' . $js['integrity'] . '"';
//            }
//
//            if (isset($js['crossorigin'])) {
//                $jsTag .= ' crossorigin="' . $js['crossorigin'] . '"';
//            }
//
//            $jsTag .= "></script>\n";
//
//            $html .= $jsTag;
//        }
//
//        foreach ($addedScriptFiles as $scriptFile) {
//            $html .= "<script src=\"" . $scriptFile . "\"></script>\n";
//        }
//
//        $html .= "</body>\n";
//        $html .= "</html>";
//
//        return $html;
//    }
//}
if (! function_exists('getStructureHtml')) {
    function getStructureHtml(array $block, array $structure = null): string
    {
        /*$structure = $structure ?: Arr::get($block, 'structure', []);

        if (Arr::has($structure, 'tag')) {
            $html = '<' . Arr::get($structure, 'tag');

            if (!Arr::has($structure, 'attributes')) {
                Arr::set($structure, 'attributes', []);
            }

            if (!Arr::has($structure, 'attributes.class')) {
                Arr::set($structure, 'attributes.class', '');
            }

            foreach (Arr::get($structure, 'attributes') as $key => $value) {
                $html .= ' ' . $key . '="' . $value . '"';
            }

            if (Arr::has($structure, 'children') && !empty(Arr::get($structure, 'children'))) {
                $html .= '>';

                foreach (Arr::get($structure, 'children') as $childStructure) {
                    $html .= getStructureHtml($block, $childStructure);
                }

                $html .= '</' . Arr::get($structure, 'tag') . '>';
            } else {
                $html .= '/>';
            }

            return $html;
        } elseif (
            Arr::has($structure, 'field') &&
            Arr::has($block, 'values') &&
            Arr::has(Arr::get($block, 'values'), Arr::get($structure, 'field'))
        ) {
            $blockHtml = '';
            if (Arr::has($block, 'children')) {
                if (Arr::get($structure, 'field') === 'content') {
                    $blockHtml = generateContent(Arr::get($block, 'children'));
                } else {
                    foreach (Arr::get($block, 'children') as $blockChild) {
                        if (Arr::get($blockChild, 'name', '') === Arr::get($structure, 'field') && Arr::has($blockChild, 'children')) {
                            foreach (Arr::get($blockChild, 'children') as $blockChildItem) {
                                $blockHtml .= getStructureHtml($blockChild);
                            }
                        }
                    }
                }

                //
            }
        } elseif (Arr::has($structure, 'text')) {
            return Arr::get($structure, 'text');
        } elseif (Arr::has($structure, 'html')) {
            return Arr::get($structure, 'html');
        }*/

        return '';
    }
}

/*if (! function_exists('generateContent')) {
    function generateContent(array $children): string
    {
        dd($children);
        $contentHtml = '';

        foreach ($children as $itemRow) {
            $contentHtml .= '<div class="row">';

            foreach (Arr::get($itemRow, 'children', []) as $itemCol) {
                $contentHtml .= '<div class="col-md-' . Arr::get($itemCol, 'size', 0) . '">';

                foreach (Arr::get($itemCol, 'children', []) as $itemColContent) {
                    //TODO:
                }

                $contentHtml .= '</div>';
            }

            $contentHtml .= '</div>';
        }

        return $contentHtml;
    }
}*/
if (! function_exists('getFileUrl')) {
    function getFileUrl(array $file): string
    {
        return Storage::url($file['folderPath'] . '/' . $file['name'] . '.' . $file['extension']);
    }
}

if (! function_exists('getFilesList')) {
    function getFilesList(array|null $files): array
    {
        if (is_null($files)) return [];

        foreach ($files as $key => $file) {
            $file['url'] = getFileUrl($file);
            $files[$key] = $file;
        }

        return $files;
    }
}

if (! function_exists('structureToHtml')) {
    function structureToHtml(array $structure, array $fields): string
    {
        $result = '<' . $structure['tag'];

        $children = Arr::get($structure, 'children', []);
        $attribtues = Arr::get($structure, 'attributes', []);

        foreach ($attribtues as $attribtueName => $attribtueValue) {
            $result .= ' ' . $attribtueName . '="' . $attribtueValue . '"';
        }

        if (!empty($children)) {
            $result .= '>';

            foreach ($children as $itemKey => $item) {
                if ($itemKey === 'field') {
                    $result .= '{ $content }';
                } else {
                    $result .= structureToHtml($item, $fields);
                }
            }

            $result .= '</' . $structure['tag'] . '>';
        } else {
            $result .= '/>';
        }

        return $result;
    }
}

if (! function_exists('getStructureFields')) {
    function getStructureFields(array $structure): array
    {
        $result = [];

        foreach ($structure as $tab) {
            foreach (Arr::get($tab, 'children', []) as $row) {
                foreach (Arr::get($row, 'children', []) as $col) {
                    foreach (Arr::get($col, 'children', []) as $field) {
                        $result[] = $field;
                    }
                }
            }
        }

        return $result;
    }
}
