<?php
namespace App\Models;

/**
 * Setting Model (ayarlar table)
 */
class Setting extends BaseModel {
    protected $table = 'ayarlar';

    /**
     * Get the single settings row
     */
    public function get() {
        return $this->find(1);
    }
}
