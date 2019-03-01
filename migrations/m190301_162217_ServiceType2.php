<?php

use yii\db\Migration;

/**
 * Class m190301_162217_ServiceType2
 */
class m190301_162217_ServiceType2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
			$this->insert('ServiceType', [
	            'idServiceType' => '-1',
	            'ServiceName' => 'Без услуг',
	            'ServiceDescript'=>'Для старых работ',
	            'idRole'=>'7'
	        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->delete('ServiceType', ['idServiceType' => -1]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190301_162217_ServiceType2 cannot be reverted.\n";

        return false;
    }
    */
}
