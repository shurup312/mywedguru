<?php
namespace userDetails\forms;

use yii\base\Model;

class WeddingForm extends Model
{

    public $groomFirstName;
    public $groomLastName;
    public $date;

    public function rules()
    {
        return [
            [['groomFirstName', 'groomLastName', 'date'], 'required'],
            [['groomFirstName', 'groomLastName'], 'trim'],
            [['groomFirstName', 'groomLastName'],  'string', 'max'=>32,'min'=>2],
        ];
    }

    public function attributeLabels()
    {
        return [
            'groomFirstName' => 'Имя жениха',
            'groomLastName'  => 'Фамилия жениха',
            'date'           => 'Дата свадьбы',
        ];
    }

    /**
     * @return mixed
     */
    public function groomLastName()
    {
        return $this->groomLastName;
    }

    /**
     * @return mixed
     */
    public function groomFirstName()
    {
        return $this->groomFirstName;
    }

    /**
     * @return mixed
     */
    public function date()
    {
        return $this->date;
    }
}
