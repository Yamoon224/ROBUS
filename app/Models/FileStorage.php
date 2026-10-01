<?php

namespace App\Models;

use App\Traits\HasWallet;
use App\Traits\IconTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileStorage extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;

    use HasFactory, IconTrait, HasWallet;

    protected $table = 'file_storage';

    protected $appends = ['file_url', 'icon', 'size_format'];

    public function getFileUrlAttribute()
    {
        return asset_url($this->path . '/' . $this->filename);
    }

    public function getSizeFormatAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        if ($bytes > 1) {
            return $bytes . ' bytes';
        }

        if ($bytes == 1) {
            return $bytes . ' byte';
        }

        return '0 bytes';

    }

}