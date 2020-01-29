<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class UrlPro extends Model
{
    //protected $table = "siswa";
 
    protected $fillable = ['id','name','code','url'];
}