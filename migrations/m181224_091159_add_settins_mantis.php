<?php

use yii\db\Migration;

/**
 * Class m181224_091159_add_settins_mantis
 */
class m181224_091159_add_settins_mantis extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('settings', [
			            'id_param' => '6',
			            'Prm_name' => 'Mantis_path_create',
			            'Prm_description'=>'Путь к экземпляру багтрекера для создания инцидентов (wsdl)',
			            'Prm_enum_id'=>'17',
			            'deleted'=>'0',
			        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('settings', ['id_param' => 6]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181224_091159_add_settins_mantis cannot be reverted.\n";

        return false;
    }
    */
}
