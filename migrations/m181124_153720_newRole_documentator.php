<?php

use yii\db\Migration;

/**
 * Class m181124_153720_newRole_documentator
 */
class m181124_153720_newRole_documentator extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('RoleModel', [
		'idRole' => 8,
		'idRoleModelType' =>1,
		'RoleName' =>'Технический писатель',
		'RoleComment'=>'Разрабатывает документацию',
		'idTariff'=>1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->delete('RoleModel', ['idRole' => 8]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181124_153720_newRole_documentator cannot be reverted.\n";

        return false;
    }
    */
}
