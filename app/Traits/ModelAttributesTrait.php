<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait ModelAttributesTrait
{
    protected function formattedStartDate(): Attribute
    {
        return Attribute::make(
            get: fn () => format_date($this->start_date)
        );
    }

    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => format_date($this->date)
        );
    }

    protected function formattedEndDate(): Attribute
    {
        return Attribute::make(
            get: fn () => format_date($this->end_date)
        );
    }

    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => format_datetime($this->created_at)
        );
    }

    protected function formattedUpdatedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => format_datetime($this->updated_at)
        );
    }

    protected function formattedDeletedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->deleted_at ? format_datetime($this->deleted_at) : null
        );
    }

    protected function humanCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at ? Carbon::parse($this->created_at)->diffForHumans() : null
        );
    }
}
