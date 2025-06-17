<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'book';
    
    protected $fillable = [
        'judul',
        'author',
        'tahun',
        'genre',
        'cover_path',
        'pdf_path',
        'total_pages',
        'point_value'
    ];

    // Mutator untuk memastikan path file selalu benar
    public function setCoverPathAttribute($value)
    {
        $this->attributes['cover_path'] = 'assets/buku/' . $value;
    }

    public function setPdfPathAttribute($value)
    {
        $this->attributes['pdf_path'] = 'assets/buku/' . $value;
    }
}
