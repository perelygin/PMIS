<?php

use yii\db\Migration;

/**
 * Class m190110_133405_add_settins_mantis_defaultUser
 */
class m190110_133405_add_settins_mantis_defaultUser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
				$this->insert('settings', [
			            'id_param' => '7',
			            'Prm_name' => 'Mantis_default_user',
			            'Prm_description'=>'Пользователь mantis  по умолчанию',
			            'Prm_enum_id'=>'23',
			            'deleted'=>'0',
			        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->delete('settings', ['id_param' => 7]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190110_133405_add_settins_mantis_defaultUser cannot be reverted.\n";

        return false;
    }
    */
}
