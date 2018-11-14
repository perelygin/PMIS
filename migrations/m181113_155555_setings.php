<?php

use yii\db\Migration;

/**
 * Class m181113_155555_setings
 */
class m181113_155555_setings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
			$this->insert('settings', [
	            'id_param' => '5',
	            'Prm_name' => 'Mantis_path',
	            'Prm_description'=>'Путь к экземпляру багтрекера',
	            'Prm_enum_id'=>'16',
	            'deleted'=>'0',
	        ]);
	        
	        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->delete('settings', ['id_param' => 1]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181113_155555_setings cannot be reverted.\n";

        return false;
    }
    */
}
