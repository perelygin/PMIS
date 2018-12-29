<?php

use yii\db\Migration;

/**
 * Class m181224_091604_add_settins_mantis_enum
 */
class m181224_091604_add_settins_mantis_enum extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
			$this->insert('EnumSettings', [
	            'idEnumSettings' => '17',
	            'id_param' => '6',
	            'enm_num_value'=>'0.00',
	            'enm_str_value'=>'http://192.168.20.55/mantisbt-2.3.1/api/soap/mantisconnect.php?wsdl',
	        ]);
	        $this->insert('EnumSettings', [
	            'idEnumSettings' => '18',
	            'id_param' => '6',
	            'enm_num_value'=>'0.00',
	            'enm_str_value'=>'http://mantis.it-spectrum.ru/vtb24-mantis/api/soap/mantisconnect.php?wsdl',
	        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->delete('EnumSettings', ['idEnumSettings' => 17]);
		$this->delete('EnumSettings', ['idEnumSettings' => 18]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181224_091604_add_settins_mantis_enum cannot be reverted.\n";

        return false;
    }
    */
}
