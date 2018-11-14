<?php

use yii\db\Migration;

/**
 * Class m181113_155940_enum_setings
 */
class m181113_155940_enum_setings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
			$this->insert('EnumSettings', [
	            'idEnumSettings' => '16',
	            'id_param' => '5',
	            'enm_num_value'=>'0.00',
	            'enm_str_value'=>'http://mantis.it-spectrum.ru/vtb24-mantis/view.php?id=',
	        ]);
	        $this->insert('EnumSettings', [
	            'idEnumSettings' => '21',
	            'id_param' => '5',
	            'enm_num_value'=>'0.00',
	            'enm_str_value'=>'http://192.168.20.55/mantis/',
	        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->delete('EnumSettings', ['idEnumSettings' => 16]);
		$this->delete('EnumSettings', ['idEnumSettings' => 21]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181113_155940_enum_setings cannot be reverted.\n";

        return false;
    }
    */
}
