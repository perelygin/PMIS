<?php

use yii\db\Migration;

/**
 * Class m181120_140601_RoleModel_tariff
 */
class m181120_140601_RoleModel_tariff extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->update('RoleModel', array(
              'idTariff' => 1), 
              'idRole=1'
		);
		$this->update('RoleModel', array(
              'idTariff' => 1), 
              'idRole=2'
		);
		$this->update('RoleModel', array(
              'idTariff' => 1), 
              'idRole=3'
		);
		$this->update('RoleModel', array(
              'idTariff' => 4), 
              'idRole=4'
		);
		$this->update('RoleModel', array(
              'idTariff' => 3), 
              'idRole=5'
		);
		$this->update('RoleModel', array(
              'idTariff' => 6), 
              'idRole=6'
		);
		$this->update('RoleModel', array(
              'idTariff' => 1), 
              'idRole=7'
		);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181120_140601_RoleModel_tariff cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181120_140601_RoleModel_tariff cannot be reverted.\n";

        return false;
    }
    */
}
