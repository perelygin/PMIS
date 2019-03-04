<?php

use yii\db\Migration;

/**
 * Class m190220_142746_ServiceType1
 */
class m190220_142746_ServiceType1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
			$this->insert('ServiceType', [
	            'idServiceType' => '1',
	            'ServiceName' => 'Анализ требований',
	            'ServiceDescript'=>'Анализ и формализация бизнес требований к модифицированию Продуктов, составление функциональных требований и спецификаций на модификации Продуктов',
	            'idRole'=>'3'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '2',
	            'ServiceName' => 'Проектирование',
	            'ServiceDescript'=>'Проектирование модифицирования Продуктов и контроль за целостностью и оптимальностью архитектуры Продуктов при их модификации',
	            'idRole'=>'5'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '3',
	            'ServiceName' => 'Разработка',
	            'ServiceDescript'=>'Модификация Продуктов в соответствии с техническим заданием, согласованным в соответствии с заявкой Заказчика',
	            'idRole'=>'4'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '4',
	            'ServiceName' => 'Документирование',
	            'ServiceDescript'=>'Создание и модификация документации на Продукты',
	            'idRole'=>'8'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '5',
	            'ServiceName' => 'Тестирование',
	            'ServiceDescript'=>'Проверка результата оказания Услуг модификаций Продуктов на предмет наличия ошибок методами функционального и регрессионного тестирования',
	            'idRole'=>'6'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '6',
	            'ServiceName' => 'Анализ проблем',
	            'ServiceDescript'=>'Анализ проблем на стендах Заказчика, возникающих  при тестировании  Продуктов, оказание консультаций о причинах их возникновения',
	            'idRole'=>'3'
	        ]);
	        $this->insert('ServiceType', [
	            'idServiceType' => '7',
	            'ServiceName' => 'Консультирование',
	            'ServiceDescript'=>'Консультации и согласование методики нагрузочного тестирования, поддержка контура на время проведения нагрузочного тестирования, анализ экспресс-отчетов, согласование финального отчета',
	            'idRole'=>'3'
	        ]);
	        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('ServiceType', ['idServiceType' => 1]);
        $this->delete('ServiceType', ['idServiceType' => 2]);
        $this->delete('ServiceType', ['idServiceType' => 3]);
        $this->delete('ServiceType', ['idServiceType' => 4]);
        $this->delete('ServiceType', ['idServiceType' => 5]);
        $this->delete('ServiceType', ['idServiceType' => 6]);
        $this->delete('ServiceType', ['idServiceType' => 7]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190220_142746_ServiceType1 cannot be reverted.\n";

        return false;
    }
    */
}
