<?php

use yii\db\Migration;

/**
 * Class m181120_133010_tariff
 */
class m181120_133010_tariff extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('Tariff', [
		'idTariff' => 1,
		'TariffName' =>'Ведущий бизнес аналитик',
		'TariffRate' =>20000,
		'idProject'=>1
        ]);
        $this->insert('Tariff', [
		'idTariff' => 2,
		'TariffName' =>'Бизнес аналитик',
		'TariffRate' =>18800,
		'idProject'=>1
        ]);
        $this->insert('Tariff', [
		'idTariff' => 3,
		'TariffName' =>'Системный архитектор',
		'TariffRate' =>20000,
		'idProject'=>1
        ]);
        $this->insert('Tariff', [
		'idTariff' => 4,
		'TariffName' =>'Ведущий разработчик',
		'TariffRate' =>20000,
		'idProject'=>1
        ]);
        $this->insert('Tariff', [
		'idTariff' => 5,
		'TariffName' =>'Разработчик',
		'TariffRate' =>18800,
		'idProject'=>1
        ]);
        $this->insert('Tariff', [
		'idTariff' => 6,
		'TariffName' =>'Инженер по тестированию ПО',
		'TariffRate' =>15600,
		'idProject'=>1
        ]);
        $this->insert('Tariff', [
		'idTariff' => 7,
		'TariffName' =>'Специалист службы сопровождения и поддержки',
		'TariffRate' =>18800,
		'idProject'=>1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->delete('Tariff', ['idTariff' => 1]);
       $this->delete('Tariff', ['idTariff' => 2]);
       $this->delete('Tariff', ['idTariff' => 3]);
       $this->delete('Tariff', ['idTariff' => 4]);
       $this->delete('Tariff', ['idTariff' => 5]);
       $this->delete('Tariff', ['idTariff' => 6]);
       $this->delete('Tariff', ['idTariff' => 7]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181120_133010_tariff cannot be reverted.\n";

        return false;
    }
    */
}
