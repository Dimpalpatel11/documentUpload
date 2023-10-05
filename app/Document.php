<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'document_id';

    protected $fillable = ['name', 'extension'];

    // public function getNameAttribute($value){
    //     if($value != ""){
    //         return (url('/resources/uploads/documents/') . '/') . $value;
    //     }else{
    //         return "";
    //     }

    // }
}
