<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
	if($name)
	{
		echo "<p>Вы ввели имя <b>$name</b> и email <b>$email</b></p>";
	}
	$f = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
	echo $f->field($form, 'name');
	echo $f->field($form, 'email'); //для текстовых полей  можно не указывать тип поля, а для других надо
	echo $f->field($form, 'file')->fileInput();
	echo Html::submitButton("Отправить");
	
	ActiveForm::end();
?>