<?php

namespace Peyas\PreOrderForm\Traits;
use Illuminate\Database\Eloquent\Model;

trait SoftDeletesWithDeletedBy
{
    protected static function bootSoftDeletesWithDeletedBy()
    {
        static::deleting(function (Model $model) {
            if (!$model->isSoftDelete()) {
                $model->deleted_by_id = auth()->id();
                $model->saveQuietly();
            }
        });
    }

    /**
     * Check if the delete is a soft delete.
     */
    protected function isSoftDelete(): bool
    {
        return !is_null($this->deleted_at);
    }

}
