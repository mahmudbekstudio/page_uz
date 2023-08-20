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
        if ($this->canSaveFile($template)) {
            $content = getFileContentFromTemmplate($template);
            createStorageTemplateFile($template->type, $template->id, $template->website_id, $content);
        }
    }

    /**
     * Handle the Template "updated" event.
     *
     * @param  \App\Models\Template  $template
     * @return void
     */
    public function updated(Template $template)
    {
        if ($this->canSaveFile($template)) {
            $content = getFileContentFromTemmplate($template);
            updateStorageTemplateFile($template->type, $template->id, $template->website_id, $content);
        }
    }

    /**
     * Handle the Template "deleted" event.
     *
     * @param  \App\Models\Template  $template
     * @return void
     */
    public function deleted(Template $template)
    {
        if ($this->canSaveFile($template)) {
            deleteStorageTemplateFile($template->type, $template->id, $template->website_id);
        }
    }

    /**
     * Handle the Template "restored" event.
     *
     * @param  \App\Models\Template  $template
     * @return void
     */
    public function restored(Template $template)
    {
        if ($this->canSaveFile($template)) {
            $content = getFileContentFromTemmplate($template);
            createStorageTemplateFile($template->type, $template->id, $template->website_id, $content);
        }
    }

    /**
     * Handle the Template "force deleted" event.
     *
     * @param  \App\Models\Template  $template
     * @return void
     */
    public function forceDeleted(Template $template)
    {
        if ($this->canSaveFile($template)) {
            deleteStorageTemplateFile($template->type, $template->id, $template->website_id);
        }
    }

    /**
     * @param Template $template
     * @return bool
     */
    private function canSaveFile(Template $template): bool
    {
        return in_array($template->type, Template::saveFileTypes());
    }
}
