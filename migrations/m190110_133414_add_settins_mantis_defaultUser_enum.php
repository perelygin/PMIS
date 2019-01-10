<?php

use yii\db\Migration;

/**
 * Class m190110_133414_add_settins_mantis_defaultUser_enum
 */
class m190110_133414_add_settins_mantis_defaultUser_enum extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
			$this->insert('EnumSettings', [
	            'idEnumSettings' => '23',
	            'id_param' => '7',
	            'enm_num_value'=>'0.00',
	            'enm_str_value'=>'perelygin',
	        ]);
	        $this->insert('EnumSettings', [
	            'idEnumSettings' => '24',
	            'id_param' => '7',
	            'enm_num_value'=>'0.00',
	            'enm_str_value'=>'tsyganok',
	        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('EnumSettings', ['idEnumSettings' => 23]);
		$this->delete('EnumSettings', ['idEnumSettings' => 24]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190110_133414_add_settins_mantis_defaultUser_enum cannot be reverted.\n";

        return false;
    }
    */
}
