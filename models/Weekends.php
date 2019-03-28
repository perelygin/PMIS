<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Weekends".
 *
 * @property int $idWeekends
 * @property string $weekend
 */
class Weekends extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Weekends';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['weekend'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idWeekends' => 'Id Weekends',
            'weekend' => 'Weekend',
        ];
    }
    
    public function isWeekend($date)
    {
		$sql = "SELECT count(weekend) FROM Weekends where weekend = '".$date->format('Y-m-d')."'";
		$count = Yii::$app->db->createCommand($sql)
             ->queryScalar();
        //echo $count.'<br>'.$sql.'<br>';     
        if($count>0){
			return true;
			}else{
				return false;
				}     
		}
    
}
