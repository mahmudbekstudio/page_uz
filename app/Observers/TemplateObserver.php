<?php

namespace App\Observers;

use App\Models\Template;

class TemplateObserver
{
    /**
     * Handle the Template "created" event.
     *
     * @param  \App\Models\Template  $template
     * @return void
     */
    public function created(Template $template)
    {
        $content = $this->getTemplateContent($template);
        createStorageTemplateFile($template->type, $template->id, $template->website_id, $content);
    }

    /**
     * Handle the Template "updated" event.
     *
     * @param  \App\Models\Template  $template
     * @return void
     */
    public function updated(Template $template)
    {
        $content = $this->getTemplateContent($template);
        updateStorageTemplateFile($template->type, $template->id, $template->website_id, $content);
    }

    /**
     * Handle the Template "deleted" event.
     *
     * @param  \App\Models\Template  $template
     * @return void
     */
    public function deleted(Template $template)
    {
        deleteStorageTemplateFile($template->type, $template->id, $template->website_id);
    }

    /**
     * Handle the Template "restored" event.
     *
     * @param  \App\Models\Template  $template
     * @return void
     */
    public function restored(Template $template)
    {
        $this->created($template);
    }

    /**
     * Handle the Template "force deleted" event.
     *
     * @param  \App\Models\Template  $template
     * @return void
     */
    public function forceDeleted(Template $template)
    {
        $this->deleted($template);
    }

    /**
     * @param Template $template
     * @return bool
     */
    private function isPageType(Template $template): bool
    {
        return in_array($template->type, Template::pageTypes());
    }

    private function isLayoutType(Template $template): bool
    {
        return $template->type === Template::TYPE_LAYOUT;
    }

    private function getTemplateContent(Template $template): string
    {
        if ($this->isPageType($template)) {
            return getFileContentFromTemplatePage($template);
        } elseif($this->isLayoutType($template)) {
            return getFileContentFromTemplateLayout($template);
        }

        return '';
    }
}
