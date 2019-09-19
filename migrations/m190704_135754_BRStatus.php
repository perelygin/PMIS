<?php

use yii\db\Migration;

/**
 * Class m190704_135754_BRStatus
 */
class m190704_135754_BRStatus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
				//$this->insert('BRStatus', [
		            //'idBRStatus' => '1',
		            //'BRStatusName' => 'Действует',
		        //]);
		        //$this->insert('BRStatus', [
		            //'idBRStatus' => '2',
		            //'BRStatusName' => 'Завершена',
		        //]);
		        //$this->insert('BRStatus', [
		            //'idBRStatus' => '3',
		            //'BRStatusName' => 'Отложена',
		        //]);
	        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->delete('BRStatus', ['idBRStatus' => 1]);
         $this->delete('BRStatus', ['idBRStatus' => 2]);
         $this->delete('BRStatus', ['idBRStatus' => 3]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190704_135754_BRStatus cannot be reverted.\n";

        return false;
    }
    */
}
