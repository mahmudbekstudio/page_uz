<?php
use Illuminate\Support\Arr;

if (! function_exists('getFileContentFromTemmplate')) {
    function getFileContentFromTemmplate(\App\Models\Template $template)
    {
        $addedStyleFiles = [];
        $addedScriptFiles = [];
        $html = "<html lang=\"en\">\n";
        $html .= "<head>\n";
        $html .= "<meta charset=\"utf-8\">\n";
        $html .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">\n";

        foreach (config('app.website.css') as $css) {
            $cssTag = '<link rel="stylesheet" href="' . $css['href'] . '"';

            if (isset($css['integrity'])) {
                $cssTag .= ' integrity="' . $css['integrity'] . '"';
            }

            if (isset($css['crossorigin'])) {
                $cssTag .= ' crossorigin="' . $css['crossorigin'] . '"';
            }

            $cssTag .= ">\n";

            $html .= $cssTag;
        }

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

        $html .= "<style>" . str_replace("\n", "", Arr::get($template->params, 'styles', '')) . "</style>\n";

        $html .= "</head>\n";
        $html .= "<body>\n";

        $html .= Arr::get($template->params, 'contentHtml');

        foreach (config('app.website.js') as $js) {
            $jsTag = '<script src="' . $js['src'] . '"';

            if (isset($js['integrity'])) {
                $jsTag .= ' integrity="' . $js['integrity'] . '"';
            }

            if (isset($js['crossorigin'])) {
                $jsTag .= ' crossorigin="' . $js['crossorigin'] . '"';
            }

            $jsTag .= "></script>\n";

            $html .= $jsTag;
        }

        foreach ($addedScriptFiles as $scriptFile) {
            $html .= "<script src=\"" . $scriptFile . "\"></script>\n";
        }

        $html .= "</body>\n";
        $html .= "</html>";

        return $html;
    }
}
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
