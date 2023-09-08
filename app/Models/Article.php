<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'api',
        'source',
        'author',
        'title',
        'description',
        'image_url',
        'article_url',
        'publishedAt',
        'category',
        'lang'
    ];
}
