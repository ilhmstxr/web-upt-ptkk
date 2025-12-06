<?php  
  
namespace App\Models;  
  
use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;  
  
class ProfilUPT extends Model  
{  
    use HasFactory;  
  
    protected $table = 'profil_u_p_t_s';  
  
    protected $fillable = [  
        'kepala_upt_name',  
        'kepala_upt_photo',  
        'sambutan',  
        'sejarah',  
        'visi',  
        'misi',  
        'alamat',  
        'email',  
        'phone',  
    ];  
  
    // aktifkan timestamps (created_at & updated_at)  
    public $timestamps = true;  
}  
