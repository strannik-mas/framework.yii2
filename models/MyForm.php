<?php
namespace app\models;
use Yii;
use yii\base\Model;
class MyForm extends Model
{
	public $name;
	public $email;
	public $file;
	
	public function rules(){
	
		return [
			[['name', 'email'], 'required', 'message' => 'Не заполнено поле!'],
			['email', 'email', 'message' => 'Некорректный адрес!'],					//для введения корректного адреса
			[['file'], 'file', 'extensions' => 'jpg, png']
		];
	}
}

?>