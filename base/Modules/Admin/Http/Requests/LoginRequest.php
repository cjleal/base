<?php 

namespace Modules\Admin\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class LoginRequest extends FormRequest {
    public function rules() {
        return [
            'usuario' => ['required', 'min:4'],
            'password' => ['required', 'min:4'],
        ];
    }
 
    public function recordar() {
        return $this->get('recordar', 'false') === 'true';
    }
 
    public function credenciales() {
        return $this->only('usuario', 'password');
    }
 
    public function authorize() {
        return true;
    }
}