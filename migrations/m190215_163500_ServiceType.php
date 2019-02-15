<?php

use yii\db\Migration;

/**
 * Class m190215_163500_ServiceType
 */
class m190215_163500_ServiceType extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
			$this->insert('ServiceType', [
	            'idServiceType' => '1',
	            'ServiceName' => 'Анализ требований',
	            'ServiceDescript'=>'Анализ и формализация бизнес требований к модифицированию Продуктов, составление функциональных требований и спецификаций на модификации Продуктов'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '2',
	            'ServiceName' => 'Проектирование',
	            'ServiceDescript'=>'Проектирование модифицирования Продуктов и контроль за целостностью и оптимальностью архитектуры Продуктов при их модификации'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '3',
	            'ServiceName' => 'Разработка',
	            'ServiceDescript'=>'Модификация Продуктов в соответствии с техническим заданием, согласованным в соответствии с заявкой Заказчика'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '4',
	            'ServiceName' => 'Документирование',
	            'ServiceDescript'=>'Создание и модификация документации на Продукты'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '5',
	            'ServiceName' => 'Тестирование ',
	            'ServiceDescript'=>'Проверка результата оказания Услуг модификаций Продуктов на предмет наличия ошибок методами функционального и регрессионного тестирования'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '6',
	            'ServiceName' => 'Анализ проблем',
	            'ServiceDescript'=>'Анализ проблем на стендах Заказчика, возникающих  при тестировании  Продуктов, оказание консультаций о причинах их возникновения'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '7',
	            'ServiceName' => 'Консультирование',
	            'ServiceDescript'=>'Консультации и согласование методики нагрузочного тестирования, поддержка контура на время проведения нагрузочного тестирования, анализ экспресс-отчетов, согласование финального отчета'
	        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190215_163500_ServiceType cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190215_163500_ServiceType cannot be reverted.\n";

        return false;
    }
    */
}
